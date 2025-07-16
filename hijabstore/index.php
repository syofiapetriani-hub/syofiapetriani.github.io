<?php
session_start();
include 'koneksi.php';

// Inisialisasi keranjang jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Tambah produk ke keranjang dari tombol di produk
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $id = $_POST['id'];
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]++;
        } else {
            $_SESSION['cart'][$id] = 1;
        }
        header("Location: index.php");
        exit;
    } elseif (isset($_POST['buy_now'])) {
    $id = $_POST['id'];
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }
    header("Location: cart.php");
    exit;
}

}

// Hitung total item di keranjang untuk header
$total_items = 0;
foreach ($_SESSION['cart'] as $qty) {
    $total_items += $qty;
}

// Ambil data pengguna jika sudah login
$nama_pengguna = '';
$welcome_msg = '';

if (isset($_SESSION['nama'])) {
    $nama_pengguna = $_SESSION['nama'];
}
if (isset($_SESSION['welcome_msg'])) {
    $welcome_msg = $_SESSION['welcome_msg'];
    unset($_SESSION['welcome_msg']);
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Ambil keyword pencarian jika ada
$keyword = isset($_GET['search']) ? $_GET['search'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>HijabStore</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffeef3;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #ff7eb9;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        header h1 {
            color: white;
            margin: 0;
        }
        .search-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            max-width: 500px;
            margin: 0 15px;
            position: relative;
        }
        .search-bar input[type="text"] {
            padding: 8px 12px;
            border-radius: 20px;
            border: none;
            width: 100%;
        }
        .search-bar button {
            background-color: #ff4da6;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            font-weight: bold;
        }
        .search-bar button:hover {
            background-color: #ff3399;
        }
        .cart-icon {
            position: relative;
            cursor: pointer;
            color: white;
            margin-right: 10px;
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        .cart-icon svg {
            width: 28px;
            height: 28px;
            fill: white;
        }
        .cart-count {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #ff2672;
            color: white;
            font-size: 12px;
            font-weight: bold;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        nav {
            text-align: center;
            margin-top: 15px;
        }
        nav a {
            margin: 0 15px;
            color: #d63384;
            text-decoration: none;
            font-weight: bold;
        }
        .produk-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 30px;
            gap: 25px;
        }
        .produk {
            background-color: #fff;
            border-radius: 15px;
            width: 230px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 15px;
            text-align: center;
            position: relative;
        }
        .produk img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 12px;
        }
        .produk h3 {
            margin: 10px 0 5px;
        }
        .produk p {
            color: #ff3399;
            font-weight: bold;
        }
        form.produk-form {
            margin-top: 10px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        form.produk-form button {
            background-color: #ff4da6;
            border: none;
            border-radius: 20px;
            padding: 8px 0;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        form.produk-form button:hover {
            background-color: #ff2672;
        }
        form.produk-form button.buy-now {
            background-color: #ff85b8;
        }
        form.produk-form button.buy-now:hover {
            background-color: #ff5a93;
        }
        .auth-button {
            background-color: white;
            color: #ff3399;
            padding: 10px 20px;
            border-radius: 25px;
            margin-left: 10px;
            border: none;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
        }
        .auth-button:hover {
            background-color: #ffe0ef;
        }
        .welcome-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>

<header>
    <h1>HijabStore</h1>
    
    <a href="cart.php" class="cart-icon" title="Lihat Keranjang">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2S15.9 22 17 22s2-.9 2-2-.9-2-2-2zM7.16 14.26l.03-.12L8.1 14h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49-1.74-1-.04-.03H6.21l-.94-2H1v2h3l3.6 7.59-1.35 2.44c-.14.25-.21.54-.21.86 0 1.11.89 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25z"/></svg>
        <?php if ($total_items > 0): ?>
            <div class="cart-count"><?= $total_items ?></div>
        <?php endif; ?>
    </a>

    <form class="search-bar" method="GET" action="index.php">
        <input type="text" name="search" placeholder="Cari hijab favoritmu..." value="<?= htmlspecialchars($keyword) ?>">
        <button type="submit">Search</button>
    </form>

    <div>
        <?php if ($nama_pengguna): ?>
            <span style="color: white; margin-right: 10px;">Hai, <?= htmlspecialchars($nama_pengguna); ?>!</span>
            <a href="index.php?logout=true" class="auth-button">Logout</a>
        <?php else: ?>
            <a href="login.php" class="auth-button">Login</a>
            <a href="register.php" class="auth-button">Daftar</a>
        <?php endif; ?>
    </div>
</header>

<?php if (!empty($welcome_msg)): ?>
    <div class="welcome-message">
        <?= htmlspecialchars($welcome_msg); ?>
    </div>
<?php endif; ?>

<nav>
    <a href="index.php">Home</a>
    <a href="about.php">Tentang kami</a>
    <a href="add_produk.php">Daftar produk</a>
    <a href="profil.php">Profil</a>
</nav>

<div class="produk-container">
    <?php
    $query = "SELECT * FROM produk";
    if (!empty($keyword)) {
        $keyword_safe = mysqli_real_escape_string($koneksi, $keyword);
        $query .= " WHERE nama LIKE '%$keyword_safe%'";
    }

    $result = mysqli_query($koneksi, $query);
    if (mysqli_num_rows($result) > 0) {
       while ($row = mysqli_fetch_assoc($result)) {
    $nama = htmlspecialchars($row['nama'] ?? '');
    $gambar = htmlspecialchars($row['gambar'] ?? '');
    $harga = $row['harga'] ?? 0;
    $id = $row['id'];

    echo '
    <div class="produk">
        <img src="images/' . $gambar . '" alt="' . $nama . '">
        <h3>' . $nama . '</h3>
        <p>Rp' . number_format($harga, 0, ',', '.') . '</p>
        <form method="POST" action="index.php" class="produk-form">
            <input type="hidden" name="id" value="' . $id . '">
            <button type="submit" name="add_to_cart">Tambah ke Keranjang</button>
            <button type="submit" name="buy_now" class="buy-now">Beli Sekarang</button>
        </form>
    </div>';
}

    } else {
        echo '<p style="text-align:center; color:#888;">Produk tidak ditemukan.</p>';
    }
    ?>
</div>

</body>
</html>
