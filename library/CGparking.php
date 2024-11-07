<?php
/*
    - SKIP INTRO line 29 -

    CGparking module/library
    ---------------------------
    Created by Abas at 27th August 2024
    No copyright, absolutely free! If you see a copyright symbol, it's a joke!

    Intro:
    Shalom and Hello to Readers,

    To begin my word, it gives me a delighted felling that all of you that are currently reading this give up your time to read this.
    But firstly, pardon me for my awful writing skills if you noticed already because I have no respect for this beautiful language.
    Not because I always failed to master this language, the real reason is I still do not know how to use plural and singular correctly.
    That's all from me, I hope every one of you will have a very understanding on what is happening behind certain line of code.
    Lord have mercy on you!

    Sincerly, Sir Abas.

    Brief explanation:
    The reason for the existance of this library is to ease the project making of this system.
    This library contains accessable and reusable collection of variables and functions.
    Speaking of accessable, you cannot access functions statically except variables.
    This library should be use by each pages that requires a user input/output interaction between system.
    But using this library is still an option.
*/

// import any additional custom made or 3th party library (use Composer to install library, make sure the location is inside the `library` directory)
// you also will be given an another folder (if it your first time installing library onto that directory) called `vendor`

require_once "config/config_DB.php";

// most function are not static, please do `$xxx = new CGparking` before using function
class CGparking {
    // local var here
    // $xxx = xxx;

    // func's

    /**
    * Summary of loginInput
    * to take user input from HTML form.
    * @param mixed $DB 
    * @param mixed $input 
    * @return false | array
    */
    public static function formInput($DB, ...$input) {
        // check if every args does not contain any weird string
        $data = [];
        $count = 0;
        // checking each input by loop
        foreach($input as $i) {
            if(mysqli_real_escape_string($DB, $i)) {
                $data[$count++] = $i;
            }
            else {
                // quit loop if string is unsupported
                return false;
            }
        }
        // return all of the input recived
        return $data;
    }

