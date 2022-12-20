<?php

require_once 'config.php';

if(!isset($_SESSION['user_id'])){
  header('Location: login_page.php');
  exit;
}

if(isset($_SESSION['msg'])){
  alert($_SESSION['msg']);
  unset($_SESSION['msg']);
}

if(isset($_POST['item'])){
  $item_name = $_POST['item-name'];
  $arrival = time() + $_POST['delivery-duration'];

  if ($_POST['destination'] !== '') {
    [ $lat, $lng ] = explode(', ', $_POST['destination']);
  } else {
    [ $lat, $lng ] = [0, 0];
  }

  $conn->query("INSERT INTO items VALUES(
    '',
    '$item_name',
    '$lat',
    '$lng',
    '$arrival'
  )");

  if($conn->affected_rows > 0){
    alert('Barang berhasil ditambahkan!');
  }
}

$result = $conn->query("SELECT * FROM items");
$items = [];
while($item = $result->fetch_object()){
  $remain_time = $item->arrival - time();

  if($remain_time > 0){
    $day = (int)($remain_time / DAY_IN_SEC);
    $remain_time -= ($day * DAY_IN_SEC);

    $hour = (int)($remain_time / HOUR_IN_SEC);
    $remain_time -= ($hour * HOUR_IN_SEC);

    $minute = (int)($remain_time / MINUTE_IN_SEC);
    $second = (int)($remain_time - ($minute * MINUTE_IN_SEC));

    $item->arrival = [
      'day' => $day,
      'hour' => $hour,
      'minute' => $minute,
      'second' => $second,
    ];
  } else {
    $item->arrival = false;
  }
  $items[] = $item;
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Simple Map</title>
	<meta name="viewport" content="initial-scale=1.0">
	<meta charset="utf-8">

	<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" /> -->
	<link rel="stylesheet" type="text/css" href="bootstrap.min.css" />

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
  

	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body class="d-grid text-center">

<header class="d-flex justify-content-between align-items-center px-3">
  <h2>Aplikasi pengiriman barang</h2>
  <div>
    <ul class="d-flex align-items-center pt-2 pe-1">
      <li>
        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#addItem">
          Tambah barang
        </button>
      </li>
      <li>
        <div class="dropdown notif">
          <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            Notifikasi
          </button>
          <ul class="dropdown-menu">
            <?php
            if(count($items) > 0):
              foreach($items as $item): ?>
              <li data-lat="<?= $item->lat ?>"
                  data-lng="<?= $item->lng ?>">
                <a class="dropdown-item">
                  <p class="d-flex justify-content-between">
                    <span><?= $item->name ?></span>
                    <?php
                    if($item->arrival){
                      echo "<span>Perkiraan sampai:
                        {$item->arrival['day']} hari,
                        {$item->arrival['hour']} jam,
                        {$item->arrival['minute']} menit,
                        {$item->arrival['second']} detik
                      </span>";
                    } else {
                      echo '<span>Barang telah sampai</span>';
                    }
                    ?>
                    <a class="btn btn-outline-danger" href="delete_item.php?id=<?= $item->id ?>" onclick="return confirm('Yakin ingin membatalkan?')"><b>Batalkan pengiriman</b></a>
                  </p>
                </a>
              </li>
            <?php
              endforeach;
            else: ?>
              <li><a class="dropdown-item d-flex justify-content-center"><p>Tidak ada barang yang sedang dikirim</p></a></li>
            <?php endif ?>
          </ul>
        </div>
      </li>
      <li>
        <div class="dropdown">
          <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <i class="bi bi-person-circle"></i>
            Akun
          </button>
          <ul class="dropdown-menu p-0">
            <li>
              <a class="dropdown-item py-0" href="#">
                <i class="bi bi-person-lines-fill"></i> Profil
              </a>
            </li>
            <li>
              <a class="dropdown-item py-0" href="logout.php">
                <i class="bi bi-box-arrow-left"></i> Keluar
              </a>
            </li>
          </ul>
        </div>
      </li>      
    </ul>
  </div>
</header>

<main class="bg-info">
  <div id="map"></diV>
</main>

<footer class="">
  <p>IAM &copy; 2022</p>
</footer>

<div class="modal fade" id="addItem" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="ModalLabel">Tambah barang</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="" method="post">
        <div class="modal-body">
          <div class="form-floating mb-2">
            <input type="text" class="form-control" id="item-name" name="item-name" placeholder="Nama barang" required />
            <label for="item-name">Nama barang</label>
          </div>
          <div class="input-group">
            <div class="form-floating mb-2">
              <input type="text" class="form-control" id="destination" name="destination" value="0, 0" placeholder="Tujuan pengiriman" />
              <label for="destination">Tujuan pengiriman</label>
            </div>
            <button id="set-dest-btn" class="btn btn-warning" type="button">Ganti tujuan</button>
          </div>
          <div class="form-floating">
            <select class="form-select" id="delivery-duration" name="delivery-duration">
              <option value="43200" selected>12 jam</option>
              <option value="86400">1 hari</option>
              <option value="172800">2 hari</option>
              <option value="259200">3 hari</option>
            </select>
            <label for="delivery-duration">Durasi pengiriman</label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" name="item">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDiIKIfaFR_ubQUDVDzO5D-LwY_4biVMqc&callback=initMap&v=weekly" defer></script>

<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script> -->
<script type="text/javascript" src="bootstrap.bundle.min.js"></script>

<script src="index.js"></script>

</body>
</html>