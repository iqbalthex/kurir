<?php

session_start();
$_SESSION['logged_in'] = false;
unset($_SESSION['logged_in']);
session_destroy();

header('Location: index.php');
