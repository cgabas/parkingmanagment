<!-- backend -->
<?php
    require_once "pageStarter.php";

    // process form data
    if(isset($_POST['login'])) {
        // input recived from form input
        $input = CGparking::formInput(CGconfig::getConnect(), $_POST['noic'], $_POST['passcode'], $_POST['regis']);

        // check if login process is success
        if($session_D = CGparking::processForm(CGconfig::getConnect(), $input, 'LOGIN')) {
            // set session and direct user to dashboard_PSM.php
            foreach($session_D as $name => $val) {
                CGparking::sessionManagment('SET', [$name, $val]);
            }
            header("Location: dashboard_PMS.php");
            // echo "<script>window.location = 'dashboard_PMS.php';</script>";
        }
        else {
            // return back to login page
            echo "<script>
                document.getElementById('errMsg').innerHTML = 'Sila Semak Semula Maklumat Anda!';
                window.location = 'index.php';
            </script>";
        }
    }
?>

<!-- frontend -->
<!DOCTYPE html>
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
            <center><a href="register.php" id="formLink">New user? Register now</a></center>
        </div>
    </body>
</html>