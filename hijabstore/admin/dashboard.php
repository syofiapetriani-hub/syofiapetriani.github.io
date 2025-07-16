<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - HijabStore</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background: #fff0f5;
        }

        nav {
            background: #ffe0ec;
            display: flex;
            align-items: center;
            padding: 10px 30px;
            box-shadow: 0 4px 10px rgba(255, 182, 193, 0.3);
        }

        nav a, .dropdown-btn {
            color: #b3005f;
            text-decoration: none;
            margin-right: 25px;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 20px;
            transition: background 0.3s ease;
            cursor: pointer;
        }

        nav a.active, nav a:hover, .dropdown-btn:hover {
            background: #ff69b4;
            color: white;
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease;
            display: flex;
            flex-direction: column;
            margin-left: 30px;
        }

        .submenu.show {
            max-height: 300px;
        }

        .submenu a {
            color: #b3005f;
            margin: 5px 0;
            padding: 6px 12px;
            border-radius: 15px;
            background-color: #ffeaf3;
            width: fit-content;
            transition: background 0.3s ease;
        }

        .submenu a:hover {
            background-color: #ffcce0;
        }

        .container {
            padding: 40px 30px;
        }

        h1 {
            color: #d63384;
        }

        .icon {
            margin-right: 6px;
        }

        .stats-box {
            margin-top: 30px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .card {
            background-color: #ffeaf3;
            border: 2px solid #ffcce0;
            border-radius: 20px;
            padding: 20px 30px;
            color: #b3005f;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(255, 192, 203, 0.3);
            min-width: 220px;
        }

        .card h2 {
            margin: 0 0 10px;
            font-size: 18px;
        }

        .card .value {
            font-size: 26px;
            color: #ff69b4;
        }

        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                align-items: flex-start;
            }

            .stats-box {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<nav>
    <a href="dashboard.php" class="active"><span class="icon">🏠</span>Dashboard</a>
    <a href="kelola_produk.php"><span class="icon">🛍️</span>Produk</a>
    <span class="dropdown-btn" onclick="toggleSubmenu()"><span class="icon">📊</span>Laporan</span>
    <div class="submenu" id="submenuLaporan">
        <a href="laporan_harian.php">📅 Laporan Harian</a>
        <a href="laporan_bulanan.php">📆 Laporan Bulanan</a>
        <a href="laporan_tahunan.php">🗓️ Laporan Tahunan</a>
        <a href="stok.php">📦 Stok</a>
    </div>
    <a href="kelola_pengguna.php"><span class="icon">👤</span>Kelola Pengguna</a>
    <a href="logout.php"><span class="icon">🚪</span>Logout</a>
</nav>

<div class="container">
    <h1>Selamat Datang di Dashboard Admin HijabStore 💖</h1>
    <p>Gunakan menu di atas untuk mengelola produk, laporan penjualan, dan pengguna.</p>

    <div class="stats-box">
        <div class="card">
            <h2>👁️ Pengunjung Hari Ini</h2>
            <div class="value">
                <?php
                    // Contoh: jika kamu sudah menyimpan view di database atau session
                    echo rand(50, 150); // Ganti dengan query jumlah pengunjung sebenarnya
                ?>
            </div>
        </div>

        <div class="card">
            <h2>🛍️ Produk Dilihat</h2>
            <div class="value">
                <?php echo rand(120, 400); ?>
            </div>
        </div>
    </div>
</div>

<script>
function toggleSubmenu() {
    const submenu = document.getElementById("submenuLaporan");
    submenu.classList.toggle("show");
}
</script>

</body>
</html>
