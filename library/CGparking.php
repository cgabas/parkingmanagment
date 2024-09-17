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
     * @return array | bool
     */
    public static function processForm($DB, $array, $switch) {
        switch($switch) {
            // case login
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
                            'licenseType' => $user_D['licenseType'],
                            'dateOfBirth' => $user_D['dateOfBirth'],
                            'phoneNumber' => $user_D['phoneNumber'],
                            'regisNum' => $regis_D['regisNum'],
                            'isSuspended' => $regis_D['isSuspended'],
                            'engineHP' => $regis_D['engineHP']
                        ];
                    }
                    // return false if not
                    else {
                        echo "<script>alert('The entered car registration number doesn\'t exist under the user data');</script>";
                        return false;
                    }
                }
                // return false if user not found or not match
                else {
                    echo "<script>alert('The given identity number doesn\'t exist in our record');</script>";
                    return false;
                }
            
            default:
                // echo "<script>alert('by default');</script>";
                return false;
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
                // only access one session at one time
                // second argument for session name

                if(count($sessionList) === 1 && is_string($sessionList[0])) {
                    if(isset($_SESSION[$sessionList[0]])) {
                        session_write_close();
                        return true;
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
}