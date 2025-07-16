<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Produk Hijab</title>
    <style>
        body {
            background-color: #ffe6f0;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #d63384;
            margin-bottom: 30px;
        }
        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 16px;
            border-bottom: 1px solid #f1c5d6;
            text-align: center;
        }
        th {
            background-color: #ff99cc;
            color: white;
        }
        tr:hover {
            background-color: #fff0f5;
        }
        img {
            width: 100px;
            height: auto;
            border-radius: 8px;
        }
        a.kembali {
            display: block;
            margin: 30px auto 0;
            text-align: center;
            width: fit-content;
            padding: 10px 20px;
            background-color: #ff4da6;
            color: white;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
        }
        a.kembali:hover {
            background-color: #ff3399;
        }
    </style>
</head>
<body>

    <h2>Daftar Produk Hijab</h2>

    <table>
        <tr>
            <th>No</th>
            <th>Gambar</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Stok</th>
        </tr>
        <?php
        $result = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY id DESC");
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $no++ . '</td>';
            echo '<td><img src="images/' . htmlspecialchars($row["gambar"]) . '" alt="' . htmlspecialchars($row["nama"]) . '"></td>';
            echo '<td>' . htmlspecialchars($row["nama"]) . '</td>';
            echo '<td>Rp' . number_format($row["harga"], 0, ',', '.') . '</td>';
            echo '<td>' . htmlspecialchars($row["stok"]) . '</td>';
            echo '</tr>';
        }
        ?>
    </table>

    <a href="index.php" class="kembali">← Kembali ke Beranda</a>

</body>
</html>
