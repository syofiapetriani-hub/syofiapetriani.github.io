<?php
session_start();

// Koneksi database (boleh pakai include koneksi.php jika sudah benar)
$host = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "hijabstore";

$koneksi = new mysqli($host, $username_db, $password_db, $dbname);
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Username dan password harus diisi.";
    } else {
        $stmt = $koneksi->prepare("SELECT id, password, nama, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $hash_password, $nama, $role);
            $stmt->fetch();

            if (password_verify($password, $hash_password)) {
                // Login sukses, set session
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['nama'] = $nama;
                $_SESSION['role'] = $role;

                if ($role == 'admin') {
                    $_SESSION['welcome_msg'] = "Selamat datang, Admin $nama!";
                    header("Location: admin/dashboard.php");
                    exit;
                } else {
                    $_SESSION['welcome_msg'] = "Selamat datang, $nama!";
                    header("Location: index.php");
                    exit;
                }
            } else {
                $error = "Password salah.";
            }
        } else {
            $error = "Username tidak ditemukan.";
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
    <title>Login</title>
    <style>
         /* Style sama persis seperti yang kamu berikan */
         * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #ffe6f0, #ffd6eb);
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            background-color: white;
            width: 100%;
            max-width: 400px;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            animation: slideIn 0.6s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #d6336c;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 25px;
            background: #ff66a3;
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #e05592;
        }

        .error, .success {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }

        .error {
            background-color: #ffe5e5;
            color: #a30000;
        }

        .success {
            background-color: #ddffdd;
            color: #0a6000;
        }

        p {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        a {
            color: #d6336c;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Login</h2>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Username:</label>
        <input type="text" name="username" required />

        <label>Password:</label>
        <input type="password" name="password" required />

        <button type="submit">Login</button>
    </form>

    <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
</div>

</body>
</html>
