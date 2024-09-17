<?php
    /*
        halo eglis peaker!!! me name is Abas, me do code, me do eat, me do sleep

        anyways...
        AUTOSTART.php script functions
        - initializing every function that is needed for the system whole process
        - check system connection to database before start
        - giving every pages accessabiliy to access session variables(please edit the var back if you consern about privacy ;))
    */

    // link to custom made config library that contain CGconfig© class for all system initializing uses
    require_once "library/config/config_DB.php";
    require_once "library/CGparking.php";

    // taking connection info from init file
    $config = parse_ini_file("library/config/SERVER/MYSQL_CONNECT_FILE.ini", true);

    // store $config inside a multidimentional array
    $conf = [
        $config["DB"]["host"],
        $config["DB"]["user"],
        $config["DB"]["pass"],
        $config["DB"]["db"]
    ];

    // initializing mysql object type connection -> mysqli_connect
    CGconfig::init($conf);

    // check if mysqli_connection initialization is working or not
    if(CGconfig::getConnect()) {
        // check 
        if(CGconfig::initCheck(CGconfig::getConnect())){
            // put useful variables here

            // setting some session var
            CGparking::sessionManagment("SET", ["usrLoginStmt", 0], ["usrNoI", NULL], ["usrCarRegis", NULL]);
        }
    }