<?php
session_start();
include '../koneksi.php';

// Batasi hanya admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses ditolak!'); window.location='../login.php';</script>";
    exit();
}

$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

// Ambil total penjualan per bulan
$query = "
    SELECT MONTH(order_date) AS bulan, SUM(total) AS total_bulanan
    FROM orders
    WHERE YEAR(order_date) = '$tahun'
    GROUP BY MONTH(order_date)
    ORDER BY bulan
";
$result = mysqli_query($koneksi, $query);

// Buat array default 12 bulan
$bulan_array = [];
for ($i = 1; $i <= 12; $i++) {
    $bulan_array[$i] = 0;
}

$total_tahunan = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $bulan_array[$row['bulan']] = $row['total_bulanan'];
    $total_tahunan += $row['total_bulanan'];
}

// Fungsi untuk tanggal Indonesia
function formatTanggalIndonesia($tanggal) {
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $pecah = explode('-', $tanggal);
    return $pecah[2] . ' ' . $bulan[(int)$pecah[1]] . ' ' . $pecah[0];
}
$tanggalCetak = formatTanggalIndonesia(date('Y-m-d'));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Tahunan Penjualan</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fff0f6;
            padding: 20px;
        }
        .container {
            max-width: 850px;
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
        button {
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
            font-weight: bold;
            margin-top: 15px;
            text-align: right;
            color: #c2185b;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Laporan Penjualan Tahunan</h2>

    <form method="GET">
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
        <button type="button" onclick="window.print()">Cetak</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Total Penjualan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $bulan_nama = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            foreach ($bulan_array as $bln => $total):
            ?>
            <tr>
                <td><?= $bulan_nama[$bln] ?></td>
                <td>Rp<?= number_format($total, 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p class="total">Total Penjualan Tahun <?= $tahun ?>: <strong>Rp<?= number_format($total_tahunan, 0, ',', '.') ?></strong></p>

     <br><br>
<div style="text-align: right; margin-top: 40px;">
    <p style="margin: 0;">Padang, <?= date('d F Y') ?></p>
    <p style="margin: 0;">Pimpinan Toko</p>
    <p style="margin: 40px 0 0 0; text-decoration: underline; font-weight: normal;">Syofiah Petriani</p>
</div>



</div>

</body>
</html>
