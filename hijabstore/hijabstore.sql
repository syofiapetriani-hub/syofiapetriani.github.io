-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for hijabstore
CREATE DATABASE IF NOT EXISTS `hijabstore` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `hijabstore`;

-- Dumping structure for table hijabstore.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table hijabstore.orders: ~7 rows (approximately)
INSERT INTO `orders` (`id`, `user_id`, `order_date`, `total`) VALUES
	(1, 17, '2025-06-23 16:54:49', 50000.00),
	(2, 17, '2025-06-23 16:55:46', 50000.00),
	(3, 17, '2025-06-23 17:07:03', 238000.00),
	(4, 17, '2025-06-23 17:08:12', 95000.00),
	(5, 17, '2025-06-24 18:48:03', 115000.00),
	(6, 17, '2025-06-24 18:49:34', 90000.00),
	(7, 17, '2025-06-24 18:56:50', 90000.00);

-- Dumping structure for table hijabstore.order_items
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int DEFAULT NULL,
  `produk_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `price` decimal(20,6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table hijabstore.order_items: ~11 rows (approximately)
INSERT INTO `order_items` (`id`, `order_id`, `produk_id`, `quantity`, `price`) VALUES
	(1, 2, 18, 1, 50000.000000),
	(2, 3, 9, 1, 45000.000000),
	(3, 3, 10, 1, 80000.000000),
	(4, 3, 12, 1, 35000.000000),
	(5, 3, 17, 1, 78000.000000),
	(6, 4, 19, 1, 65000.000000),
	(7, 4, 20, 1, 30000.000000),
	(8, 5, 10, 1, 80000.000000),
	(9, 5, 12, 1, 35000.000000),
	(10, 6, 9, 2, 45000.000000),
	(11, 7, 9, 2, 45000.000000);

-- Dumping structure for table hijabstore.produk
CREATE TABLE IF NOT EXISTS `produk` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `gambar` varchar(50) DEFAULT NULL,
  `stok` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table hijabstore.produk: ~13 rows (approximately)
INSERT INTO `produk` (`id`, `nama`, `harga`, `gambar`, `stok`) VALUES
	(10, 'satin', 80000, 'satin.jpeg', 4),
	(12, 'paris premium', 35000, 'paris.jpeg', 9),
	(13, 'voal sakura', 90000, 'voal-sakura.jpeg', 56),
	(14, 'bella squer', 40000, 'bella.jpeg', 32),
	(17, 'CERUTY', 78000, 'ceruty.jpeg', 67),
	(18, 'Hijab crinkle', 50000, 'Hijab crinkle.jpg', 236),
	(19, 'Hijab Viscose', 65000, 'Hijab viscose.jpg', 123),
	(22, 'SATIN', 69000, 'satin.jpeg', 6),
	(24, 'BAWAL INNER', 65000, 'Bawal inner.webp', 789),
	(25, 'VOILA PARIS SQUARE', 15000, 'voila paris square.webp', 125),
	(26, 'INSTAN SHAWL', 20000, 'instan shawl.webp', 290),
	(27, 'HAARA VOAL', 45000, 'haara voal.webp', 165),
	(28, 'BAWAL INNER SOFT PINK', 99000, 'bawal inner soft pink.webp', 25);

-- Dumping structure for table hijabstore.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table hijabstore.users: ~2 rows (approximately)
INSERT INTO `users` (`id`, `username`, `nama`, `password`, `role`) VALUES
	(4, 'syofiah petriani', 'admin piaa', '$2y$10$Ka.cHAXPMv4JrxW6bfWeyum9xi.ya4VKx/lbvSK6mBu8PyYLRt73i', 'admin'),
	(17, 'putri', 'putri', '$2y$10$et0TUtJVKyh7y7Pb3HyuKO.RMRGnl4ANsWjtynl4ewNFjmXCyh5dK', 'user');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
