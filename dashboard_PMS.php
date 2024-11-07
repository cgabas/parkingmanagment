<?php
    require_once "library/CGparking.php";
    require_once "pageStarter.php";
?>
<!DOCTYPE html>
<html lang="en">
<?php include_once "library/head.php"; ?>
<script type="module" src="importMe.js"></script>
<body>
    <div class="header">
        <h1>Welcome <?php echo CGparking::sessionManagment('ACC', "surname"); ?>!</h1>
        <hr>
        <h4><i><?php
            $printCar = CGparking::sessionManagment("ACC", "vehicleBrand") . " " . CGparking::sessionManagment("ACC", "vehicleModel");
            echo $printCar;
        ?></i></h4>
        <h3><strong><?php echo CGparking::sessionManagment("ACC", "regisNum"); ?></strong></h3>
    </div>
    <div class="content">
        <div class="coupon"><?php
            $userID = CGparking::sessionManagment("ACC", "userID");
            $regisNum = CGparking::sessionManagment("ACC", "regisNum");

            $isOpccupyingParkSpace = mysqli_query(CGconfig::getConnect(), "SELECT isInParkSpace FROM registered_car WHERE userID = '$userID' AND regisNum = '$regisNum'");
            if ($isOpccupyingParkSpace->num_rows > 0) {
                $queryCoupon = "SELECT *, TIMEDIFF(time_end, CURTIME()) AS time_left 
                                FROM recorded_parking 
                                WHERE issued_date = CURDATE() 
                                AND (time_start <= CURTIME() && time_end >= CURTIME())";
                $checkUserCoupon = mysqli_query(CGconfig::getConnect(), $queryCoupon);

                if ($checkUserCoupon->num_rows > 0) {
                    $ret_checkUserCoupon = $checkUserCoupon->fetch_assoc();
                    $time_left = $ret_checkUserCoupon['time_left'];
                    echo "<h2>You have $time_left left.</h2>";
                } else {
                    include_once "library/pay_menu.php";
                }
            } else {
                echo "<h2>No parking space occupied</h2>";
            }
        ?></div>
        <small>Note: You can leave the parking space before paying if you did not intend to pay</small>
        <div class="modalOverlay" id="modalOverlay">
            <div class="recipt">
                <button id="close-recipt">X</button>
                <center><h2>Payment Successful!</h2></center>
                <hr>
                <h4>Name: <b><?php echo CGparking::sessionManagment("ACC", "fullname"); ?></b></h4>
                <h4>ID: <b><?php echo CGparking::sessionManagment("ACC", "userID"); ?></b></h4>
                <h4>Phone Number: <b><?php echo CGparking::sessionManagment("ACC", "phoneNumber"); ?></b></h4>
                <h4>Car Registration Number: <b><?php echo CGparking::sessionManagment("ACC", "regisNum"); ?></b></h4>
                <h4>Car Brand: <b><?php echo CGparking::sessionManagment("ACC", "vehicleBrand"); ?></b></h4>
                <h4>Car Model: <b><?php echo CGparking::sessionManagment("ACC", "vehicleModel"); ?></b></h4>
                <hr>
                <center><h2>You were given</h2></center>
                <center><h3><?php
                    $addHour_dis = $_COOKIE['addHour'] ?? 0;
                    $addMinute_dis = $_COOKIE['addMinute'] ?? 0;
                    echo "$addHour_dis Hour(s) & $addMinute_dis Minute(s)";
                ?></h3></center>
                <small>~ PMS 2024 ~</small>
            </div>
        </div>
    </div>
    <script>
        let hour = 0;
        let minute = 0;

        document.getElementById('time_dur_015').onclick = function() { minute += 15; };
        document.getElementById('time_dur_030').onclick = function() { minute += 30; };
        document.getElementById('time_dur_100').onclick = function() { hour++; };
        document.getElementById('time_dur_130').onclick = function() { hour++; minute += 30; };
        document.getElementById('time_dur_200').onclick = function() { hour += 2; };

        document.getElementById('cls_all').onclick = function() { hour = 0; minute = 0; };
        document.getElementById('close-recipt').onclick = function() { document.getElementById('modalOverlay').style.display = 'none'; };
        document.getElementById('pay').onclick = function() {
            document.cookie = `addHour=${hour}; path=/; SameSite=Lax`;
            document.cookie = `addMinute=${minute}; path=/; SameSite=Lax`;
        };
        document.getElementById('add_time_dur').onclick = function() {
            hour = parseInt(document.getElementById('time_dur_cus_hour').value) || 0;
            minute = parseInt(document.getElementById('time_dur_cus_minute').value) || 0;
        };

        function openRecipt() {
            document.getElementById('modalOverlay').style.display = 'block';
        }

        setInterval(() => {
            if (minute >= 60) {
                hour++;
                minute -= 60;
            }
            document.getElementById('dis_time_dur').innerHTML = `${hour} Hour(s) & ${minute} Minute(s)`;
        }, 100);
    </script>
    <?php include_once "library/navigation.php"; ?>
</body>
</html>
<?php
    if (isset($_POST['pay'])) {
        $timeDurration = $_COOKIE['addHour'] . ":" . $_COOKIE['addMinute'] . ":00";
        $randBytes = random_bytes(2);
        $randCouponID = bin2hex($randBytes) . date("dmy") . date("His");
        
        $queryRecord = "INSERT INTO recorded_parking (couponID, regisNum, time_start, time_end, issued_date) VALUES (?, ?, CURTIME(), ADDTIME(CURTIME(), ?), CURDATE())";
        $recordStmt = CGconfig::getConnect()->prepare($queryRecord);
        $recordStmt->bind_param("sss", $randCouponID, $regisNum, $timeDurration);

        if ($recordStmt->execute()) {
            echo "<script>document.getElementById('modalOverlay').style.display = 'block';</script>";
        } else {
            echo "<script>alert('Unable to process the payment, please try again.'); window.location = 'dashboard_PMS.php';</script>";
        }
    }
?>
