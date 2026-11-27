CREATE DATABASE IF NOT EXISTS db_caritas_d2 CHARACTER SET utf8mb4;
USE db_caritas_d2;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(20) DEFAULT '',
  `foto_profil` varchar(255) DEFAULT '',
  `role` enum('user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_menu` varchar(100) NOT NULL,
  `grup_menu` varchar(50) NOT NULL,
  `filter_tag` varchar(30) NOT NULL,
  `is_signature` tinyint(1) DEFAULT 0,
  `harga_hot` int(11) DEFAULT NULL,
  `harga_ice` int(11) DEFAULT NULL,
  `deskripsi` varchar(255) DEFAULT '',
  `gambar_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `cart_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `penyajian` varchar(20) NOT NULL DEFAULT '',
  `gula` varchar(20) NOT NULL DEFAULT '',
  `es` varchar(20) NOT NULL DEFAULT '',
  `catatan` varchar(255) NOT NULL DEFAULT '',
  `qty` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `cart_items_ibfk_2` (`menu_id`),
  CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_antrean` varchar(15) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nama_pemesan` varchar(100) NOT NULL,
  `no_wa` varchar(25) NOT NULL,
  `lokasi_ambil` varchar(100) NOT NULL,
  `jarak_info` varchar(100) DEFAULT 'Manual',
  `metode_ambil` varchar(50) DEFAULT 'Pickup',
  `waktu_ambil` varchar(30) DEFAULT 'Sekarang',
  `metode_bayar` varchar(50) DEFAULT 'QRIS',
  `subtotal` int(11) NOT NULL,
  `diskon` int(11) DEFAULT 0,
  `promo_nama` varchar(100) DEFAULT NULL,
  `total_bayar` int(11) NOT NULL,
  `uang_diterima` int(11) DEFAULT NULL,
  `rincian_item` text NOT NULL,
  `catatan` varchar(255) DEFAULT '',
  `status` enum('Pesanan diterima','Sedang dibuat barista','Siap diambil','Selesai') DEFAULT 'Pesanan diterima',
  `sumber` enum('online','kasir') NOT NULL DEFAULT 'online',
  `dibuat_oleh` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `penyajian` varchar(20) NOT NULL DEFAULT '',
  `qty` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `catatan` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `order_items_ibfk_2` (`menu_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `promos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_promo` varchar(100) NOT NULL,
  `deskripsi` varchar(255) NOT NULL DEFAULT '',
  `jenis` varchar(10) NOT NULL DEFAULT 'klaim',
  `diskon_persen` int(11) NOT NULL,
  `berlaku_hari` int(11) NOT NULL DEFAULT 7,
  `batas_pakai` int(11) NOT NULL DEFAULT 1,
  `kuota` int(11) NOT NULL DEFAULT 0,
  `klaim_sampai` datetime DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `user_promos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `promo_id` int(11) NOT NULL,
  `berlaku_sampai` datetime NOT NULL,
  `jumlah_dipakai` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_promo` (`user_id`,`promo_id`),
  KEY `user_promos_ibfk_2` (`promo_id`),
  CONSTRAINT `user_promos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_promos_ibfk_2` FOREIGN KEY (`promo_id`) REFERENCES `promos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `pengaturan` (
  `kunci` varchar(50) NOT NULL,
  `nilai` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`kunci`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `hero_fotos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sumber` varchar(255) NOT NULL,
  `jenis` varchar(10) NOT NULL DEFAULT 'foto',
  `urutan` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
INSERT INTO `menus` VALUES (1,'Carita','SIGNATURE COFFEE','Signature',1,NULL,25000,'Signature coffee — es kopi susu khas Caritas Cafe.','https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (2,'Salted Caramel','FLAVOUR COFFEE','Flavour',1,20000,22000,'Flavour coffee — kopi susu dengan salted caramel.','https://images.unsplash.com/photo-1572442388796-11668a67e53d?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (3,'Matcha','POWDER BASED','Powder',1,22000,24000,'Powder based — pure matcha latte creamy.','https://images.unsplash.com/photo-1536256263959-770b48d82b0a?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (4,'Butter Croissant','CROISSANT','Pastry',1,15000,NULL,'Pastry — croissant renyah dengan butter premium.','https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (5,'Butterscotch','FLAVOUR COFFEE','Flavour',0,20000,22000,'Flavour coffee — kopi susu dengan sirup butterscotch.','https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (6,'Hazelnut','FLAVOUR COFFEE','Flavour',0,20000,22000,'Flavour coffee — kopi susu aroma hazelnut.','https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (7,'Aren','FLAVOUR COFFEE','Flavour',0,20000,22000,'Flavour coffee — kopi susu gula aren.','https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (8,'Espresso','BASIC COFFEE','Basic',0,5000,NULL,'Basic coffee — single shot espresso.','https://images.unsplash.com/photo-1510591509098-f4fdc6d0ff04?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (9,'Americano','BASIC COFFEE','Basic',0,15000,18000,'Basic coffee — espresso dan air mineral.','https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (10,'Latte','BASIC COFFEE','Basic',0,20000,21000,'Basic coffee — espresso dengan susu steamed.','https://images.unsplash.com/photo-1572442388796-11668a67e53d?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (11,'Cappuccino','BASIC COFFEE','Basic',0,20000,21000,'Basic coffee — espresso dengan foam susu tebal.','https://images.unsplash.com/photo-1572442388796-11668a67e53d?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (12,'Arum','SIGNATURE COFFEE','Signature',0,NULL,25000,'Signature coffee — racikan dingin spesial Caritas.','https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (13,'Renjana','SIGNATURE COFFEE','Signature',0,NULL,25000,'Signature coffee — kopi susu manis aromatik.','https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (14,'Sweet Berry Caramel','SIGNATURE COFFEE','Signature',0,NULL,25000,'Signature coffee — perpaduan berry dan caramel.','https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (15,'Lychee Tea','TEA BASED','Tea',0,15000,20000,'Tea based — teh segar dengan buah leci.','https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (16,'Lemon Tea','TEA BASED','Tea',0,15000,20000,'Tea based — teh dengan perasan lemon segar.','https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (17,'Peach Tea','TEA BASED','Tea',0,15000,20000,'Tea based — teh aromatik rasa peach.','https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (18,'Chocolate','POWDER BASED','Powder',0,20000,22000,'Powder based — cokelat pekat creamy.','https://images.unsplash.com/photo-1542990253-0d0f5be5f0ed?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (19,'Red Velvet','POWDER BASED','Powder',0,20000,22000,'Powder based — red velvet latte lembut.','https://images.unsplash.com/photo-1542990253-0d0f5be5f0ed?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (20,'Taro','POWDER BASED','Powder',0,20000,22000,'Powder based — taro latte manis gurih.','https://images.unsplash.com/photo-1542990253-0d0f5be5f0ed?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (21,'Choco Rum','POWDER BASED','Powder',0,20000,22000,'Powder based — cokelat aroma rum non-alkohol.','https://images.unsplash.com/photo-1542990253-0d0f5be5f0ed?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (22,'Atmaja','REFRESHMENTS','Tea',0,NULL,24000,'Refreshments — mocktail buah menyegarkan.','https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (23,'Wistara','REFRESHMENTS','Tea',0,NULL,24000,'Refreshments — soda citrus segar.','https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (24,'Sasmita','REFRESHMENTS','Tea',0,NULL,24000,'Refreshments — minuman dingin tropis.','https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (25,'Strawberry Yakult','REFRESHMENTS','Tea',0,NULL,22000,'Refreshments — stroberi segar dan yakult.','https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (26,'Lychee Yakult','REFRESHMENTS','Tea',0,NULL,22000,'Refreshments — leci manis dan yakult.','https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (27,'Pain Au Chocolat','CROISSANT','Pastry',0,18000,NULL,'Croissant dengan isian cokelat.','https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (28,'Triple Cheese','CROISSANT','Pastry',0,18000,NULL,'Croissant gurih tiga keju.','https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (29,'Almond','CROISSANT','Pastry',0,18000,NULL,'Croissant taburan kacang almond.','https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (30,'Cinnamon Rolls','CROISSANT','Pastry',0,18000,NULL,'Roti gulung kayu manis.','https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (31,'Chocolate Nuts','CROISSANT','Pastry',0,18000,NULL,'Pastry cokelat dan kacang.','https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (32,'Black Charcoal','SOURDOUGH','Pastry',0,19000,NULL,'Sourdough arang aktif.','https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (33,'Matcha Sourdough','SOURDOUGH','Pastry',0,23000,NULL,'Sourdough rasa matcha.','https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=600&q=80');
INSERT INTO `menus` VALUES (34,'Jogja Bun All Variant','JOGJA BUN','Pastry',0,8000,NULL,'Coffee bun hangat lembut.','https://images.unsplash.com/photo-1555507036-ab1f4038808a?auto=format&fit=crop&w=600&q=80');
INSERT INTO `promos` VALUES (1,'Diskon Akun Baru','Hadiah selamat datang untuk setiap akun baru, berlaku untuk pesanan pertamamu.','otomatis',20,7,1,0,NULL,1,'2026-11-18 21:46:17');
INSERT INTO `promos` VALUES (3,'Promo Komunitas Garuda Katolik','Promo Komunitas Garuda Katolik','klaim',5,8,1,0,NULL,1,'2026-11-18 21:58:45');

SET FOREIGN_KEY_CHECKS = 1;
