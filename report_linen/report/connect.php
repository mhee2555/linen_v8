<?php

/*
 * set var
 */
$cfHost = "poseintelligence.dyndns.biz:3308";
$cfUser = "root";
$cfPassword = "A$192dijd";
$cfDatabase = "linen_test";

// A$192dijd

/*
 * connection mysql
 */
$conn = mysqli_connect($cfHost, $cfUser, $cfPassword,$cfDatabase);
// Check connection
if (mysqli_connect_errno()){
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

mysqli_set_charset($conn, "utf8");
set_time_limit(0);
ini_set('mysql.connect_timeout','0');
ini_set('max_execution_time', '0');
?>
