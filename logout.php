<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connect.php';

session_start();
$_SESSION['logout_message'] = "You have been successfully logged out.";
session_unset();
session_destroy();
header("location: login.php");
exit();
?>