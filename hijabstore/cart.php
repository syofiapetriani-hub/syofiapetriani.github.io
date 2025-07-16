<?php
session_start();
include 'koneksi.php';

// Inisialisasi keranjang jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Tambah produk ke keranjang
if (isset($_POST['add'])) {
    $id = $_POST['id'];
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }
    header('Location: cart.php');
    exit;
}

// Kurangi produk dari keranjang
if (isset($_POST['reduce'])) {
    $id = $_POST['id'];
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]--;
        if ($_SESSION['cart'][$id] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
    }
    header('Location: cart.php');
    exit;
}

// Hapus produk dari keranjang
if (isset($_POST['remove'])) {
    $id = $_POST['id'];
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
    header('Location: cart.php');
    exit;
}

// Ambil data produk keranjang
$cart_items = [];
$total_harga = 0;

if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $ids_safe = implode(',', array_map('intval', $ids));
    $query = "SELECT * FROM produk WHERE id IN ($ids_safe)";
    $result = mysqli_query($koneksi, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $row['qty'] = $_SESSION['cart'][$row['id']];
        $row['subtotal'] = $row['qty'] * $row['harga'];
        $total_harga += $row['subtotal'];
        $cart_items[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Keranjang Belanja - HijabStore</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: linear-gradient(135deg, #ffeef3, #ffd6e8);
            margin: 0;
            padding: 0;
            color: #5a0033;
        }

        header {
            background-color: #ff4da6;
            padding: 25px 0;
            text-align: center;
            font-weight: 700;
            font-size: 2rem;
            color: white;
            letter-spacing: 2px;
            box-shadow: 0 5px 15px rgba(255, 77, 166, 0.6);
            user-select: none;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            border-radius: 25px;
            padding: 30px 40px;
            box-shadow: 0 8px 25px rgba(255, 77, 166, 0.3);
        }

        .cart-item {
            display: flex;
            align-items: center;
            background: #fff0f6;
            border-radius: 20px;
            margin-bottom: 20px;
            padding: 15px 20px;
            box-shadow: 0 4px 10px rgba(255, 77, 166, 0.15);
            transition: transform 0.2s ease, box-shadow 0.3s ease;
        }
        .cart-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(255, 77, 166, 0.3);
        }

        .cart-item img {
            width: 100px;
            height: 100px;
            border-radius: 18px;
            object-fit: cover;
            box-shadow: 0 3px 8px rgba(255, 77, 166, 0.4);
            margin-right: 20px;
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 8px;
        }

        .item-price {
            font-size: 1rem;
            margin-bottom: 12px;
            color: #b30052;
            font-weight: 600;
        }

        .qty-controls {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
        }

        .qty-btn {
            width: 32px;
            height: 32px;
            background-color: #ff7eb9;
            border: none;
            border-radius: 50%;
            color: white;
            font-weight: 700;
            font-size: 1.3rem;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(255, 126, 185, 0.6);
            transition: background-color 0.3s ease;
            user-select: none;
        }
        .qty-btn:hover {
            background-color: #ff4da6;
            box-shadow: 0 6px 16px rgba(255, 77, 166, 0.9);
        }

        .qty-display {
            font-weight: 600;
            font-size: 1.1rem;
            width: 30px;
            text-align: center;
            user-select: none;
        }

        .subtotal {
            font-weight: 700;
            font-size: 1.1rem;
            color: #d81b62;
            min-width: 110px;
            text-align: right;
        }

        .btn-remove {
            background-color: #e63946;
            border: none;
            border-radius: 15px;
            color: white;
            padding: 8px 14px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(230, 57, 70, 0.8);
            transition: background-color 0.3s ease;
            user-select: none;
        }
        .btn-remove:hover {
            background-color: #b92c34;
            box-shadow: 0 6px 18px rgba(185, 44, 52, 0.9);
        }

        .actions {
            display: flex;
            justify-content: space-between;
            margin-top: 35px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .btn {
            background-color: #ff7eb9;
            border: none;
            padding: 14px 40px;
            border-radius: 30px;
            font-weight: 700;
            color: white;
            cursor: pointer;
            font-size: 1.15rem;
            box-shadow: 0 6px 16px rgba(255, 126, 185, 0.7);
            text-decoration: none;
            display: inline-block;
            user-select: none;
            transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.2s ease;
        }
        .btn:hover {
            background-color: #ff4da6;
            box-shadow: 0 8px 22px rgba(255, 77, 166, 0.9);
            transform: scale(1.05);
        }
        .btn.lanjut-belanja {
            background-color: #ffc1d9;
            color: #7a0033;
        }
        .btn.lanjut-belanja:hover {
            background-color: #ff9ac1;
            color: #5a0033;
        }

        .btn.checkout {
            background-color: #d81b62;
        }
        .btn.checkout:hover {
            background-color: #b31e52;
        }

        .total {
            font-size: 1.8rem;
            font-weight: 700;
            color: #a60057;
            text-align: right;
            margin-top: 30px;
            letter-spacing: 1.5px;
        }

        .empty-cart {
            font-size: 1.4rem;
            font-weight: 600;
            color: #a1999f;
            text-align: center;
            margin: 70px 0;
            user-select: none;
        }

        a.back-home {
            display: block;
            text-align: center;
            font-weight: 700;
            font-size: 1.1rem;
            color: #ff4da6;
            margin-top: 25px;
            text-decoration: none;
            user-select: none;
            transition: color 0.3s ease;
        }
        a.back-home:hover {
            color: #d81b62;
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 700px) {
            .cart-item {
                flex-direction: column;
                align-items: flex-start;
                padding: 20px;
            }
            .cart-item img {
                margin-bottom: 15px;
                width: 100%;
                height: auto;
                border-radius: 15px;
            }
            .subtotal {
                width: 100%;
                text-align: left;
                margin-top: 10px;
            }
            .actions {
                flex-direction: column;
            }
            .btn {
                width: 100%;
                text-align: center;
            }
            .total {
                text-align: center;
                margin-top: 40px;
            }
        }
    </style>
</head>
<body>
<header>Keranjang Belanja HijabStore</header>

<div class="container">
    <?php if (empty($cart_items)): ?>
        <p class="empty-cart">Keranjangmu masih kosong.</p>
        <a href="index.php" class="back-home">← Kembali ke Beranda</a>
    <?php else: ?>
        <?php foreach ($cart_items as $item): ?>
            <div class="cart-item">
                <img src="images/<?= htmlspecialchars($item['gambar']) ?>" alt="<?= htmlspecialchars($item['nama']) ?>" />
                <div class="item-details">
                    <div class="item-name"><?= htmlspecialchars($item['nama']) ?></div>
                    <div class="item-price">Rp<?= number_format($item['harga'], 0, ',', '.') ?></div>
                    <div class="qty-controls">
                        <form method="POST" style="margin:0;">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>" />
                            <button class="qty-btn" type="submit" name="reduce" title="Kurangi jumlah">−</button>
                        </form>
                        <div class="qty-display"><?= $item['qty'] ?></div>
                        <form method="POST" style="margin:0;">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>" />
                            <button class="qty-btn" type="submit" name="add" title="Tambah jumlah">+</button>
                        </form>
                    </div>
                </div>
                <div class="subtotal">Rp<?= number_format($item['subtotal'], 0, ',', '.') ?></div>
                <form method="POST" style="margin-left: 20px;">
                    <input type="hidden" name="id" value="<?= $item['id'] ?>" />
                    <button class="btn-remove" type="submit" name="remove" title="Hapus produk">Hapus</button>
                </form>
            </div>
        <?php endforeach; ?>

        <div class="total">Total: Rp<?= number_format($total_harga, 0, ',', '.') ?></div>

        <div class="actions">
            <a href="index.php" class="btn lanjut-belanja">← Lanjut Belanja</a>
            <a href="checkout.php" class="btn checkout">Checkout</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
