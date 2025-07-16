<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses ditolak!'); window.location='../login.php';</script>";
    exit;
}

// ✅ Tambah Produk
if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $folder = '../images/' . $gambar;

    if (move_uploaded_file($tmp, $folder)) {
        mysqli_query($koneksi, "INSERT INTO produk (nama, harga, gambar, stok) VALUES ('$nama', '$harga', '$gambar', '$stok')")
            or die(mysqli_error($koneksi));
        echo "<script>alert('Produk berhasil ditambahkan'); window.location='kelola_produk.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal upload gambar');</script>";
    }
}

// ✅ Tambah stok
if (isset($_GET['tambah_stok'])) {
    $id = intval($_GET['tambah_stok']);
    mysqli_query($koneksi, "UPDATE produk SET stok = stok + 1 WHERE id = $id");
    echo "<script>window.location='kelola_produk.php';</script>";
    exit;
}

$produk = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Produk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #fff0f5;
            font-family: 'Poppins', sans-serif;
            padding: 30px;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(216, 27, 96, 0.2);
        }
        h2 {
            color: #d81b60;
            margin-bottom: 20px;
        }
        .form-tambah {
            background-color: #fce4ec;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        .btn-pink {
            background-color: #f48fb1;
            border: none;
            color: white;
            font-weight: bold;
        }
        .btn-pink:hover {
            background-color: #ec407a;
        }
        .btn-edit, .btn-stok {
            background-color: #f8bbd0;
            border: none;
            color: white;
            padding: 6px 12px;
            border-radius: 10px;
            font-weight: 500;
            margin-right: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-edit:hover, .btn-stok:hover {
            background-color: #f48fb1;
        }
        .btn-hapus {
            background-color: #f48fb1;
            border: none;
            color: white;
            padding: 6px 12px;
            border-radius: 10px;
            font-weight: 500;
            cursor: pointer;
        }
        .btn-hapus:hover {
            background-color: #ec407a;
        }
        table {
            margin-top: 20px;
        }
        td img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manajemen Produk</h2>

    <!-- ✅ Form Tambah Produk -->
    <div class="form-tambah">
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <input type="text" name="nama" class="form-control" placeholder="Nama Produk" required>
                </div>
                <div class="col-md-2 mb-2">
                    <input type="number" name="harga" class="form-control" placeholder="Harga" required>
                </div>
                <div class="col-md-2 mb-2">
                    <input type="number" name="stok" class="form-control" placeholder="Stok" required>
                </div>
                <div class="col-md-3 mb-2">
                    <input type="file" name="gambar" class="form-control" required>
                </div>
                <div class="col-md-2 mb-2">
                    <button type="submit" name="submit" class="btn btn-pink w-100">+ Tambah</button>
                </div>
            </div>
        </form>
    </div>

    <!-- ✅ Tabel Produk -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($produk)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td>Rp <?= number_format($row['harga']) ?></td>
                <td>
                    <?= $row['stok'] ?>
                    <a href="?tambah_stok=<?= $row['id'] ?>" class="btn-stok btn-sm">+</a>
                </td>
                <td><img src="../images/<?= htmlspecialchars($row['gambar']) ?>" alt="Gambar Produk"></td>
                <td>
                    <a href="edit_produk.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                    <a href="hapus_produk.php?id=<?= $row['id'] ?>" class="btn-hapus" onclick="return confirm('Yakin hapus produk ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile ?>
        </tbody>
    </table>
</div>

</body>
</html>
