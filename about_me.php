<?php
    require_once "library/CGparking.php";
    require_once "library/config/config_DB.php";
    require_once "pageStarter.php";

    // gather all session variables for each data
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
    <h1>Your Info</h1>
    <hr>
    <button id="back" onclick="dashboard_PMS();"><img src="sources/image/arrow_left.png" alt="Go Back To Dashboard"><span>Back</span></button>
        <div class="outputBox">
            <h4>Fullname</h4>
            <p><?php echo CGparking::sessionManagment("ACC", "fullname");?></p>
        </div>
        <div class="outputBox">
            <h4>Surname</h4>
            <p><?php echo CGparking::sessionManagment("ACC", "surname");?></p>
        </div>
        <div class="outputBox">
            <h4>I.C Number</h4>
            <p><?php echo CGparking::sessionManagment("ACC", "userID");?></p>
        </div>
        <div class="outputBox">
            <h4>Date Of Birth</h4>
            <p><?php echo CGparking::sessionManagment("ACC", "dateOfBirth");?></p>
        </div>
        <div class="outputBox">
            <h4>Phone Number</h4>
            <p><?php echo CGparking::sessionManagment("ACC", "phoneNumber");?></p>
        </div>
        <div class="outputBox">
            <h4>Email</h4>
            <p><?php echo CGparking::sessionManagment("ACC", "email");?></p>
        </div>
        <div class="outputBox">
            <h4>License ID</h4>
            <p><?php echo CGparking::sessionManagment("ACC", "licenseID");?></p>
        </div>
    <?php include_once "library/navigation.php"; ?>
</body>
</html>