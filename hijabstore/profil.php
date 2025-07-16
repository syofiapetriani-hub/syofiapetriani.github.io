<?php
session_start();

// Cek login
if (!isset($_SESSION['nama'])) {
    header("Location: login.php");
    exit;
}

$nama_pengguna = $_SESSION['nama'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Pengguna</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #ffeef3;
            margin: 0;
            padding: 0;
        }
        nav {
            text-align: center;
            background-color: #ffd6e8;
            padding: 15px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border-bottom: 2px solid #ffb6c1;
        }
        nav a {
            display: inline-block;
            margin: 0 15px;
            padding: 10px 20px;
            background-color: #ff99cc;
            color: white;
            border-radius: 20px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        nav a:hover {
            background-color: #ff66b2;
        }

        .container {
            max-width: 600px;
            margin: 60px auto;
            background: white;
            padding: 40px;
            border-radius: 25px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        .avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-bottom: 20px;
            background-color: #ffccdd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            color: white;
            margin-left: auto;
            margin-right: auto;
        }
        h2 {
            color: #d63384;
            margin-bottom: 10px;
        }
        p {
            font-size: 18px;
            color: #555;
        }
        .btn-logout {
            margin-top: 30px;
            background-color: #ff4da6;
            color: white;
            padding: 12px 25px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
        }
        .btn-logout:hover {
            background-color: #ff3399;
        }
    </style>
</head>
<body>

<nav>
    <a href="index.php">Home</a>
    <a href="about.php">Tentang Kami</a>
    <a href="add_produk.php">Daftar Produk</a>
    <a href="profil.php">Profil</a>
</nav>

<div class="container">
    <div class="avatar">💖</div>
    <h2>Hai, <?= htmlspecialchars($nama_pengguna) ?>!</h2>
    <p>Selamat datang di akun Anda. Terima kasih telah menjadi bagian dari HijabStore!</p>
    <a href="index.php?logout=true" class="btn-logout">Logout</a>
</div>

</body>
</html>
