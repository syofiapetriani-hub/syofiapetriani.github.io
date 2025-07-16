<?php
session_start();
include '../koneksi.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses ditolak!'); window.location='login.php';</script>";
    exit;
}

$users = mysqli_query($koneksi, "SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pengguna - HijabStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ffeef3, #ffd6e8);
            font-family: 'Poppins', sans-serif;
        }
        .container {
            margin-top: 60px;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(255, 77, 166, 0.2);
        }
        h2 {
            color: #d63384;
            text-align: center;
            margin-bottom: 30px;
        }
        .table thead {
            background-color: #ffb6d9;
            color: white;
        }
        .table tbody tr:hover {
            background-color: #fff0f6;
        }
        .btn-print {
            background-color: #ff69b4;
            border: none;
            color: white;
            padding: 10px 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .btn-print:hover {
            background-color: #ff4da6;
        }
        @media print {
            .btn-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Daftar Pengguna HijabStore</h2>
    <button class="btn btn-print" onclick="window.print()">Cetak</button>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Nama Lengkap</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            <?php while($user = mysqli_fetch_assoc($users)): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['nama']) ?></td>
                    <td><?= ucfirst($user['role']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
