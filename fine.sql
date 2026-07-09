-- MySQL dump 10.13  Distrib 8.0.46, for Linux (x86_64)
--
-- Host: localhost    Database: finenectar
-- ------------------------------------------------------
-- Server version	8.0.46-0ubuntu0.24.04.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('finenectar-cache-2be2b0608bcc1788003fd9525424b40c','i:1;',1782559956),('finenectar-cache-2be2b0608bcc1788003fd9525424b40c:timer','i:1782559956;',1782559956),('finenectar-cache-da4b9237bacccdf19c0760cab7aec4a8359010b0','i:1;',1782545105),('finenectar-cache-da4b9237bacccdf19c0760cab7aec4a8359010b0:timer','i:1782545105;',1782545105),('finenectar-cache-e84c77ce34660901a80d6252755fccf0','i:3;',1782537969),('finenectar-cache-e84c77ce34660901a80d6252755fccf0:timer','i:1782537969;',1782537969),('finenectar-cache-fadiaskeyn@gmail.com|127.0.0.1','i:3;',1782537970),('finenectar-cache-fadiaskeyn@gmail.com|127.0.0.1:timer','i:1782537970;',1782537970),('finenectara-cache-2be2b0608bcc1788003fd9525424b40c','i:1;',1783006836),('finenectara-cache-2be2b0608bcc1788003fd9525424b40c:timer','i:1783006836;',1783006836),('finenectara-cache-5c785c036466adea360111aa28563bfd556b5fba','i:18;',1782992072),('finenectara-cache-5c785c036466adea360111aa28563bfd556b5fba:timer','i:1782992072;',1782992072),('laravel-cache-2be2b0608bcc1788003fd9525424b40c','i:1;',1782537882),('laravel-cache-2be2b0608bcc1788003fd9525424b40c:timer','i:1782537882;',1782537882),('laravel-cache-da4b9237bacccdf19c0760cab7aec4a8359010b0','i:1;',1782118880),('laravel-cache-da4b9237bacccdf19c0760cab7aec4a8359010b0:timer','i:1782118880;',1782118880);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_reserved_at_available_at_index` (`queue`,`reserved_at`,`available_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_03_17_000003_create_orders_table',2),(5,'2026_03_17_092734_add_two_factor_columns_to_users_table',3),(6,'2026_06_18_114041_create_products_table',4),(7,'2026_06_21_000001_add_product_snapshot_to_orders_table',4),(8,'2026_07_02_000001_add_shipping_fields_to_products_table',5),(9,'2026_07_02_000002_add_shipping_fields_to_orders_table',5);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int unsigned NOT NULL,
  `unit_price` bigint unsigned NOT NULL,
  `subtotal_amount` bigint unsigned NOT NULL DEFAULT '0',
  `shipping_amount` bigint unsigned NOT NULL DEFAULT '0',
  `shipping_courier` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_service` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_etd` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_destination_id` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_response` json DEFAULT NULL,
  `total_amount` bigint unsigned NOT NULL,
  `payment_method` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `merchant_ref` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tripay_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tripay_checkout_url` text COLLATE utf8mb4_unicode_ci,
  `tripay_qr_url` text COLLATE utf8mb4_unicode_ci,
  `tripay_response` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_merchant_ref_index` (`merchant_ref`),
  KEY `orders_tripay_reference_index` (`tripay_reference`),
  KEY `orders_product_id_foreign` (`product_id`),
  CONSTRAINT `orders_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'padias','085232132528','jl mh thamrin gladak pakem',NULL,NULL,2,35000,0,0,NULL,NULL,NULL,NULL,NULL,70000,'qris','waiting_payment','FN-20260317083227-HZHNR',NULL,NULL,NULL,NULL,'2026-03-17 01:32:27','2026-03-17 01:32:27'),(2,'padias','085859714058','Jember',1,'Fine Nectar Honey 200ml',1,35000,0,0,NULL,NULL,NULL,NULL,NULL,35000,'qris','tripay_failed','FN-20260622081526-DQUTI',NULL,NULL,NULL,'{\"message\": \"Invalid API Key\", \"success\": false}','2026-06-22 01:15:26','2026-06-22 01:15:27'),(3,'padias','085859714058','Jember',2,'Fine Nectar Daily Bundle',1,99000,0,0,NULL,NULL,NULL,NULL,NULL,99000,'qris','tripay_failed','FN-20260622085032-0NQXM',NULL,NULL,NULL,'{\"message\": \"Invalid API Key\", \"success\": false}','2026-06-22 01:50:32','2026-06-22 01:50:32'),(4,'padias','085859714058','Jember',1,'Fine Nectar Honey 200ml',1,35000,0,0,NULL,NULL,NULL,NULL,NULL,35000,'qris','waiting_payment','FN-20260622090020-N62H1','DEV-T16923380580XED9S','https://tripay.co.id/checkout/DEV-T16923380580XED9S','https://tripay.co.id/qr/DEV-T16923380580XED9S','{\"data\": {\"amount\": 35995, \"qr_url\": \"https://tripay.co.id/qr/DEV-T16923380580XED9S\", \"status\": \"UNPAID\", \"pay_url\": null, \"pay_code\": null, \"qr_string\": \"SANDBOX MODE\", \"reference\": \"DEV-T16923380580XED9S\", \"total_fee\": 995, \"return_url\": \"http://localhost:8000/#order\", \"order_items\": [{\"sku\": \"FN-1\", \"name\": \"Fine Nectar Honey 200ml\", \"price\": 35000, \"quantity\": 1, \"subtotal\": 35000, \"image_url\": null, \"product_url\": \"http://localhost:8000/produk/fine-nectar-honey-200ml\"}], \"callback_url\": \"http://localhost:8000/payments/tripay/callback\", \"checkout_url\": \"https://tripay.co.id/checkout/DEV-T16923380580XED9S\", \"expired_time\": 1782205161, \"fee_customer\": 995, \"fee_merchant\": 0, \"instructions\": [{\"steps\": [\"Masuk ke aplikasi dompet digital Anda yang telah mendukung QRIS\", \"Pindai/Scan QR Code yang tersedia\", \"Akan muncul detail transaksi. Pastikan data transaksi sudah sesuai\", \"Selesaikan proses pembayaran Anda\", \"Transaksi selesai. Simpan bukti pembayaran Anda\"], \"title\": \"Pembayaran via QRIS\"}, {\"steps\": [\"Download QR Code pada invoice\", \"Masuk ke aplikasi dompet digital Anda yang telah mendukung QRIS\", \"Upload QR Code yang telah di download tadi\", \"Akan muncul detail transaksi. Pastikan data transaksi sudah sesuai\", \"Selesaikan proses pembayaran Anda\", \"Transaksi selesai. Simpan bukti pembayaran Anda\"], \"title\": \"Pembayaran via QRIS (Mobile)\"}], \"merchant_ref\": \"FN-20260622090020-N62H1\", \"payment_name\": \"QRIS\", \"customer_name\": \"padias\", \"customer_email\": \"customer+4@finenectar.local\", \"customer_phone\": \"085859714058\", \"payment_method\": \"QRIS2\", \"amount_received\": 35000, \"payment_selection_type\": \"static\"}, \"message\": \"\", \"success\": true}','2026-06-22 02:00:20','2026-06-22 02:00:21'),(5,'padias','081233151566','jmbrrr',1,'Fine Nectar Honey 200ml',1,35000,0,0,NULL,NULL,NULL,NULL,NULL,35000,'qris','waiting_payment','FN-20260627062608-VRYJV','DEV-T16923382097IZJJA','https://tripay.co.id/checkout/DEV-T16923382097IZJJA','https://tripay.co.id/qr/DEV-T16923382097IZJJA','{\"data\": {\"amount\": 35995, \"qr_url\": \"https://tripay.co.id/qr/DEV-T16923382097IZJJA\", \"status\": \"UNPAID\", \"pay_url\": null, \"pay_code\": null, \"qr_string\": \"SANDBOX MODE\", \"reference\": \"DEV-T16923382097IZJJA\", \"total_fee\": 995, \"return_url\": \"http://localhost:8000/#order\", \"order_items\": [{\"sku\": \"FN-1\", \"name\": \"Fine Nectar Honey 200ml\", \"price\": 35000, \"quantity\": 1, \"subtotal\": 35000, \"image_url\": null, \"product_url\": \"http://localhost:8000/produk/fine-nectar-honey-200ml\"}], \"callback_url\": \"http://localhost:8000/payments/tripay/callback\", \"checkout_url\": \"https://tripay.co.id/checkout/DEV-T16923382097IZJJA\", \"expired_time\": 1782627909, \"fee_customer\": 995, \"fee_merchant\": 0, \"instructions\": [{\"steps\": [\"Masuk ke aplikasi dompet digital Anda yang telah mendukung QRIS\", \"Pindai/Scan QR Code yang tersedia\", \"Akan muncul detail transaksi. Pastikan data transaksi sudah sesuai\", \"Selesaikan proses pembayaran Anda\", \"Transaksi selesai. Simpan bukti pembayaran Anda\"], \"title\": \"Pembayaran via QRIS\"}, {\"steps\": [\"Download QR Code pada invoice\", \"Masuk ke aplikasi dompet digital Anda yang telah mendukung QRIS\", \"Upload QR Code yang telah di download tadi\", \"Akan muncul detail transaksi. Pastikan data transaksi sudah sesuai\", \"Selesaikan proses pembayaran Anda\", \"Transaksi selesai. Simpan bukti pembayaran Anda\"], \"title\": \"Pembayaran via QRIS (Mobile)\"}], \"merchant_ref\": \"FN-20260627062608-VRYJV\", \"payment_name\": \"QRIS\", \"customer_name\": \"padias\", \"customer_email\": \"customer+5@finenectar.local\", \"customer_phone\": \"081233151566\", \"payment_method\": \"QRIS2\", \"amount_received\": 35000, \"payment_selection_type\": \"static\"}, \"message\": \"\", \"success\": true}','2026-06-26 23:26:08','2026-06-26 23:26:09'),(6,'hilmy','6281233151566','Jember',1,'Fine Nectar Honey 200ml',1,35000,0,0,NULL,NULL,NULL,NULL,NULL,35000,'qris','waiting_payment','FN-20260627072232-1GRP9','DEV-T16923382107HUBEJ','https://tripay.co.id/checkout/DEV-T16923382107HUBEJ','https://tripay.co.id/qr/DEV-T16923382107HUBEJ','{\"data\": {\"amount\": 35995, \"qr_url\": \"https://tripay.co.id/qr/DEV-T16923382107HUBEJ\", \"status\": \"UNPAID\", \"pay_url\": null, \"pay_code\": null, \"qr_string\": \"SANDBOX MODE\", \"reference\": \"DEV-T16923382107HUBEJ\", \"total_fee\": 995, \"return_url\": \"http://localhost:8000/#order\", \"order_items\": [{\"sku\": \"FN-1\", \"name\": \"Fine Nectar Honey 200ml\", \"price\": 35000, \"quantity\": 1, \"subtotal\": 35000, \"image_url\": null, \"product_url\": \"http://localhost:8000/produk/fine-nectar-honey-200ml\"}], \"callback_url\": \"http://localhost:8000/payments/tripay/callback\", \"checkout_url\": \"https://tripay.co.id/checkout/DEV-T16923382107HUBEJ\", \"expired_time\": 1782631292, \"fee_customer\": 995, \"fee_merchant\": 0, \"instructions\": [{\"steps\": [\"Masuk ke aplikasi dompet digital Anda yang telah mendukung QRIS\", \"Pindai/Scan QR Code yang tersedia\", \"Akan muncul detail transaksi. Pastikan data transaksi sudah sesuai\", \"Selesaikan proses pembayaran Anda\", \"Transaksi selesai. Simpan bukti pembayaran Anda\"], \"title\": \"Pembayaran via QRIS\"}, {\"steps\": [\"Download QR Code pada invoice\", \"Masuk ke aplikasi dompet digital Anda yang telah mendukung QRIS\", \"Upload QR Code yang telah di download tadi\", \"Akan muncul detail transaksi. Pastikan data transaksi sudah sesuai\", \"Selesaikan proses pembayaran Anda\", \"Transaksi selesai. Simpan bukti pembayaran Anda\"], \"title\": \"Pembayaran via QRIS (Mobile)\"}], \"merchant_ref\": \"FN-20260627072232-1GRP9\", \"payment_name\": \"QRIS\", \"customer_name\": \"hilmy\", \"customer_email\": \"customer+6@finenectar.local\", \"customer_phone\": null, \"payment_method\": \"QRIS2\", \"amount_received\": 35000, \"payment_selection_type\": \"static\"}, \"message\": \"\", \"success\": true}','2026-06-27 00:22:32','2026-06-27 00:22:32'),(7,'padias','6285859714058','Jember',1,'Fine Nectar Honey 200ml',1,35000,0,0,NULL,NULL,NULL,NULL,NULL,35000,'qris','waiting_payment','FN-20260627072405-U2ODV','DEV-T16923382108BGNCY','https://tripay.co.id/checkout/DEV-T16923382108BGNCY','https://tripay.co.id/qr/DEV-T16923382108BGNCY','{\"data\": {\"amount\": 35995, \"qr_url\": \"https://tripay.co.id/qr/DEV-T16923382108BGNCY\", \"status\": \"UNPAID\", \"pay_url\": null, \"pay_code\": null, \"qr_string\": \"SANDBOX MODE\", \"reference\": \"DEV-T16923382108BGNCY\", \"total_fee\": 995, \"return_url\": \"http://localhost:8000/#order\", \"order_items\": [{\"sku\": \"FN-1\", \"name\": \"Fine Nectar Honey 200ml\", \"price\": 35000, \"quantity\": 1, \"subtotal\": 35000, \"image_url\": null, \"product_url\": \"http://localhost:8000/produk/fine-nectar-honey-200ml\"}], \"callback_url\": \"http://localhost:8000/payments/tripay/callback\", \"checkout_url\": \"https://tripay.co.id/checkout/DEV-T16923382108BGNCY\", \"expired_time\": 1782631385, \"fee_customer\": 995, \"fee_merchant\": 0, \"instructions\": [{\"steps\": [\"Masuk ke aplikasi dompet digital Anda yang telah mendukung QRIS\", \"Pindai/Scan QR Code yang tersedia\", \"Akan muncul detail transaksi. Pastikan data transaksi sudah sesuai\", \"Selesaikan proses pembayaran Anda\", \"Transaksi selesai. Simpan bukti pembayaran Anda\"], \"title\": \"Pembayaran via QRIS\"}, {\"steps\": [\"Download QR Code pada invoice\", \"Masuk ke aplikasi dompet digital Anda yang telah mendukung QRIS\", \"Upload QR Code yang telah di download tadi\", \"Akan muncul detail transaksi. Pastikan data transaksi sudah sesuai\", \"Selesaikan proses pembayaran Anda\", \"Transaksi selesai. Simpan bukti pembayaran Anda\"], \"title\": \"Pembayaran via QRIS (Mobile)\"}], \"merchant_ref\": \"FN-20260627072405-U2ODV\", \"payment_name\": \"QRIS\", \"customer_name\": \"padias\", \"customer_email\": \"customer+7@finenectar.local\", \"customer_phone\": null, \"payment_method\": \"QRIS2\", \"amount_received\": 35000, \"payment_selection_type\": \"static\"}, \"message\": \"\", \"success\": true}','2026-06-27 00:24:05','2026-06-27 00:24:05');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` bigint unsigned NOT NULL,
  `compare_at_price` bigint unsigned DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `net_weight` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight_grams` int unsigned NOT NULL DEFAULT '1000',
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock` int unsigned NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_promo` tinyint(1) NOT NULL DEFAULT '0',
  `is_free_shipping` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Fine Nectar Honey 200ml','fine-nectar-honey-200ml','Madu murni premium untuk rutinitas pagi, campuran minuman, dan energi harian.',35000,55000,'/storage/products/vwPz7TgFpJw2fuaMXNBYZ8FoDpoDkyiBRnDl7gpq.jpg','200ml',1000,'Madu konsumsi harian',100,1,0,1,0,'2026-06-20 23:16:52','2026-07-02 08:42:29'),(2,'Fine Nectar Daily Bundle','fine-nectar-daily-bundle','Paket hemat untuk stok madu di rumah atau kantor.',99000,165000,'/storage/products/OVFncYUGPAcQJJsdjNqfkabxHXStdRJNQUVKYXMN.jpg','3 x 200ml',1000,'Bundle',40,1,1,0,1,'2026-06-20 23:16:52','2026-07-02 08:42:53');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('YhtVW3KJZZMmHgtATE9hvjs2ScgTkgqv32bUnXdD',2,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiSGw3eVRpY0NJNTkyV1Z5blB2OFZZM2ZoNDcxMnZPVGp3MVlPWjlQVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9vcmRlcnMiO3M6NToicm91dGUiO3M6MTg6ImFkbWluLm9yZGVycy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=',1783008708);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Test User','test@example.com','2026-03-14 13:44:24','$2y$12$5P65S7ooQGao/eH5E/gLIuVUOlZ5YRKBWy3Xu3.34FKQAOSdVE6Xm',NULL,NULL,NULL,'F6tgksq5gM','2026-03-14 13:44:25','2026-03-14 13:44:25'),(2,'admin','admin@mail.com','2026-03-17 02:31:30','$2y$12$SV/DwBBIflmihGpd5TmQAeBygV0CVkNZzsDjkKSTgaGOmyqZgLpVa',NULL,NULL,NULL,'vVmaOsXO1a','2026-03-17 02:31:30','2026-03-17 02:31:30');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-03  0:44:55
