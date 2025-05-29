<?php
$host = '127.0.0.1';
$dbUser = 'root';
$dbPass = '';
$dbName = 'webtech section a';

$con = mysqli_connect($host, $dbUser, $dbPass, $dbName);

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
