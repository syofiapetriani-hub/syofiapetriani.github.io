<?php
include '../koneksi.php';
$produk = mysqli_query($koneksi, "SELECT * FROM produk");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Stok Produk - HijabStore</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins&display=swap">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #fff0f5;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 25px;
            box-shadow: 0 8px 25px rgba(255, 77, 166, 0.2);
        }

        h2 {
            text-align: center;
            color: #b3005f;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 12px;
            overflow: hidden;
        }

        thead {
            background-color: #ffcce0;
            color: #7a0033;
        }

        th, td {
            padding: 12px 18px;
            border-bottom: 1px solid #f3c1d9;
            text-align: center;
        }

        img {
            width: 60px;
            height: auto;
            border-radius: 8px;
        }

        tbody tr:hover {
            background-color: #fff6fa;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Daftar Stok Produk Hijab</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Gambar</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($produk)): ?>
            <tr>
                <td><?= $no++ ?></td>
               <td>
    <img src="uploads/<?= $row['gambar'] ?>" alt="<?= $row['nama'] ?>" width="60">
               <td><?= htmlspecialchars($row['nama']) ?></td>
                <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                <td><?= $row['stok'] ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