    /**
     * Summary of processForm
     * to process an input recived from second args
     * NOTE: this library is only ment for PMS development, this function cannot be use to process any kind of data that is unrelated to PMS
     * @param mixed $DB
     * @param array $array
     * @return array | int
     */
    public static function processForm($DB, $array, $switch) {
        switch($switch) {
            // case login
            // NOTE:
            // FOR INPUT: input must be contain inside an array before passing it into the second argument
            // AND
            // FOR OUTPUT: the data need to be return as array also to avoid further system error & confusion when using the return data
            case "LOGIN":
                // 1 -> userID, 2 -> passcode, 3 -> regisNum
                // preparing statement
                $checkUser = $DB -> prepare("SELECT * FROM parkdb.user WHERE userID = ? AND passcode = ?");
                $checkUser -> bind_param("ss", $array[0], $array[1]);
                // execute stmt and get result
                $checkUser -> execute();
                $resUser = $checkUser -> get_result();

                // user data is true
                if($resUser -> num_rows > 0) {
                    // for register number
                    $checkRegis = $DB -> prepare("SELECT * FROM parkdb.registered_car WHERE userID = ? AND regisNum = ?");
                    $checkRegis -> bind_param("ss", $array[0], $array[2]);
                    // execute stmt and get result
                    $checkRegis -> execute();
                    $resRegis = $checkRegis -> get_result();

                    // registered_number is owned by user
                    if($resRegis -> num_rows > 0) {
                        // fetch data 
                        $user_D = $resUser -> fetch_assoc();
                        $regis_D = $resRegis -> fetch_assoc();

                        // return data
                        // list as needed only

                        return [
                            'fullname' => $user_D['fullname'],
                            'surname' => $user_D['surname'],
                            'userID' => $user_D['userID'],
                            'gender' => $user_D['gender'],
                            'email' => $user_D['email'],
                            'licenseID' => $user_D['licenseID'],
                            'licenseType' => $user_D['licenseType'],
                            'dateOfBirth' => $user_D['dateOfBirth'],
                            'phoneNumber' => $user_D['phoneNumber'],
                            'regisNum' => $regis_D['regisNum'],
                            'vehicleBrand' => $regis_D['vehicleBrand'],
                            'vehicleModel' => $regis_D['vehicleModel'],
                            'isSuspended' => $regis_D['isSuspended'],
                            'engineHP' => $regis_D['engineHP']
                        ];
                    }
                    // return false if not
                    else {
                        // 2 indicate as registered car number given by the user does not exist
                        return 2;
                    }
                }
                // return false if user not found or not match
                else {
                    // 1 indicate as identity number given by the user does not exist
                    return 1;
                }
                // code below this comment will mark as unrechable before another case
                // break;
            case "REGISTER":
                /*
                LIST of given data
                0.    $userID
                1.    $fullname
                2.    $surname -> can be NULL
                3.    $dateOfBirth
                4.    $passcode
                5.    $gender
                6.    $phoneNumber
                7.    $email -> can be NULL
                8.    $licenseID
                9.    $licenseType
                10.    $dateOfExpiry
                11.    $dateLatestRenew
                */

                $dataInsertion_user_query = "INSERT INTO user (userID, fullname, surname, dateOfBirth, passcode, gender, phoneNumber, email, licenseID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $dataInsertion_license_query = "INSERT INTO license (licenseID, licenseType, dateOfExpiry, dateLatestRenew) VALUES (?, ?, ?, ?)";
                $licenseCheck_query = "SELECT * FROM license WHERE licenseID = ? AND dateOfExpiry = ?";
                $userCheck_query = "SELECT * FROM user WHERE userID = ? OR phoneNumber = ?";
                // do data checking on each tables
                // 1. license
                if(
                    (isset($array[8]) || empty($array[8])) && // licenseID
                    (isset($array[10]) || empty($array[10])) // dateOfExpiry
                ) {
                    // code if licenseID & dateOfExpiry is not empty or NULL
                    $checklicense = $DB -> prepare($licenseCheck_query);
                    $checklicense -> bind_param("ss", $array[6], $array[10]);
                    $checklicense -> execute();
                    $checklicense_result = $checklicense-> get_result();
                    
                    // check if there is any matching row
                    if($checklicense_result -> num_rows > 0) {
                        // 1 indicate as licenseID already exist
                        return 1;
                    }
                }
                else {
                    // 3 indicate as all related data given is empty or null
                    return 3;
                }

                // 2. user
                if(
                    (isset($array[0]) || empty($array[0])) && // userID
                    (isset($array[6]) || empty($array[6])) // phoneNumber
                ) {
                    // code if userID & phoneNumber is not empty or NULL
                    $checkUser = $DB -> prepare($userCheck_query);
                    $checkUser -> bind_param("ss", $array[0], $array[6]);
                    $checkUser -> execute();
                    $checkUser_result = $checkUser -> get_result();

                    // check if there is any matching row
                    if($checkUser_result -> num_rows > 0) {
                        // 2 indicate as userID already exist or phoneNumber is already being used
                        return 2;
                    }
                }
                else {
                    // 3 indicate as all related data given is empty or null
                    return 3;
                }

                // data insertion process
                // prepare statement
                $insertInto_license = $DB -> prepare($dataInsertion_license_query); // count 9
                $insertInto_user = $DB -> prepare($dataInsertion_user_query); // count 4

                // check datatype for the surname
                $surname = empty($array[2]) || $array[2] === "" ? substr($array[1], 0, 5) : $array[2];

                // bind param
                $insertInto_license -> bind_param(
                    "ssss",
                    $array[8],
                    $array[9],
                    $array[10],
                    $array[11]
                );
                $insertInto_user -> bind_param(
                    "sssssssss",
                    $array[0],
                    $array[1],
                    $surname, // different
                    $array[3],
                    $array[4],
                    $array[5],
                    $array[6],
                    $array[7],
                    $array[8],
                );
                
                // execute license first, then user
                if($insertInto_license -> execute()) {
                    if($insertInto_user -> execute()) {
                        // 0 indicate successful data insertion for both
                        return [
                            0,
                            'surname' => $surname
                        ];
                    }
                }

                // 4 indicate as data insertion fail
                return 4;
                // break;
            default:
                // default action goes here 👇🏻
                // echo "<script>alert('by default');</script>";
                return -1;
        }
    }

