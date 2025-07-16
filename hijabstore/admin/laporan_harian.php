<?php
session_start();
include '../koneksi.php';

// Batasi akses untuk admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses ditolak!'); window.location='../login.php';</script>";
    exit();
}

// Ambil tanggal dari input atau default hari ini
$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');

// Query pesanan berdasarkan tanggal
$query = "
    SELECT o.id AS order_id, o.order_date, u.username, o.total
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE DATE(o.order_date) = '$tanggal'
    ORDER BY o.order_date DESC
";
$result = mysqli_query($koneksi, $query);

// Ambil item pesanan
$order_items = [];
$item_result = mysqli_query($koneksi, "
    SELECT oi.*, p.nama AS produk_nama
    FROM order_items oi
    JOIN produk p ON oi.produk_id = p.id
    JOIN orders o ON oi.order_id = o.id
    WHERE DATE(o.order_date) = '$tanggal'
");
while ($row = mysqli_fetch_assoc($item_result)) {
    $order_items[$row['order_id']][] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Harian</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fff0f6;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(255, 105, 180, 0.2);
        }
        h2 {
            text-align: center;
            color: #d81b60;
        }
        form {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="date"] {
            padding: 8px;
            font-size: 1rem;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        button, .btn-cetak {
            background: #ff69b4;
            color: white;
            padding: 10px 20px;
            border: none;
            font-weight: bold;
            border-radius: 10px;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ffcce0;
            padding: 10px;
        }
        th {
            background-color: #ffe6f0;
            color: #880e4f;
        }
        .total {
            margin-top: 15px;
            font-weight: bold;
            text-align: right;
            color: #c2185b;
        }
        .btn-cetak {
            float: right;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Laporan Harian Penjualan</h2>

    <form method="GET">
        <label for="tanggal">Pilih Tanggal:</label>
        <input type="date" name="tanggal" value="<?= $tanggal ?>">
        <button type="submit">Tampilkan</button>
        <button type="button" onclick="window.print()" class="btn-cetak">Cetak</button>
    </form>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jam</th>
                    <th>Pelanggan</th>
                    <th>Produk</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1; 
                $grand_total = 0;
                while ($row = mysqli_fetch_assoc($result)):
                    $produk_list = '';
                    if (isset($order_items[$row['order_id']])) {
                        foreach ($order_items[$row['order_id']] as $item) {
                            $produk_list .= $item['produk_nama'] . ' x' . $item['quantity'] . '<br>';
                        }
                    }
                    $grand_total += $row['total'];
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= date('H:i:s', strtotime($row['order_date'])) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= $produk_list ?></td>
                    <td>Rp<?= number_format($row['total'], 0, ',', '.') ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <p class="total">Total Penjualan: Rp<?= number_format($grand_total, 0, ',', '.') ?></p>
    <?php else: ?>
        <p>Tidak ada pesanan pada tanggal ini.</p>
    <?php endif; ?>

    <br><br>
    <div style="text-align: right; margin-top: 40px;">
        <p style="margin: 0;">Padang, <?= date('d F Y') ?></p>
        <p style="margin: 0;">Pimpinan Toko</p>
        <p style="margin: 40px 0 0 0; text-decoration: underline; font-weight: normal;">Syofiah Petriani</p>
    </div>
</div>

</body>
</html>
