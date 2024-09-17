<?php
require_once "CGparking.php";
CGparking::sessionManagment("RESTART");
// echo "<script>window.location = '../index.php';</script>";
header('Location: ../index.php');