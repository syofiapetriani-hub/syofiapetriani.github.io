<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'checkout.php';
    header("Location: login.php");
    exit();
}

$cart_items = [];
$total_harga = 0;

// Ambil data produk dari keranjang
if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $ids_safe = implode(',', array_map('intval', $ids));
    $query = "SELECT * FROM produk WHERE id IN ($ids_safe)";
    $result = mysqli_query($koneksi, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $row['qty'] = $_SESSION['cart'][$row['id']];
        $row['subtotal'] = $row['qty'] * $row['harga'];
        $total_harga += $row['subtotal'];
        $cart_items[] = $row;
    }
}

$checkout_success = false;
$data_pembeli = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simpan data form
    $data_pembeli['nama'] = $_POST['nama'];
    $data_pembeli['alamat'] = $_POST['alamat'];
    $data_pembeli['telepon'] = $_POST['telepon'];
    $data_pembeli['catatan'] = $_POST['catatan'];
    $data_pembeli['metode'] = $_POST['metode'];

    // Cek stok terlebih dahulu
    $stok_kurang = [];
    foreach ($cart_items as $item) {
        $produk_id = $item['id'];
        $qty = $item['qty'];

        $cek = mysqli_query($koneksi, "SELECT stok FROM produk WHERE id = $produk_id");
        $data = mysqli_fetch_assoc($cek);
        if ($data['stok'] < $qty) {
            $stok_kurang[] = $item['nama'];
        }
    }

    if (!empty($stok_kurang)) {
        echo "<div class='container'><div class='summary'><h3>Checkout Gagal!</h3>";
        echo "<p>Stok tidak mencukupi untuk produk berikut:</p><ul>";
        foreach ($stok_kurang as $nama_produk) {
            echo "<li>" . htmlspecialchars($nama_produk) . "</li>";
        }
        echo "</ul><a href='cart.php' class='btn' style='margin-top:10px;'>Kembali ke Keranjang</a></div></div>";
    } else {
        // Semua stok cukup, lanjutkan checkout
        $user_id = $_SESSION['user_id'];
        $total = $total_harga;

        $stmt = mysqli_prepare($koneksi, "INSERT INTO orders (user_id, order_date, total) VALUES (?, NOW(), ?)");
        mysqli_stmt_bind_param($stmt, 'id', $user_id, $total);
        mysqli_stmt_execute($stmt);
        $order_id = mysqli_insert_id($koneksi);

        // Simpan ke order_items dan kurangi stok
        $stmt_item = mysqli_prepare($koneksi, "INSERT INTO order_items (order_id, produk_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($cart_items as $item) {
            $produk_id = $item['id'];
            $qty = $item['qty'];
            $harga = $item['harga'];

            mysqli_stmt_bind_param($stmt_item, 'iiid', $order_id, $produk_id, $qty, $harga);
            mysqli_stmt_execute($stmt_item);

            // Kurangi stok di tabel produk
            $update_stok = mysqli_prepare($koneksi, "UPDATE produk SET stok = stok - ? WHERE id = ?");
            mysqli_stmt_bind_param($update_stok, 'ii', $qty, $produk_id);
            mysqli_stmt_execute($update_stok);
        }

        // Kosongkan keranjang
        $_SESSION['cart'] = [];
        $checkout_success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout - HijabStore</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #ffeaf4, #ffdee9);
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 700px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(255, 105, 180, 0.2);
        }
        h2 {
            text-align: center;
            color: #c2185b;
        }
        label {
            font-weight: bold;
            color: #ad1457;
        }
        input, textarea, select {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 1em;
        }
        .btn {
            background-color: #ff69b4;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 30px;
            font-weight: bold;
            font-size: 1em;
            cursor: pointer;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #e91e63;
        }
        .summary {
            background-color: #fff5f9;
            padding: 20px;
            border-radius: 15px;
            margin-top: 30px;
        }
        .produk-item {
            margin-bottom: 8px;
        }
        .total {
            font-size: 1.2em;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
            color: #d81b60;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Checkout HijabStore</h2>

    <?php if ($checkout_success): ?>
        <div class="summary">
            <h3>Terima kasih, <?= htmlspecialchars($data_pembeli['nama']) ?>!</h3>
            <p>Pesanan Anda berhasil dibuat dan akan dikirim ke alamat berikut:</p>
            <p><strong>Alamat:</strong> <?= htmlspecialchars($data_pembeli['alamat']) ?></p>
            <p><strong>Telepon:</strong> <?= htmlspecialchars($data_pembeli['telepon']) ?></p>
            <p><strong>Catatan:</strong> <?= nl2br(htmlspecialchars($data_pembeli['catatan'])) ?></p>
            <p><strong>Metode Pembayaran:</strong> <?= htmlspecialchars($data_pembeli['metode']) ?></p>

            <h4>Detail Produk:</h4>
            <?php foreach ($cart_items as $item): ?>
                <div class="produk-item">
                    <?= htmlspecialchars($item['nama']) ?> x<?= $item['qty'] ?> - Rp<?= number_format($item['subtotal'], 0, ',', '.') ?>
                </div>
            <?php endforeach; ?>
            <div class="total">Total: Rp<?= number_format($total_harga, 0, ',', '.') ?></div>

            <a href="index.php" class="btn" style="margin-top: 20px;">Kembali ke Beranda</a>
        </div>
    <?php elseif (!empty($cart_items) && !$checkout_success): ?>
        <form method="POST">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" required>

            <label>Alamat Lengkap</label>
            <textarea name="alamat" required></textarea>

            <label>No. Telepon</label>
            <input type="text" name="telepon" required>

            <label>Catatan (opsional)</label>
            <textarea name="catatan"></textarea>

            <label>Metode Pembayaran</label>
            <select name="metode" required>
                <option value="Transfer Bank">Transfer Bank</option>
                <option value="QRIS">QRIS</option>
                <option value="COD (Bayar di Tempat)">COD (Bayar di Tempat)</option>
            </select>

            <button type="submit" class="btn">Konfirmasi Checkout</button>
        </form>
    <?php else: ?>
        <p>Keranjang belanja Anda kosong.</p>
        <a href="index.php" class="btn">Kembali Belanja</a>
    <?php endif; ?>
</div>

</body>
</html>
