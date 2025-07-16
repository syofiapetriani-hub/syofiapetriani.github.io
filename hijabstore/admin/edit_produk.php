<?php
include '../koneksi.php';
$id = $_GET['id'] ?? 0;

// Ambil data produk dari database
$query = "SELECT * FROM produk WHERE id = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$produk = $result->fetch_assoc();

if (!$produk) {
    echo "Produk tidak ditemukan.";
    exit;
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'] ?? '';
    $harga = $_POST['harga'] ?? 0;
    $gambar_baru = $_FILES['gambar']['name'];

    // Jika ada gambar baru diupload
    if (!empty($gambar_baru)) {
        $gambar_tmp = $_FILES['gambar']['tmp_name'];
        move_uploaded_file($gambar_tmp, '../images/' . $gambar_baru);
    } else {
        $gambar_baru = $produk['gambar']; // pakai gambar lama
    }

    // Update produk
    $update = $koneksi->prepare("UPDATE produk SET nama = ?, harga = ?, gambar = ? WHERE id = ?");
    $update->bind_param("sisi", $nama, $harga, $gambar_baru, $id);
    if ($update->execute()) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Gagal mengupdate produk.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk - HijabStore Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fff0f6;
            padding: 40px;
        }
        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(255,0,123,0.1);
        }
        h2 {
            text-align: center;
            color: #d63384;
        }
        label {
            font-weight: bold;
            margin-top: 15px;
            display: block;
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
        img {
            max-width: 100%;
            margin-top: 10px;
        }
        button {
            background: #ff4da6;
            color: white;
            border: none;
            padding: 10px;
            margin-top: 20px;
            width: 100%;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #e60073;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Produk</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Nama Produk</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($produk['nama'] ?? '') ?>" required>

            <label>Harga (Rp)</label>
            <input type="number" name="harga" value="<?= htmlspecialchars($produk['harga'] ?? '') ?>" required>

            <label>Ganti Gambar Produk</label>
            <input type="file" name="gambar">

            <p>Gambar Saat Ini:</p>
            <img src="../images/<?= htmlspecialchars($produk['gambar']) ?>" alt="Gambar Produk">

            <button type="submit">Update Produk</button>
        </form>
    </div>
</body>
</html>
