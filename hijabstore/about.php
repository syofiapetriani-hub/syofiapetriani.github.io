<?php
session_start();
include 'koneksi.php';

// Ambil data pengguna jika sudah login
$nama_pengguna = '';
if (isset($_SESSION['nama'])) {
    $nama_pengguna = $_SESSION['nama'];
}

// Logout jika logout ditekan
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: about.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Tentang HijabStore</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffeef3;
            margin: 0; padding: 0;
            color: #4a4a4a;
        }
        header {
            background-color: #ff7eb9;
            padding: 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 {
            margin: 0;
            font-weight: bold;
        }
        nav a {
            color: white;
            margin-left: 15px;
            text-decoration: none;
            font-weight: bold;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .welcome {
            margin-right: 15px;
            font-weight: 600;
        }
        .container {
            max-width: 900px;
            margin: 30px auto;
            background: white;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }
        h2 {
            color: #d63384;
            margin-bottom: 15px;
        }
        h3 {
            color: #ff4da6;
            margin-top: 25px;
            margin-bottom: 10px;
        }
        p, ul {
            line-height: 1.6;
        }
        ul {
            padding-left: 20px;
        }
        footer {
            margin-top: 50px;
            text-align: center;
            color: #aaa;
            font-size: 14px;
            padding-bottom: 20px;
        }
        .auth-button {
            background-color: white;
            color: #ff3399;
            padding: 8px 16px;
            border-radius: 25px;
            border: none;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            margin-left: 10px;
        }
        .auth-button:hover {
            background-color: #ffe0ef;
        }
    </style>
</head>
<body>

<header>
    <h1>HijabStore</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="about.php">Tentang Kami</a>
        <a href="add_produk.php">Daftar Produk</a>
        <a href="#">Profil</a>
        <?php if ($nama_pengguna): ?>
            <span class="welcome">Hai, <?= htmlspecialchars($nama_pengguna); ?>!</span>
            <a href="about.php?logout=true" class="auth-button">Logout</a>
        <?php else: ?>
            <a href="login.php" class="auth-button">Login</a>
            <a href="register.php" class="auth-button">Daftar</a>
        <?php endif; ?>
    </nav>
</header>

<div class="container">
    <h2>🧕 Tentang HijabStore</h2>

    <p><strong>HijabStore</strong> adalah toko online yang menyediakan berbagai pilihan hijab modern, stylish, dan berkualitas untuk wanita muslimah Indonesia. Berdiri dengan semangat untuk mendukung perempuan tampil anggun dan percaya diri, kami menghadirkan hijab dengan berbagai warna, bahan, dan model terkini.</p>

    <h3>🎯 Visi</h3>
    <p>Menjadi pusat belanja hijab online terpercaya di Indonesia yang menginspirasi gaya berhijab yang elegan dan syar’i.</p>

    <h3>🧭 Misi</h3>
    <ul>
        <li>Menyediakan produk hijab berkualitas dengan harga terjangkau.</li>
        <li>Menghadirkan tren hijab terbaru untuk segala usia dan aktivitas.</li>
        <li>Memberikan pelayanan ramah dan cepat bagi seluruh pelanggan.</li>
        <li>Mendorong semangat muslimah Indonesia untuk tampil cantik dan percaya diri.</li>
    </ul>

    <h3>👗 Produk Kami</h3>
    <ul>
        <li>Hijab Segi Empat</li>
        <li>Pashmina</li>
        <li>Hijab Instan</li>
        <li>Hijab Syar’i</li>
        <li>Inner & Ciput</li>
        <li>Aksesoris Hijab</li>
    </ul>

    <h3>🤝 Mengapa Belanja di HijabStore?</h3>
    <ul>
        <li>Produk up to date sesuai tren</li>
        <li>Layanan pelanggan responsif</li>
        <li>Pengiriman cepat dan aman</li>
        <li>Sistem belanja mudah & aman</li>
    </ul>
</div>

<footer>
    &copy; <?= date("Y"); ?> HijabStore. All rights reserved.
</footer>

</body>
</html>
