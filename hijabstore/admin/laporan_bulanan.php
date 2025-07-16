<?php
session_start();
include '../koneksi.php';

// Batasi akses untuk admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses ditolak!'); window.location='../login.php';</script>";
    exit();
}

$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

// Query pesanan berdasarkan bulan dan tahun
$query = "
    SELECT o.id AS order_id, o.order_date, u.username, o.total
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE MONTH(o.order_date) = '$bulan' AND YEAR(o.order_date) = '$tahun'
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
    WHERE MONTH(o.order_date) = '$bulan' AND YEAR(o.order_date) = '$tahun'
");
while ($row = mysqli_fetch_assoc($item_result)) {
    $order_items[$row['order_id']][] = $row;
}

$bulan_nama = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
    '04' => 'April', '05' => 'Mei', '06' => 'Juni',
    '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
    '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Bulanan</title>
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
        select {
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
            margin-left: 10px;
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
    <h2>Laporan Penjualan Bulanan</h2>

    <form method="GET">
        <label for="bulan">Bulan:</label>
        <select name="bulan" required>
            <?php foreach ($bulan_nama as $key => $nama): ?>
                <option value="<?= $key ?>" <?= ($key == $bulan) ? 'selected' : '' ?>><?= $nama ?></option>
            <?php endforeach; ?>
        </select>
        <label for="tahun">Tahun:</label>
        <select name="tahun" required>
            <?php
            $current_year = date('Y');
            for ($y = $current_year; $y >= $current_year - 5; $y--) {
                $selected = ($y == $tahun) ? "selected" : "";
                echo "<option value='$y' $selected>$y</option>";
            }
            ?>
        </select>
        <button type="submit">Tampilkan</button>
        <button type="button" onclick="window.print()" class="btn-cetak">Cetak</button>
    </form>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
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
                    <td><?= date('d-m-Y', strtotime($row['order_date'])) ?></td>
                    <td><?= date('H:i:s', strtotime($row['order_date'])) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= $produk_list ?></td>
                    <td>Rp<?= number_format($row['total'], 0, ',', '.') ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <p class="total">Total Penjualan Bulan <?= $bulan_nama[$bulan] . ' ' . $tahun ?>: Rp<?= number_format($grand_total, 0, ',', '.') ?></p>
    <?php else: ?>
        <p>Tidak ada pesanan pada bulan ini.</p>
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
