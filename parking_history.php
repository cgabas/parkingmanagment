<?php
    require_once "library/CGparking.php";
    require_once "pageStarter.php";
?>
<!-- frontend -->
 <!DOCTYPE html>
 <html lang="en">
    <?php include_once "library/head.php"; ?>
    <script>
        function dashboard_PMS() {
            document.cookie = 'style=4;path=/;';
            window.location = 'dashboard_PMS.php';
        }
    </script>
    <body>
        <h1>History</h1>
        <hr>
        <center><button id="back" onclick="dashboard_PMS();"><img src="sources/image/arrow_left.png" alt="Go Back To Dashboard"><span>Back</span></button></center>
        <h3>List Of Every Parking Record</h3>
        <div id="contain-record">
            <?php
                $regisNum = CGparking::sessionManagment("ACC", "regisNum");
                $showAllRecord = mysqli_query(CGconfig::getConnect(), "SELECT * FROM recorded_parking WHERE regisNum = '$regisNum'");

                if($showAllRecord -> num_rows > 0) {
                    $count = 0;
                    while($rowData = mysqli_fetch_assoc($showAllRecord)) {
                        $count++;
                        echo "<div class=\"row-data\"><small><b>$count</b></small><h4>couponID: <b>{$rowData['couponID']}</b></h4><br><h4>Date: <b>{$rowData['issued_date']}</b></h4><br><h4>Time Start: <b>{$rowData['time_start']}</b></h4><br><h4>Time End: <b>{$rowData['time_end']}</b></h4></div>";
                    }
                }
                else {

                }
            ?>
        </div>
        <?php include_once "library/navigation.php"; ?>
    </body>
 </html>