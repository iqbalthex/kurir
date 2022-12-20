<?php
session_start();

define('HOST', 'localhost');//'sql306.epizy.com');
define('USER', 'root');//'epiz_33232406');
define('PASS', '');//'c6H11p6gRaagO0w');
define('DB_NAME', 'apk_kurir');//'epiz_33232406_cari_kost');

define('DAY_IN_SEC', 86400);
define('HOUR_IN_SEC', 3600);
define('MINUTE_IN_SEC', 60);

try {
  $conn = mysqli_connect(HOST, USER, PASS);
} catch(mysqli_sql_exception $e) {
  var_dump($e);
}

$conn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
$conn->query("USE " . DB_NAME);

$conn->query("CREATE TABLE IF NOT EXISTS items(
  id INT(6) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(150) NOT NULL,
  lat VARCHAR(30) NOT NULL,
  lng VARCHAR(30) NOT NULL,
  arrival INT(11) NOT NULL
)");

$conn->query("CREATE TABLE IF NOT EXISTS users(
  id INT(6) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(100) NOT NULL,
  password VARCHAR(100) NOT NULL
)");

$conn = new mysqli(HOST, USER, PASS, DB_NAME);

function alert($msg=''){
  echo "<script>alert('$msg')</script>";
}
