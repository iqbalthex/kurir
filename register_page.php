<?php
require_once 'config.php';

if(isset($_SESSION['user_id'])){
  header('Location: index.php');
}

if(isset($_POST['register'])){
  $username = htmlentities($_POST['username']);
  $password = htmlentities($_POST['password']);
  $role = htmlentities($_POST['role']);
  $username = mysqli_real_escape_string($conn, $username);
  $password = mysqli_real_escape_string($conn, $password);
  $role = mysqli_real_escape_string($conn, $role);

  $conn->query("INSERT INTO users VALUES(
    '',
    '$username',
    '$password',
    '$role'
  )");
  if($conn->affected_rows > 0){
    alert('Data berhasil ditambahkan!');
  }
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Simple Map</title>
	<meta name="viewport" content="initial-scale=1.0">
	<meta charset="utf-8">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
	<!--link rel="stylesheet" type="text/css" href="bootstrap.min.css" /-->
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body class="d-grid text-center">

<header>
  <h2 class="text-center mt-2">Aplikasi pengiriman barang</h2>
</header>

<main class="d-flex justify-content-center w-100 bg-info">
  <div class="d-flex justify-content-center align-items-center align-self-center w-25 p-5 shadow rounded" style="background: lightblue">
    <form class="w-100 my-3" action="" method="post">
      <div class="form-floating mb-2">
        <input type="text" class="form-control" id="username" name="username" placeholder="Nama" required />
        <label for="username">Nama</label>
      </div>
      <div class="form-floating mb-2">
        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required />
        <label for="password">Password</label>
      </div>
      <div>
        <label class="form-label">Daftar sebagai:</label>
        <select class="form-select" name="role">
          <option value="admin">Admin</option>
          <option value="kurir">Kurir</option>
        </select>
      </div>
      <button type="submit" name="register" class="btn btn-primary my-2">Daftar</button>
      <p>Sudah memiliki akun? <a href="login_page.php"><b>Login <i class="bi bi-box-arrow-in-right"></i></b></a></p>
    </form>
  </div>
</main>

<footer class="">
  <p>IAM &copy; 2022</p>
</footer>

</body>
</html>