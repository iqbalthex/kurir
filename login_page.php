<?php
require_once 'config.php';

if(isset($_SESSION['user_id'])){
  header('Location: index.php');
}

if(isset($_POST['login'])){
  $username = htmlentities($_POST['username']);
  $password = htmlentities($_POST['password']);
  $username = mysqli_real_escape_string($conn, $username);
  $password = mysqli_real_escape_string($conn, $password);

  $result = $conn->query("SELECT * FROM users WHERE username = '$username'");
  if($result->num_rows === 0):
    alert('Username tidak ditemukan!');

  else:
    $user = $result->fetch_assoc();
    if($user['password'] !== $password){
      alert('Password salah!');
    } else {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['role'] = $user['role'];
      header('Location: index.php');
      exit;
    }
  endif;
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Simple Map</title>
	<meta name="viewport" content="initial-scale=1.0">
	<meta charset="utf-8">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
	<!-- <link rel="stylesheet" type="text/css" href="bootstrap.min.css" /> -->
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
      <div class="form-floating">
        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required />
        <label for="password">Password</label>
      </div>
      <button type="submit" name="login" class="btn btn-primary my-2">Masuk</button>
      <p>Belum memiliki akun? <a href="register_page.php"><b>Daftar <i class="bi bi-person-plus"></i></b></a></p>
    </form>
  </div>
</main>

<footer class="">
  <p>IAM &copy; 2022</p>
</footer>

</body>
</html>