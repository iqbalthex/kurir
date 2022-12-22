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

$query = "SELECT * FROM items";
// $query = "SELECT * FROM items JOIN users";
if ($_SESSION['role'] !== 'admin') {
  $query .= " WHERE user_id = $_SESSION[user_id]";
}
$result = $conn->query($query);

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
	<meta name="viewport" content="initial-scale=1.0" />
	<meta charset="utf-8" />

	<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
	<!-- <link rel="stylesheet" type="text/css" href="bootstrap.min.css" /> -->

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" />
  
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body class="d-grid text-center">

<header class="d-flex justify-content-between align-items-center px-3">
  <h2>Aplikasi pengiriman barang</h2>
  <div>
    <ul class="d-flex align-items-center pt-2 pe-1">
      <li>
        <a type="button" class="btn" href="index.php">
          Lihat peta
        </a>
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

<main class="bg-info d-flex justify-content-center align-items-start px-4 pt-3 fs-5">
  <table class="table table-striped table-success">
    <thead>
      <tr>
        <th>No</th>
        <th>Nama barang</th>
        <!--?php if ($_SESSION['role'] === 'admin'): ?>
          <th>Kurir</th>
        <!?php endif ?-->
        <th>Lokasi tujuan</th>
        <th>Perkiraan sampai</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
    <?php $no = 1 ?>
    <?php foreach($items as $item): ?>
      <tr>
        <th><?= $no++ ?></th>
        <td><?= $item->name ?></td>
        <!--td><?= $item->username ?></td-->
        <td><?= $item->lat ?>, <?= $item->lng ?></td>
        <td><?= "
          {$item->arrival['day']} hari,
          {$item->arrival['hour']} jam,
          {$item->arrival['minute']} menit,
          {$item->arrival['second']} detik
        " ?></td>
        <td>
          <a class="btn btn-outline-danger" href="delete_item.php?id=<?= $item->id ?>&redirect=items_list" onclick="return confirm('Yakin ingin membatalkan?')"><b>Batalkan pengiriman</b></a>
        </td>
      </tr>
    <?php endforeach ?>
    </tbody>
  </table>
</main>


<footer class="">
  <p>IAM &copy; 2022</p>
</footer>

<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDiIKIfaFR_ubQUDVDzO5D-LwY_4biVMqc&callback=initMap&v=weekly" defer></script> -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<!-- <script type="text/javascript" src="bootstrap.bundle.min.js"></script> -->

<script src="index.js"></script>

</body>
</html>