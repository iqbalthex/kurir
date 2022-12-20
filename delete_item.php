<?php

require_once 'config.php';

if(isset($_GET['id'])){
  $conn->query("DELETE FROM items WHERE id=$_GET[id]");
  if($conn->affected_rows > 0){
    $msg = 'Pengiriman berhasil dibatalkan!';
  } else {
    $msg = 'Pengiriman gagal dibatalkan!';
  }

  $_SESSION['msg'] = $msg;
  header('Location: index.php');
}
