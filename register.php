<!-- module CGjavascript -->
<script type="module" src="importMe.js"></script>
<?php
    // backend process
    // all in this file

    require_once "library/CGparking.php"; // to obtain usable functions inside the file
    require_once "pageStarter.php"; // to obtain mysqli_connection that has been initialize from the file already

    // start
    if(isset($_POST['register'])) {
        // gather all data from form submission
        $userID = mysqli_real_escape_string(CGconfig::getConnect(), $_POST['userID']) ?? NULL;
        $fullname = mysqli_real_escape_string(CGconfig::getConnect(), $_POST['fullname']) ?? NULL;
        $surname = mysqli_real_escape_string(CGconfig::getConnect(), $_POST['surname']) ?? NULL; // optional
        $dateOfBirth = mysqli_real_escape_string(CGconfig::getConnect(), $_POST['dateOfBirth']) ?? NULL;
        $passcode = mysqli_real_escape_string(CGconfig::getConnect(), $_POST['passcode']) ?? NULL;
        $gender = mysqli_real_escape_string(CGconfig::getConnect(), $_POST['gender']) ?? NULL;
        $phoneNumber = mysqli_real_escape_string(CGconfig::getConnect(), $_POST['phoneNumber']) ?? NULL;
        $email = mysqli_real_escape_string(CGconfig::getConnect(), $_POST['email']) ?? NULL; // optional
        $licenseID = mysqli_real_escape_string(CGconfig::getConnect(), $_POST['licenseID']) ?? NULL;
        $licenseType = mysqli_real_escape_string(CGconfig::getConnect(), $_POST['licenseType']) ?? NULL;
        $dateOfExpiry = mysqli_real_escape_string(CGconfig::getConnect(), $_POST['dateOfExpiry']) ?? NULL;
        $dateLatestRenew = mysqli_real_escape_string(CGconfig::getConnect(), $_POST['dateLatestRenew']) ?? NULL;
        // total of: 11 variables declared
        
        // preparing data into array
        $standbyData = [
            $userID,
            $fullname,
            $surname,
            $dateOfBirth,
            $passcode,
            $gender,
            $phoneNumber,
            $email,
            $licenseID,
            $licenseType,
            $dateOfExpiry,
            $dateLatestRenew
        ];

        // insert the array to be process

        // NOTE: becareful with the getConnect() for it might sometimes return NULL which is not good, but this will never happend!
        $register_user = CGparking::processForm(CGconfig::getConnect(), $standbyData, "REGISTER");

        if(is_array($register_user) && $register_user[0] === 0) { // success
            $surname = isset($register_user['surname']) && !empty($register_user['surname']) ? $register_user['surname'] : 'User';
            
            echo "
            <script>
                alert(`Welcome $surname`);
                document.cookie = 'userRegis=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC; SameSite=Lax';
                window.location = 'index.php';
            </script>
            ";

            // // run this code if javascript is not working
            // unset($_COOKIE['userRegis']);
            // header("Location: index.php");
        }
        else {
            if($register_user === 2 || $register_user === 1) { // userID already exist or phoneNumber is already being used
                echo "<script>alert('UserID Or Phone Number is already being used'); comeBack();</script>";
            }
            elseif($register_user === 3) { // all related(non-null) data inside the given array is null
                echo "<script>alert('Please fill out everything that is nessesary inside the form'); comeBack();</script>";
            }
            elseif($register_user === 4) { // data insertion fail
                echo "<script>alert('We are sorry, the registration process seems to be fail. But don't worry, you can always try again!'); comeBack();</script>";  
            }
        }      
    }
?>

<!-- frontend -->
<!DOCTYPE html>
<html lang="en">
<?php include_once "library/head.php"; ?>
<body>
    <header>
        <div class="title">
            <h1>Register</h1>
            <h2>For New User!</h2>
        </div>
    </header>
    <form action="#" method="post">
        <h4>Fill in the form below to experience every PMS features!</h4>

        <br>

        <span class="input-span">
            <label for="userID">Identification Number <strong>(REQUIRED)</strong>(I.C/KadPengenalan)</label>
            <input type="text" name="userID" id="userID" minlength="12" maxlength="12" pattern="\d{12}" placeholder="No '-' required" autofocus required>
        </span>

        <br>

        <span class="input-span">
            <label for="fullname">Fullname <strong>(REQUIRED)</strong></label>
            <input type="text" name="fullname" id="fullname" maxlength="128" minlength="4" placeholder="Same as it shown on your ID" required>
        </span>

        <span class="input-span">
            <label for="surname">Surname</label>
            <input type="text" name="surname" id="surname" maxlength="64" minlength="2" placeholder="Optional">
        </span>

        <span class="input-span">
            <label for="dateOfBirth">Date Of Birth</label>
            <input type="date" name="dateOfBirth" id="dateOfBirth" required>
        </span>

        <span class="input-span">
            <label for="passcode">Password</label>
            <input type="password" name="passcode" id="passcode" placeholder="Use a strong password!" maxlength="40" required>
        </span>

        <span class="input-span">
            <label for="gender">Gender</label>
            <select name="gender" id="gender" required>
                <option value="L">Male</option>
                <option value="P">Female</option>
            </select>
        </span>

        <span class="input-span">
            <label for="phoneNumber">Phone Number</label>
            <input type="tel" name="phoneNumber" id="phoneNumber" placeholder="Your frequently used number" pattern="[0-9]{10,16}" maxlength="16" required>
        </span>

        <span class="input-span">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="Optional" maxlength="64">
        </span>

        <span class="input-span">
            <label for="licenseID">Driving license ID</label>
            <input type="text" name="licenseID" id="licenseID" placeholder="6-digit number AND must VALID!" minlength="6" maxlength="6" required>
        </span>

        <span class="input-span">
            <label for="licenseType">License Type</label>
            <select name="licenseType" id="licenseType" required>
                <option value="L">L - Learner</option>
                <option value="P">P - Probationary</option>
            </select>
        </span>

        <span class="input-span">
            <label for="dateOfExpiry">License's Date Of Expiry</label>
            <input type="date" name="dateOfExpiry" id="dateOfExpiry" required>
        </span>

        <span class="input-span">
            <label for="dateLatestRenew">License's Latest Renew Date</label>
            <input type="date" name="dateLatestRenew" id="dateLatestRenew" required>
        </span>

        <br>

        <button type="submit" name="register" style="display: none;" id="remoteSubmitButton"></button>
    </form>
    <footer>
        <div class="group-button">
            <button onclick="CGobj.submit('remoteSubmitButton')" name="register">Register</button>
            <button type="reset">Reset Form</button>
            <button onclick="CGobj.back()">Log In</button>
        </div>
    </footer>
</body>
</html>