    /**
     * Summary of tabTitle
     * to write out or print tab title
     * @param array $set
     * @return void
     */
    public static function tabTitle(...$set) {
        // check if args inserted is more than 1
        session_start();
        if(count($set) > 1 && is_string($set[1])) {
            // set empty string
            if($set[1] === "EMPTY") {
                $_SESSION["title"] = "";
            }
            // set new title
            elseif($set[1] === "SET") {
                $_SESSION["title"] = $set[0];
            }
            // do both
            elseif($set[1] === "WNR") {
                $_SESSION["title"] = $set[0];
                echo $_SESSION["title"];
            }
        }
        // print title
        elseif(count($set) === 0) {
            echo $_SESSION["title"];
        }

        // NOTE: if there is more than 2 args, it will simply ignore by the function
        session_write_close();
    }

    /**
     * Summary of sessionManagment
     * set inserted session or delete them
     * NOTE: please memorize their name carefuly
     * @param string $act
     * @param string | array $sessionList
     * @return string | void
     */
    public static function sessionManagment($act, ...$sessionList) {
        $SESSION_INI = parse_ini_file("config/SESSION.ini", true);
        session_save_path($SESSION_INI['PMS_SESSION']['PMS_SESSION_PATH']);
        session_name($SESSION_INI['PMS_SESSION']['PMS_SESSION_NAME_main']);
        session_start();
        // i have trust issue with switch case statement, hope this will work out
        // remember to use `break` after case
        switch($act) {
            case "SET":
                // usage: <CLASS>::sessionManagment("SET", [name, value]);
                // loop each args
                // only 2 indexs array will be in use
                for($i=0;$i<count($sessionList);$i++) {
                    if(is_array($sessionList[$i]) && count($sessionList[$i]) === 2) {
                        // first index is for name, second is for value
                        $_SESSION[$sessionList[$i][0]] = $sessionList[$i][1];
                    }
                }
                break;
            case "REMOVE":
                // usage: <CLASS>::sessionManagment("REMOVE", name);
                // remove based on name
                // only string will be accepted inside args
                // remove each var according to their name
                foreach($sessionList as $remove) {
                    // remove session var
                    if(is_string($remove)) {
                        unset($_SESSION[$remove]);
                    }
                }
                break;
            case "ACC":
                // usage: <CLASS>::sessionManagment("ACC", name);
                // only access one session at one time
                // second argument for session name

                if(count($sessionList) === 1 && is_string($sessionList[0])) {
                    if(isset($_SESSION[$sessionList[0]])) {
                        session_write_close();
                        return $_SESSION[$sessionList[0]]; // return any value inside the session variable
                    }
                }

                session_write_close();
                return false;
                // `break;` code was not added because it will be unreachable after `return false;` run
            
            case "RESTART":
                // simple, just close or abort the session system
                session_unset();
                session_destroy();
                session_write_close();
                return true;

            default:
                session_write_close();
                return false;
        }

        // default action before ending function
        session_write_close();

        // return true on success
        return true;
    }

    /**
     * Summary of checkValue
     * @param string $act
     * @param mixed $arrColumn
     * @param mixed $arrValue
     * @param string $table
     * @param mixed $retColumn
     * @param mixed $retValue
     * @return int & array
     */
    public static function checkValue($act, $arrColumn, $arrValue, $table, $retColumn, $retValue) {
        // switch mode smth🤦‍♂️
        switch($act) {
            case "SIMPLE":
                if(
                    (!is_array($arrColumn)) || 
                    (!is_array($arrValue)) || 
                    (!is_array($retColumn)) || 
                    (!is_array($retValue))
                ) {
                    $query = "SELECT $retColumn FROM $table WHERE $arrColumn = $arrValue";
                }
                else {
                    return 1; // argument inserted does not match the use
                }
            case "COMPLEX":
                $columnSelection = "";
                foreach($arrColumn as $aCol) {
                    // hit me up later
                }
        }
        return [];
    }
}