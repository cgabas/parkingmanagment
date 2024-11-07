<!-- backend -->
<?php
    require_once "pageStarter.php";

    // process form data
    if(isset($_POST['login'])) {
        // input recived from form input
        $input = CGparking::formInput(CGconfig::getConnect(), $_POST['noic'], $_POST['passcode'], $_POST['regis']);

        // check if login process is success
        $session_D = CGparking::processForm(CGconfig::getConnect(), $input, 'LOGIN');
        if(is_array($session_D) && isset($session_D)) {
            // set session and direct user to dashboard_PSM.php
            foreach($session_D as $name => $val) {
                CGparking::sessionManagment('SET', [$name, $val]);
            }

            // set cookie to flag user is successfuly logged in
            setcookie("userLogged", true);

            // userID is usefull during checkUserCar.php process
            header("Location: dashboard_PMS.php");
            // echo "<script>window.location = 'dashboard_PMS.php';</script>";
        }
        elseif(is_int($session_D) && $session_D === 2) {
            echo "<script>
            alert('The entered car registration number doesn\'t exist under the user data');
            window.location = 'index.php';
            </script>";
        }
        elseif(is_int($session_D) && $session_D === 2) {
            echo "<script>
            alert('The given identity number doesn\'t exist in our record');
            window.location = 'index.php';
            </script>";
        }
        else {
            // return back to login page
            echo "<script>
            alert('There is something wrong with the data given during process, please stay calm and try again.');
            window.location = 'index.php';
            </script>";
        }
    }
?>

<!-- frontend -->
<!DOCTYPE html>
<script type="module" src="importMe.js"></script>
<html lang="en">
    <?php include_once "library/head.php";?>
    <body>
        <div class="background">
            <img src="sources/image/index_background.jpg" alt="background image">
        </div>
        <div class="formArea">
            <h1>Login</h1>
            <h3>Sarawak Parking Managment System</h3>
            <p id="errMsg"></p> <!-- only appeal with context -->
            <!-- form start from here -->
            <form action="#" method="post">
                <div class="inputWrap">
                    <input maxlength="12" minlength="12" type="text" name="noic" placeholder=" " id="noic" required>
                    <label for="noic">Identity Number</label>
                </div>
                <div class="inputWrap">
                    <input maxlength="40" type="password" name="passcode" placeholder=" " id="passcode" required>
                    <label for="passcode">Passcode</label>
                </div>
                <div class="inputWrap">
                    <input maxlength="10" type="text" name="regis" placeholder=" " id="regis" required>
                    <label for="regis">Car Registration/Plate Num</label>
                </div>
                <button type="submit" name="login">Login</button>
            </form>
            <!-- end form -->
            <center><a id="formLink" onclick="CGobj.gotoRegis();">New user? Register now</a></center>
        </div>
    </body>
</html>