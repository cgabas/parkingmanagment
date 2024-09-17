<?php
    // i added CG just to earn my self a credit. this does not effect code complexity
    // also, i prefer CGconfig© but if you don't agree with me, i will touch you then😈😈

    // CGconfig class
    /*
        func count = 4
        local var = 1
    */
    class CGconfig {
        // database initialization

        // default initialize variable
        private static $init;

        // initialize connection
        public static function init($conf) {
            self::$init = new mysqli($conf[0], $conf[1], $conf[2], $conf[3]);
        }

        // check database connection and host PHP version. stop program if error detected
        // send 0 if no error detected
        public static function initCheck($DB) {
            // check server login information to MYSQL/MARIADB
            if(self::$init -> connect_error) {
                die($GLOBALS["config"]["msg"]["dbFail1"] . "<br>" . $GLOBALS["config"]["msg"]["dbFail2"] . "<br><center>Error: ".self::$init -> connect_error."</center>");
            }
            // pass check if host are running ver 8.1.0 and above
            if($ver = phpversion() < "8.1.0") {
                die($GLOBALS["config"]["msg"]["verFail1"] . "<br>" . $GLOBALS["config"]["msg"]["verFail2"] . "PHP Version : $ver");
            }

            // if there is no error, then return true
            // auto close connection after this function done execution
            return true;
        }

        // closing mysql connection
        public static function closeInit() {
            // check if $init is NULL or not
            if(self::$init) {
                self::$init -> close();
                self::$init = NULL;
            }
        }

        // return mysql connection for other database interaction uses
        public static function getConnect() {
            return self::$init;
        }
    }