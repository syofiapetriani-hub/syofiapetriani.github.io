<?php
include '../koneksi.php';

$nama = '';
$harga = '';
$error = '';
$sukses = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];

    // Validasi input
    if (empty($nama) || empty($harga)) {
        $error = "Nama dan harga wajib diisi!";
    } else {
        // Upload gambar jika ada
        $namaFile = $_FILES['gambar']['name'];
        $tmpName = $_FILES['gambar']['tmp_name'];

        if ($namaFile != '') {
            $targetDir = "../images/";
            $targetFile = $targetDir . basename($namaFile);

            if (move_uploaded_file($tmpName, $targetFile)) {
                // Simpan ke database
                $stmt = $koneksi->prepare("INSERT INTO produk (nama, harga, gambar) VALUES (?, ?, ?)");
                $stmt->bind_param("sis", $nama, $harga, $namaFile);

                if ($stmt->execute()) {
                    $sukses = "Produk berhasil ditambahkan!";
                    $nama = '';
                    $harga = '';
                } else {
                    $error = "Gagal menambahkan produk.";
                }
            } else {
                $error = "Gagal mengupload gambar.";
            }
        } else {
            $error = "Gambar produk wajib diupload.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk - Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffeef3;
            padding: 30px;
        }
        .container {
            background: white;
            max-width: 500px;
            margin: auto;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #d63384;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 20px;
            background-color: #ff4da6;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #ff3399;
        }
        .msg {
            margin-top: 15px;
            padding: 10px;
            border-radius: 8px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        a.back {
            display: block;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
            color: #ff3399;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Tambah Produk Baru</h2>

        <?php if ($error): ?>
            <div class="msg error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($sukses): ?>
            <div class="msg success"><?= htmlspecialchars($sukses) ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <label for="nama">Nama Produk</label>
            <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($nama) ?>" required>

            <label for="harga">Harga (Rp)</label>
            <input type="number" id="harga" name="harga" value="<?= htmlspecialchars($harga) ?>" required>

            <label for="gambar">Upload Gambar</label>
            <input type="file" name="gambar" id="gambar" required>

            <button type="submit">Tambah Produk</button>
        </form>

        <a class="back" href="dashboard.php">← Kembali ke Dashboard</a>
    </div>
</body>
</html>
