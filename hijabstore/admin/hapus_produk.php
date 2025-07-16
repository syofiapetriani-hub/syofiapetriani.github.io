<?php
include '../koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil nama file gambar untuk dihapus dari folder images
    $query = $koneksi->prepare("SELECT gambar FROM produk WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $result = $query->get_result();
    $produk = $result->fetch_assoc();

    if ($produk) {
        // Hapus gambar dari folder jika ada
        $gambar_path = '../images/' . $produk['gambar'];
        if (file_exists($gambar_path)) {
            unlink($gambar_path);
        }

        // Hapus data produk dari database
        $hapus = $koneksi->prepare("DELETE FROM produk WHERE id = ?");
        $hapus->bind_param("i", $id);
        if ($hapus->execute()) {
            header("Location: dashboard.php?status=deleted");
            exit;
        } else {
            echo "Gagal menghapus produk.";
        }
    } else {
        echo "Produk tidak ditemukan.";
    }
} else {
    echo "ID tidak valid.";
}
?>
