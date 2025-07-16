<?php
session_start();

$host = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "hijabstore";

$koneksi = new mysqli($host, $username_db, $password_db, $dbname);
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $nama = trim($_POST['nama']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if (empty($username) || empty($nama) || empty($password)) {
        $errors[] = "Semua field harus diisi.";
    }

    if ($password !== $password_confirm) {
        $errors[] = "Password dan konfirmasi password tidak sama.";
    }

    // Cek username sudah ada atau belum
    $stmt = $koneksi->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $errors[] = "Username sudah digunakan.";
    }
    $stmt->close();

    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $koneksi->prepare("INSERT INTO users (username, password, nama, role) VALUES (?, ?, ?, 'user')");
        $stmt->bind_param("sss", $username, $password_hash, $nama);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
            header("Location: login.php");
            exit;
        } else {
            $errors[] = "Terjadi kesalahan saat menyimpan data.";
        }
        $stmt->close();
    }
}
$koneksi->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Register User</title>
    <style>
       * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #ffe6f0, #ffd6eb);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .register-container {
            background: white;
            max-width: 450px;
            width: 100%;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h2 {
            text-align: center;
            color: #d6336c;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin: 12px 0 5px;
            font-weight: bold;
            color: #444;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
        }

        button {
            margin-top: 25px;
            width: 100%;
            padding: 12px;
            background-color: #ff66a3;
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #e05592;
        }

        .message {
            text-align: center;
            font-size: 14px;
            margin-top: 20px;
        }

        .message a {
            color: #d6336c;
            text-decoration: none;
        }

        .message a:hover {
            text-decoration: underline;
        }

        .error, .success {
            margin-top: 10px;
            padding: 10px;
            border-radius: 8px;
            font-size: 14px;
        }

        .error {
            background: #ffe5e5;
            color: #a30000;
        }

        .success {
            background: #ddffdd;
            color: #0a6000;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Daftar User Baru</h2>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $e) {
                echo htmlspecialchars($e) . "<br>";
            } ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Nama Lengkap:</label>
        <input type="text" name="nama" required />

        <label>Username:</label>
        <input type="text" name="username" required />

        <label>Password:</label>
        <input type="password" name="password" required />

        <label>Konfirmasi Password:</label>
        <input type="password" name="password_confirm" required />

        <button type="submit">Daftar</button>
    </form>
    <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
</div>

</body>
</html>
