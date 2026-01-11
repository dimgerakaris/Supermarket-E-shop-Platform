-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Φιλοξενητής: sql100.infinityfree.com
-- Χρόνος δημιουργίας: 28 Δεκ 2025 στις 18:08:03
-- Έκδοση διακομιστή: 10.6.22-MariaDB
-- Έκδοση PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Βάση δεδομένων: `if0_40578332_smdb`
--

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `added_at` datetime NOT NULL DEFAULT current_timestamp(),
  `product_name` varchar(255) NOT NULL,
  `product_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Άδειασμα δεδομένων του πίνακα `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `price`, `added_at`, `product_name`, `product_image`) VALUES
(307, 24, 280, 2, '2.86', '2025-02-02 12:13:15', 'TROFINO Κολοκυθόσπορος Ψίχα Βιολογικός 200gr', 'PHOTO/products/kshroiKarpoi/TROFINOKOL.jpg');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(20) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Άδειασμα δεδομένων του πίνακα `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `username`, `email`, `user_id`, `message`, `submitted_at`, `phone`, `is_read`) VALUES
(13, 'mpapad', 'mpapad@eshop.com', 32, 'Το προιον Mellisa No6 πότε θα γίνει διαθέσιμο;', '2025-02-05 21:05:19', NULL, 0),
(14, 'mpapad', 'mpapad@eshop.com', 32, 'Παρακαλώ επικοινωνήστε μαζί μου.\r\nΕυχαριστώ!', '2025-02-05 21:07:25', NULL, 1),
(15, 'test', 'test@test.com', 21, '1111111111111111', '2025-12-28 22:55:44', NULL, 1);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `subject`, `message`, `created_at`, `is_read`) VALUES
(15, 21, '', 'Καλώς ήρθατε!', '2025-01-30 09:26:44', 1),
(17, 24, '', 'Καλώς ήρθατε στο σούπερ μάρκετ μας! Για οποιαδήποτε πληροφορία επικοινωνήστε μαζί μας!', '2025-01-31 15:09:44', 1),
(19, 32, '', 'Καλώς ήρθατε στο σούπερ μάρκετ μας! Για οποιαδήποτε πληροφορία επικοινωνήστε μαζί μας!', '2025-02-04 10:52:28', 1),
(20, 33, '', 'Καλώς ήρθατε στο σούπερ μάρκετ μας! Για οποιαδήποτε πληροφορία επικοινωνήστε μαζί μας!', '2025-02-04 11:13:20', 0),
(21, 34, '', 'Καλώς ήρθατε στο σούπερ μάρκετ μας! Για οποιαδήποτε πληροφορία επικοινωνήστε μαζί μας!', '2025-12-28 22:58:17', 0);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `message_replies`
--

CREATE TABLE `message_replies` (
  `id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `reply_text` text NOT NULL,
  `replied_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0,
  `user_id` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `message_replies`
--

INSERT INTO `message_replies` (`id`, `message_id`, `admin_id`, `reply_text`, `replied_at`, `is_read`, `user_id`, `email`) VALUES
(19, 13, 1, 'Το προϊόν που ενδιαφέρεστε θα γίνει διαθέσιμο σύντομα! \r\nΣας ευχαριστούμε!', '2025-02-05 21:22:22', 1, 32, ''),
(20, 14, 1, 'asdsad', '2025-12-28 22:54:09', 0, 32, ''),
(21, 14, 1, 'asds', '2025-12-28 22:54:20', 0, 32, ''),
(22, 15, 1, '2222222222222', '2025-12-28 22:59:12', 1, 21, '');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `delivery_method` enum('store_pickup','home_delivery') NOT NULL,
  `payment_method` enum('card','cash') NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `fName` varchar(50) DEFAULT NULL,
  `lName` varchar(50) DEFAULT NULL,
  `card_last_digits` varchar(4) DEFAULT NULL,
  `card_expiry_date` varchar(5) DEFAULT NULL,
  `payment_token` varchar(255) DEFAULT NULL,
  `number` varchar(10) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Εκκρεμεί',
  `coupon_total_amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `username`, `delivery_method`, `payment_method`, `total_amount`, `address`, `city`, `postal_code`, `order_date`, `fName`, `lName`, `card_last_digits`, `card_expiry_date`, `payment_token`, `number`, `status`, `coupon_total_amount`) VALUES
(68, 21, 'test', 'home_delivery', 'card', '3.20', 'Test Address', 'Test City', '12345', '2025-01-30 09:31:38', 'Test ', 'Test', '1234', '05/25', 'ec7a60d0b1113b075323f37429240d2c', '6912345678', 'Ολοκληρώθηκε', NULL),
(69, 21, 'test', 'home_delivery', 'cash', '29.80', 'Test Address', 'Test City', '12345', '2025-01-31 11:22:58', 'Test ', 'Test', '', '', '', '6912345678', 'Εκκρεμεί', NULL),
(70, 21, 'test', 'home_delivery', 'cash', '6.30', 'Test Address', 'Test City', '12345', '2025-01-31 12:16:17', 'Test ', 'Test', '', '', '', '6912345678', 'Εκκρεμεί', NULL),
(71, 21, 'test', 'store_pickup', 'cash', '10.30', 'Test Address', 'Test City', '12345', '2025-01-31 12:25:31', 'Test ', 'Test', '', '', '', '6912345678', 'Εκκρεμεί', NULL),
(72, 21, 'test', 'home_delivery', 'cash', '9.35', 'Test Address', 'Test City', '12345', '2025-01-31 12:32:08', 'Test ', 'Test', '', '', '', '6912345678', 'Εκκρεμεί', NULL),
(73, 21, 'test', 'home_delivery', 'cash', '20.30', 'Test Address', 'Test City', '12345', '2025-01-31 12:47:09', 'Test ', 'Test', '', '', '', '6912345678', 'Εκκρεμεί', NULL),
(74, 21, 'test', 'store_pickup', 'cash', '12.33', 'Test Address', 'Test City', '12345', '2025-01-31 13:40:37', 'Test ', 'Test', '', '', '', '6912345678', 'Εκκρεμεί', NULL),
(75, 21, 'test', 'home_delivery', 'cash', '3.32', 'Test Address', 'Test City', '12345', '2025-01-31 13:44:02', 'Test ', 'Test', '', '', '', '6912345678', 'Εκκρεμεί', NULL),
(76, 21, 'test', 'home_delivery', 'cash', '7.95', 'Test Address', 'Test City', '12345', '2025-01-31 13:44:28', 'Test ', 'Test', '', '', '', '6912345678', 'Εκκρεμεί', NULL),
(77, 21, 'test', 'home_delivery', 'cash', '7.60', 'Test Address', 'Test City', '12345', '2025-01-31 14:04:43', 'Test ', 'Test', '', '', '', '6912345678', 'Ολοκληρώθηκε', NULL),
(78, 21, 'test', 'store_pickup', 'cash', '3.69', 'Test Address', 'Test City', '12345', '2025-01-31 14:05:27', 'Test ', 'Test', '', '', '', '6912345678', 'Ολοκληρώθηκε', NULL),
(80, 21, NULL, 'home_delivery', 'card', '15.05', 'Test Address', 'Test City', '12345', '2025-02-02 10:14:58', 'Test ', 'Test', '4567', '04/25', '08a8ac938b404b05896b40a47431b2c2', '6912345678', 'Εκκρεμεί', NULL),
(81, 21, NULL, 'home_delivery', 'cash', '8.86', 'Test Address', 'Test City', '12345', '2025-02-02 15:29:57', 'Test ', 'Test', '', '', '', '6912345678', 'Εκκρεμεί', NULL),
(82, 21, NULL, 'home_delivery', 'cash', '1.54', 'Test Address', 'Test City', '12345', '2025-02-02 15:41:24', 'Test ', 'Test', '', '', '', '6912345678', 'Εκκρεμεί', NULL),
(83, 21, NULL, 'store_pickup', 'cash', '1.54', 'Test Address', 'Test City', '12345', '2025-02-02 15:42:42', 'Test ', 'Test', '', '', '', '6912345678', 'Εκκρεμεί', NULL),
(84, 21, NULL, 'home_delivery', 'cash', '23.18', 'Test Address', 'Test City', '12345', '2025-02-02 15:49:44', 'Test ', 'Test', '', '', '', '6912345678', 'Εκκρεμεί', NULL),
(85, 21, NULL, 'home_delivery', 'cash', '4.88', 'Test Address', 'Test City', '12345', '2025-02-02 15:50:31', 'Test ', 'Test', '', '', '', '6912345678', 'Εκκρεμεί', NULL),
(86, 21, NULL, 'store_pickup', 'cash', '13.20', 'Test Address', 'Test City', '12345', '2025-02-02 15:51:09', 'Test ', 'Test', '', '', '', '6912345678', 'Εκκρεμεί', NULL),
(87, 21, NULL, 'home_delivery', 'cash', '15.86', 'Test Address', 'Test City', '12345', '2025-02-02 15:52:01', 'Test ', 'Test', '', '', '', '6912345678', 'Εκκρεμεί', NULL),
(88, 21, NULL, 'home_delivery', 'cash', '10.20', 'Test Address', 'Test City', '12345', '2025-02-02 16:02:14', 'Test ', 'Test', '', '', '', '6912345678', 'Εκκρεμεί', NULL),
(89, 21, NULL, 'home_delivery', 'cash', '45.90', 'Test Address', 'Test City', '12345', '2025-02-02 16:05:46', 'Test ', 'Test', '', '', '', '6912345678', 'Εκκρεμεί', NULL),
(90, 21, NULL, 'store_pickup', 'cash', '5.60', 'Test Address', 'Test City', '12345', '2025-02-02 16:06:51', 'Test ', 'Test', '', '', '', '6912345678', 'Ολοκληρώθηκε', NULL),
(91, 21, NULL, 'home_delivery', 'cash', '2.60', 'Test Address', 'Test City', '12345', '2025-02-02 16:13:00', 'Test ', 'Test', '', '', '', '6912345678', 'Εκκρεμεί', NULL),
(92, 21, NULL, 'home_delivery', 'cash', '39.90', 'Test Address', 'Test City', '12345', '2025-02-02 16:24:24', 'Test ', 'Test', '', '', '', '6912345678', 'Εκκρεμεί', NULL),
(93, 21, NULL, 'home_delivery', 'cash', '6.60', 'Test Address', 'Test City', '12345', '2025-02-02 16:31:45', 'Test ', 'Test', '', '', '', '6912345678', 'Εκκρεμεί', NULL),
(94, 21, NULL, 'home_delivery', 'cash', '4.14', 'Test Address', 'Test City', '12345', '2025-02-02 16:45:15', 'Test ', 'Test', '', '', '', '6912345678', 'Ολοκληρώθηκε', NULL),
(95, 21, NULL, 'home_delivery', 'cash', '7.93', 'Test Address', 'Test City', '12345', '2025-02-02 16:45:49', 'Test ', 'Test', '', '', '', '6912345678', 'Εκκρεμεί', NULL),
(96, 21, NULL, 'home_delivery', 'cash', '92.17', 'Test Address', 'Test City', '12345', '2025-02-03 09:44:38', 'Test ', 'Test', '', '', '', '6912345678', 'Εκκρεμεί', NULL),
(97, 32, NULL, 'home_delivery', 'cash', '3.50', 'Λεωφόρος Κηφισίας', 'Μαρούσι, Αθήνα', '15124', '2025-02-05 09:44:58', 'Μαρία', 'Παπαδάκη', '', '', '', '6976543201', 'Ολοκληρώθηκε', NULL),
(98, 32, NULL, 'store_pickup', 'cash', '4.72', 'Λεωφόρος Κηφισίας', 'Μαρούσι, Αθήνα', '15124', '2025-02-06 12:19:34', 'Μαρία', 'Παπαδάκη', '', '', '', '6976543201', 'Εκκρεμεί', NULL);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_name`, `quantity`, `price`, `total`) VALUES
(159, 68, 'NIKAS Σαλάμι Αέρος Πικάντικο Χωρίς γλουτένη 165gr', 1, '3.20', '3.20'),
(160, 69, 'Mellisa Σπαγγέτι No6', 1, '1.18', '1.18'),
(161, 69, 'Mellisa Κανελόνια', 1, '2.80', '2.80'),
(162, 69, 'Mellisa Λαζάνια', 1, '3.00', '3.00'),
(163, 69, 'Mellisa Πένες Ριγέ', 1, '1.70', '1.70'),
(164, 69, 'NESTLE Έτοιμο Γεύμα Λαχανικά με Κοτόπουλο από 7 Μηνών Χωρίς γλουτένη 190gr', 1, '2.35', '2.35'),
(165, 69, 'NULICIOUS Παιδικό Γεύμα Μήλο Μπανάνα Βιολογικό Χωρίς προσθήκη ζάχαρης & αλατιού 150gr', 1, '3.20', '3.20'),
(166, 69, 'NULICIOUS Παιδικό Γεύμα Αχλάδι με Μπανάνα & Μάνγκο Βιολογικό Χωρίς προσθήκη ζάχαρης & αλατιού 150gr', 1, '3.20', '3.20'),
(167, 69, 'TROFINO Κολοκυθόσπορος Ψίχα Βιολογικός 200gr', 1, '2.86', '2.86'),
(168, 69, 'DIMFIL Bio Farma Φουντούκια Ψίχα Εισαγωγής Βιολογικά 200gr', 1, '4.55', '4.55'),
(169, 69, 'DIMFIL Bio Farma Αμύγδαλα Ψίχα Ωμά Βιολογικά Χωρίς αλάτι 200gr', 1, '4.96', '4.96'),
(170, 70, 'CRISTAL Ζάχαρη Λευκή Κρυσταλλική 1kg', 1, '0.77', '0.77'),
(171, 70, 'INTERMED Slim Fix Γλυκαντικό Υγρό με Στέβια 60ml', 1, '3.53', '3.53'),
(172, 70, 'ΙΟΝ Κακάο σε Σκόνη 125gr', 1, '2.00', '2.00'),
(173, 71, '3 ΑΛΦΑ Φασόλια Μαυρομάτικα Εισαγωγής 500gr', 1, '3.00', '3.00'),
(174, 71, 'ΑΡΟΣΙΣ Φακές Ψιλές Γρεβενών 500gr', 1, '2.58', '2.58'),
(175, 71, '3 ΑΛΦΑ Φασόλια Μέτρια Εισαγωγής 500gr', 1, '2.52', '2.52'),
(176, 71, '3 ΑΛΦΑ Φακές Ψιλές Εισαγωγής 500gr', 1, '2.20', '2.20'),
(177, 72, 'AXE Αποσμητικό Σπρει Black Vanilla 150ml', 1, '3.43', '3.43'),
(178, 72, 'SETTE Elements Κρεμοσάπουνο Γιασεμί 500ml', 1, '1.32', '1.32'),
(179, 72, 'DOVE Αφρόλουτρο Hydrate 450ml', 1, '4.60', '4.60'),
(180, 73, 'ΠΑΠΑΔΟΠΟΥΛΟΥ Digestive Bar Μπάρες Δημητριακών με Μαύρη Σοκολάτα Χωρίς προσθήκη ζάχαρης 5x28gr', 5, '3.28', '16.40'),
(181, 73, 'Misko Τριβελάκι', 3, '1.30', '3.90'),
(182, 74, 'CIF Κρέμα Καθαρισμού για Όλες τις Επιφάνειες Λεμόνι 500ml', 1, '2.88', '2.88'),
(183, 74, 'AJAX Boost Καθαριστικό Πατώματος Ξίδι & Μήλο 1lt', 1, '2.83', '2.83'),
(184, 74, 'KLINEX Ultra Χλωρίνη Λεμόνι 750ml', 1, '1.96', '1.96'),
(185, 74, 'DETTOL All In One Απολυμαντικό Σπρέι Crisp Linen 400ml', 1, '4.66', '4.66'),
(186, 75, 'Misko Σπαγγέτι Νο 6', 1, '1.22', '1.22'),
(187, 75, 'Mellisa Τορτελίνια 5 Τυριά', 1, '2.10', '2.10'),
(188, 76, 'ΕΒΙΒΑ Οινοποιία Ζαρογκίκα Λευκός Οίνος 1,5lt', 1, '3.20', '3.20'),
(189, 76, 'ΑΛΛΟΤΙΝΟ Ελληνικά Κελλάρια Ερυθρός Οίνος Ημίγλυκος 500ml', 1, '2.55', '2.55'),
(190, 76, 'DON SIMON Ερυθρός Οίνος Sangria 1lt', 1, '2.20', '2.20'),
(191, 77, 'Mellisa Πένες Ριγέ', 1, '1.70', '1.70'),
(192, 77, 'Misko Τριβελάκι', 1, '1.30', '1.30'),
(193, 77, 'Mellisa Τορτελίνια 5 Τυριά', 1, '2.10', '2.10'),
(194, 77, 'ΖΑΓΟΡΙ Φυσικό Μεταλλικό Νερό 1lt', 2, '1.25', '2.50'),
(195, 78, 'ΓΙΩΤΗΣ Φαρίνα Κόκκινη 500gr', 3, '1.23', '3.69'),
(199, 80, 'ΩΜΕΓΑ Ζάχαρη Άχνη 400gr', 3, '0.61', '1.83'),
(200, 80, 'Mellisa Σπαγγέτι No6', 2, '1.18', '2.36'),
(201, 80, 'Misko Σπαγγέτι Νο 6', 3, '1.22', '3.66'),
(202, 80, 'Misko Τριβελάκι', 1, '1.30', '1.30'),
(203, 80, 'Mellisa Πένες Ριγέ', 1, '1.70', '1.70'),
(204, 80, 'Mellisa Τορτελίνια 5 Τυριά', 2, '2.10', '4.20'),
(205, 81, 'Misko Τριβελάκι', 4, '1.30', '5.20'),
(206, 81, 'Misko Σπαγγέτι Νο 6', 3, '1.22', '3.66'),
(207, 82, 'CRISTAL Ζάχαρη Λευκή Κρυσταλλική 1kg', 2, '0.77', '1.54'),
(208, 83, 'CRISTAL Ζάχαρη Λευκή Κρυσταλλική 1kg', 2, '0.77', '1.54'),
(209, 84, 'Misko Σπαγγέτι Νο 6', 19, '1.22', '23.18'),
(210, 85, 'Misko Σπαγγέτι Νο 6', 4, '1.22', '4.88'),
(211, 86, 'DON SIMON Ερυθρός Οίνος Sangria 1lt', 6, '2.20', '13.20'),
(212, 87, 'Misko Σπαγγέτι Νο 6', 13, '1.22', '15.86'),
(213, 88, 'ΑΛΛΟΤΙΝΟ Ελληνικά Κελλάρια Ερυθρός Οίνος Ημίγλυκος 500ml', 4, '2.55', '10.20'),
(214, 89, 'ΑΛΛΟΤΙΝΟ Ελληνικά Κελλάρια Ερυθρός Οίνος Ημίγλυκος 500ml', 18, '2.55', '45.90'),
(215, 90, 'Mellisa Κανελόνια', 2, '2.80', '5.60'),
(216, 91, 'Misko Τριβελάκι', 2, '1.30', '2.60'),
(217, 92, 'Mellisa Τορτελίνια 5 Τυριά', 19, '2.10', '39.90'),
(218, 93, '3 ΑΛΦΑ Φακές Ψιλές Εισαγωγής 500gr', 3, '2.20', '6.60'),
(219, 94, 'ΩΜΕΓΑ Ζάχαρη Άχνη 400gr', 3, '0.61', '1.83'),
(220, 94, 'CRISTAL Ζάχαρη Λευκή Κρυσταλλική 1kg', 3, '0.77', '2.31'),
(221, 95, 'ΩΜΕΓΑ Ζάχαρη Άχνη 400gr', 13, '0.61', '7.93'),
(222, 96, 'Mellisa Σπαγγέτι No6', 55, '1.18', '64.90'),
(223, 96, 'Mellisa Τορτελίνια 5 Τυριά', 3, '2.10', '6.30'),
(224, 96, 'Mellisa Λαζάνια', 3, '3.00', '9.00'),
(225, 96, 'Mellisa Κανελόνια', 4, '2.80', '11.20'),
(226, 96, 'CRISTAL Ζάχαρη Λευκή Κρυσταλλική 1kg', 1, '0.77', '0.77'),
(227, 97, 'ΜΑΡΑΤΑ Αλεύρι για Όλες τις Χρήσεις 1kg', 5, '0.70', '3.50'),
(228, 98, 'Mellisa Σπαγγέτι No6', 4, '1.18', '4.72');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` mediumtext DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 20
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Άδειασμα δεδομένων του πίνακα `products`
--

INSERT INTO `products` (`id`, `name`, `image`, `price`, `description`, `category`, `stock`) VALUES
(1, 'Mellisa Σπαγγέτι No6', 'PHOTO/products/zymarika/mellisaSpaggetiNo6.jpg', '1.18', '', 'zymarika', 16),
(2, 'Mellisa Τορτελίνια 5 Τυριά', 'PHOTO/products/zymarika/mellisaTortlinh_5_tyria.jpg', '2.10', '', 'zymarika', 13),
(3, 'Mellisa Πένες Ριγέ', 'PHOTO/products/zymarika/mellisaPenesRige.jpg', '1.70', '', 'zymarika', 10),
(4, 'Mellisa Λαζάνια', 'PHOTO/products/zymarika/mellisaLazania.jpg', '3.00', '', 'zymarika', 15),
(5, 'Mellisa Κανελόνια', 'PHOTO/products/zymarika/mellisaKanelonia.jpg', '2.80', '', 'zymarika', 16),
(6, 'Misko Σπαγγέτι Νο 6', 'PHOTO/products/zymarika/miskoSpaggetiNo6.jpg', '1.22', '', 'zymarika', 0),
(7, 'Misko Τριβελάκι', 'PHOTO/products/zymarika/miskoTribelaki.jpg', '1.30', '', 'zymarika', 0),
(98, 'CRISTAL Ζάχαρη Λευκή Κρυσταλλική 1kg', 'PHOTO/products/zaxarh/CRISTALzaxarh.jpg', '0.77', '', 'zaxarh', 16),
(99, 'CRISTAL Ζάχαρη Καστανή 1kg', 'PHOTO/products/zaxarh/CRISTALzaxarhKastanh.jpg', '1.49', '', 'zaxarh', 0),
(93, 'ΜΥΛΟΙ ΑΓΙΟΥ ΓΕΩΡΓΙΟΥ Αλεύρι Ζυμωτό 1kg', 'PHOTO/products/aleyria/myloiAgioyGewrgioy.jpg', '1.64', '', 'aleyria', 19),
(100, 'INTERMED Slim Fix Γλυκαντικό Υγρό με Στέβια 60ml', 'PHOTO/products/zaxarh/INTERMEDygroStevia.jpg', '3.53', '', 'zaxarh', 20),
(92, 'ΓΙΩΤΗΣ Φαρίνα Κόκκινη 500gr', 'PHOTO/products/aleyria/farinaKokkinhGiotis.jpg', '1.23', '', 'aleyria', 19),
(90, 'ΜΑΡΑΤΑ Αλεύρι για Όλες τις Χρήσεις 1kg', 'PHOTO/products/aleyria/aleyriMarata.jpg', '0.70', '', 'aleyria', 14),
(91, 'ΑΛΛΑΤΙΝΗ Αλεύρι για Όλες τις Χρήσεις 2x1kg +1 Δώρο', 'PHOTO/products/aleyria/allatinh2+1.jpg', '4.00', '', 'aleyria', 19),
(101, 'ΩΜΕΓΑ Ζάχαρη Άχνη 400gr', 'PHOTO/products/zaxarh/WMEGAzaxarhAxnh.jpg', '0.61', '', 'zaxarh', 0),
(102, 'CANDEREL Γλυκαντικό σε δισκία 120τεμ (10,2gr)', 'PHOTO/products/zaxarh/CANDEREL.jpg', '2.84', '', 'zaxarh', 20),
(103, 'HEINZ Κέτσαπ 342gr', 'PHOTO/products/saltses/HEINZketsap.jpg', '2.11', '', 'saltses', 20),
(104, 'HELLMANN\'S Real Μαγιονέζα 450ml', 'PHOTO/products/saltses/HELLMANN\'Smagioneza.jpg', '3.35', '', 'saltses', 20),
(105, 'BARILLA Έτοιμη Σάλτσα Ζυμαρικών Pesto Alla Genovese Χωρίς γλουτένη 190gr', 'PHOTO/products/saltses/Pesto.jpg', '3.29', '', 'saltses', 20),
(106, 'MONKEY Σάλτσα Sweet Chilli 210gr', 'PHOTO/products/saltses/MONKEYsweetChilli.jpg', '2.31', '', 'saltses', 20),
(107, 'KALAMATA PAPADIMITRIOU Μουστάρδα με Μέλι Χωρίς γλουτένη 300gr', 'PHOTO/products/saltses/KALAMATAmoystardaMeli.jpg', '2.88', '', 'saltses', 20),
(108, 'ΚΑΛΑΣ Κλασικό Αλάτι Θαλασσινό 400gr', 'PHOTO/products/mpaxarika/KALAS.jpg', '1.82', '', 'mpaxarika', 20),
(109, 'ΚΟΡΩΝΙΣ Αλάτι Ψιλό 500gr', 'PHOTO/products/mpaxarika/KORWNISalati.jpg', '0.40', '', 'mpaxarika', 20),
(110, 'ΗΛΙΟΣ Σκόρδο τριμμένο 50gr', 'PHOTO/products/mpaxarika/HLIOSskordo.jpg', '2.22', '', 'mpaxarika', 20),
(111, 'ΗΛΙΟΣ Πιπέρι τριμμένο 40gr', 'PHOTO/products/mpaxarika/HLIOSpiperi.jpg', '1.80', '', 'mpaxarika', 20),
(112, 'BDL Τζίντζερ τριμμένο Βιολογικό 50gr', 'PHOTO/products/mpaxarika/BDLtzintzer.jpg', '2.08', '', 'mpaxarika', 20),
(113, 'KNORR Μείγμα Μυρωδικών για Σαλάτες με Άνηθο & Κρεμμύδι 5x9gr', 'PHOTO/products/mpaxarika/KNORRmeigmaMyrod.jpg', '1.99', '', 'mpaxarika', 20),
(114, 'ΗΛΙΟΣ Carino Μείγμα Μπαχαρικών για Κοτόπουλο 40gr', 'PHOTO/products/mpaxarika/HLIOScarino.jpg', '1.59', '', 'mpaxarika', 20),
(115, 'ΜΠΑΧΑΡΑΔΙΚΟ Κρεμμύδι Σκόνη 100gr', 'PHOTO/products/mpaxarika/MPAXARADIKOkremmydi.jpg', '1.23', '', 'mpaxarika', 20),
(116, 'ΦΙΝΟ Ξίδι Χωρίς γλουτένη 390ml', 'PHOTO/products/ladi/FINOksydi.jpg', '0.33', '', 'ladi', 20),
(117, 'ΤΟΠ Ξίδι Βαλσάμικο Χωρίς γλουτένη 250ml', 'PHOTO/products/ladi/TOPbalsamiko.jpg', '1.99', '', 'ladi', 20),
(118, 'MOLON LAVE Εξαιρετικό Παρθένο Ελαιόλαδο 4lt', 'PHOTO/products/ladi/MOLONLAVEelaiolado.jpg', '43.30', '', 'ladi', 20),
(119, 'ΜΙΝΕΡΒΑ Ηλιέλαιο 2lt', 'PHOTO/products/ladi/MINERBAhlielaio.jpg', '8.60', '', 'ladi', 20),
(120, 'ΒΟΡΕΙΟΣ ΜΥΛΟΠΟΤΑΜΟΣ Εξαιρετικό Παρθένο Ελαιόλαδο ΠΟΠ 1lt', 'PHOTO/products/ladi/MYLOPOTAMOSelaiolado.jpg', '12.50', '', 'ladi', 20),
(121, 'ΜΑΡΑΤΑ Αραβοσιτέλαιο 2lt', 'PHOTO/products/ladi/MARATAarabositelaio.jpg', '4.95', '', 'ladi', 20),
(122, 'AGRINO Bella Ρύζι Parboiled 500gr', 'PHOTO/products/ryzia/AGRINOpar.jpg', '2.67', '', 'ryzi', 20),
(123, 'AGRINO Exotic Ρύζι Basmati Ινδίας 500gr', 'PHOTO/products/ryzia/AGRINOexotic.jpg', '3.40', '', 'ryzi', 20),
(124, '3 ΑΛΦΑ Ρύζι Καρολίνα 500gr', 'PHOTO/products/ryzia/3ALFAkarolina.jpg', '2.24', '', 'ryzi', 20),
(125, 'BEN\'S ORIGINAL Ρύζι Μακρύκοκκο Parboiled 10\' σε μαγειρικό σακουλάκι 4x125gr', 'PHOTO/products/ryzia/BENSmakrykoko.jpg', '3.39', '', 'ryzi', 20),
(126, '3 ΑΛΦΑ Ρύζι Basmati Ινδίας 500gr', 'PHOTO/products/ryzia/3ALFAbasmati.jpg', '3.30', '', 'ryzi', 20),
(127, '3 ΑΛΦΑ Ρύζι Καστανό 500gr', 'PHOTO/products/ryzia/3ALFAkatsano.jpg', '2.57', '', 'ryzi', 20),
(128, 'TROFINO Ρύζι Κίτρινο Ελληνικό Βιολογικό 500gr', 'PHOTO/products/ryzia/TROFINOellhniko.jpg', '2.52', '', 'ryzi', 20),
(129, 'BALI Ρύζι Parboiled 500gr', 'PHOTO/products/ryzia/BALIpar.jpg', '3.98', '', 'ryzi', 20),
(130, 'AGRINO Exotic Ρύζι Jasmine Ταϋλάνδης 500gr', 'PHOTO/products/ryzia/AGRINOjasmine.jpg', '2.77', '', 'ryzi', 20),
(131, 'ΑΡΟΣΙΣ Φακές Ψιλές Γρεβενών 500gr', 'PHOTO/products/ospria/APOSISfakes.jpg', '2.58', '', 'ospria', 20),
(132, '3 ΑΛΦΑ Φακές Ψιλές Εισαγωγής 500gr', 'PHOTO/products/ospria/3ALFAfakes.jpg', '2.20', '', 'ospria', 20),
(133, '3 ΑΛΦΑ Φασόλια Μέτρια Εισαγωγής 500gr', 'PHOTO/products/ospria/3ALFAfasMetria.jpg', '2.52', '', 'ospria', 20),
(134, '3 ΑΛΦΑ Φασόλια Μαυρομάτικα Εισαγωγής 500gr', 'PHOTO/products/ospria/3ALFAfasMayromatika.jpg', '3.00', '', 'ospria', 20),
(135, 'PUMMARO Ντομάτα Πασσάτα Κλασικό 500gr', 'PHOTO/products/ntomatika/PUMMAROklasiko.jpg', '1.10', '', 'ntomatika', 20),
(136, 'KYKNOS Ντοματοπελτές Διπλής Συμπύκνωσης 70gr', 'PHOTO/products/ntomatika/KYKNOS1.jpg', '0.66', '', 'ntomatika', 20),
(137, 'KYKNOS Ντοματοπολτός Διπλής Συμπύκνωσης 70gr', 'PHOTO/products/ntomatika/KYKNOS2.jpg', '0.70', '', 'ntomatika', 20),
(138, 'KYKNOS Ντομάτα τριμμένη 500gr', 'PHOTO/products/ntomatika/KYKNOS3.jpg', '1.25', '', 'ntomatika', 20),
(139, 'PUMMARO Ντομάτα Πασσάτα Κλασική 3x250gr', 'PHOTO/products/ntomatika/PUMMAROklasikoX3.jpg', '2.10', '', 'ntomatika', 20),
(140, 'PUMMARO Ντοματάκι ψιλοκομμένο 400gr', 'PHOTO/products/ntomatika/PUMMAROpsilok.jpg', '1.30', '', 'ntomatika', 20),
(141, 'KYKNOS Ντοματοπολτός Διπλής Συμπύκνωσης 410gr', 'PHOTO/products/ntomatika/KYKNOS4.jpg', '2.60', '', 'ntomatika', 20),
(142, 'ΖΑΓΟΡΙ Φυσικό Μεταλλικό Νερό 5x1,5lt +1 Δώρο', 'PHOTO/products/nera/ZAGORI1,5.jpg', '2.18', '', 'nera', 20),
(143, 'ΖΑΓΟΡΙ Φυσικό Μεταλλικό Νερό 12x500ml', 'PHOTO/products/nera/ZAGORI0,5.jpg', '2.02', '', 'nera', 20),
(144, 'ΖΑΓΟΡΙ Φυσικό Μεταλλικό Νερό 500ml', 'PHOTO/products/nera/ZAGORI0,5_2.jpg', '0.18', '', 'nera', 20),
(145, 'ΖΑΓΟΡΙ Φυσικό Μεταλλικό Νερό 1lt', 'PHOTO/products/nera/ZAGORI1,0.jpg', '1.25', '', 'nera', 20),
(146, 'ΖΑΓΟΡΙ Φυσικό Μεταλλικό Νερό 1lt', 'PHOTO/products/nera/ZAGORIfn1.jpg', '0.44', '', 'nera', 20),
(147, 'ZARO\'S Φυσικό Μεταλλικό Νερό 6x1,5lt', 'PHOTO/products/nera/ZAROS1,5.jpg', '1.98', '', 'nera', 20),
(148, 'PERRIER Ανθρακούχο Νερό 330ml', 'PHOTO/products/nera/PERRIER2.jpg', '1.00', '', 'nera', 20),
(149, 'PERRIER Ανθρακούχο Νερό 4x330ml', 'PHOTO/products/nera/PERRIER.jpg', '3.95', '', 'nera', 20),
(150, 'COCA-COLA Zero Χωρίς ζάχαρη 6x330ml', 'PHOTO/products/anapsyktika/cocaZero330.jpg', '5.18', '', 'anapsyktika', 20),
(151, 'COCA-COLA Zero Χωρίς ζάχαρη 2x1,5lt', 'PHOTO/products/anapsyktika/cocaZero2.jpg', '3.90', '', 'anapsyktika', 20),
(152, 'COCA-COLA Original Taste 5x330ml +1 Δώρο', 'PHOTO/products/anapsyktika/cocaClassic330.jpg', '4.98', '', 'anapsyktika', 20),
(153, 'SCHWEPPES Πορτοκαλάδα με Ανθρακικό Χωρίς ζάχαρη 6x330ml', 'PHOTO/products/anapsyktika/schPort.jpg', '4.60', '', 'anapsyktika', 20),
(154, 'TUBORG Σόδα 5x330ml +1 Δώρο', 'PHOTO/products/anapsyktika/TUBORG.jpg', '2.90', '', 'anapsyktika', 20),
(155, 'SCHWEPPES Σόδα 6x330ml', 'PHOTO/products/anapsyktika/SCH.jpg', '2.98', '', 'anapsyktika', 20),
(156, 'BOLERO Ice Tea Ροδάκινο σε Σκόνη Χωρίς ζάχαρη 8gr', 'PHOTO/products/anapsyktika/BOLERO.jpg', '0.57', '', 'anapsyktika', 20),
(157, 'LIPTON Zero Ice Tea Ροδάκινο Χωρίς ζάχαρη 500ml', 'PHOTO/products/anapsyktika/LIPTON.jpg', '1.80', '', 'anapsyktika', 20),
(158, 'GREEN Πορτοκαλάδα με Ανθρακικό Χωρίς προσθήκη ζάχαρης 1,5lt', 'PHOTO/products/anapsyktika/GREENport.jpg', '1.40', '', 'anapsyktika', 20),
(159, 'SPRITE Γκαζόζα Lemon Lime 6x330ml', 'PHOTO/products/anapsyktika/SPRITE.jpeg', '4.40', '', 'anapsyktika', 20),
(160, 'AMITA Motion Χυμός Φυσικός 1lt', 'PHOTO/products/xymoi/MOTION1L.jpg', '2.40', '', 'xymoi', 20),
(161, 'AMITA Φρουτοποτό Πορτοκάλι Βερίκοκο Μήλο 1lt', 'PHOTO/products/xymoi/AMITA3F.jpg', '1.28', '', 'xymoi', 20),
(162, 'NUTRI VALLEY Peppa Pig Χυμός Φυσικός Πορτοκάλι Μήλο Ροδάκινο Βερίκοκο 250ml', 'PHOTO/products/xymoi/NV.jpg', '0.74', '', 'xymoi', 20),
(163, 'ΤΟ ΕΛΛΗΝΙΚΟ ΡΟΔΙ Χυμός Φυσικός Ρόδι 1lt', 'PHOTO/products/xymoi/RODI1L.jpg', '6.00', '', 'xymoi', 20),
(164, 'ΝΙΚΟΛΑΣ ΡΕΠΑΝΗΣ Λευκός Οίνος Μοσχοφίλερο ΠΓΕ 750ml', 'PHOTO/products/krasia/NIKREP.jpg', '7.00', '', 'krasi', 20),
(165, 'ΕΒΙΒΑ Οινοποιία Ζαρογκίκα Λευκός Οίνος 1,5lt', 'PHOTO/products/krasia/EBIBA.jpg', '3.20', '', 'krasi', 20),
(166, 'DON SIMON Ερυθρός Οίνος Sangria 1lt', 'PHOTO/products/krasia/SANGRIA.jpg', '2.20', '', 'krasi', 0),
(167, 'ΦΙΛΟΚΤΗΤΗΣ Ευάμπελος Γη Ροζέ Οίνος ΠΓΕ 750ml', 'PHOTO/products/krasia/FILOKTHTHS.jpg', '12.45', '', 'krasi', 20),
(168, 'ΑΛΛΟΤΙΝΟ Ελληνικά Κελλάρια Ερυθρός Οίνος Ημίγλυκος 500ml', 'PHOTO/products/krasia/ALLOTINO.jpg', '2.55', '', 'krasi', 18),
(169, 'ΑΛΦΑ Μπίρα Lager 4x500ml', 'PHOTO/products/mpira/ALFA.jpg', '5.80', '', 'mpira', 20),
(170, 'ΑΜΣΤΕΛ Μπίρα Lager 4x500ml', 'PHOTO/products/mpira/AMSTEL.jpg', '5.97', '', 'mpira', 20),
(171, 'FISCHER Μπίρα Pils 500ml', 'PHOTO/products/mpira/FISCHER500.jpg', '2.08', '', 'mpira', 20),
(172, 'KAISER Μπίρα Pilsner 6x330ml', 'PHOTO/products/mpira/KAISER.jpg', '8.10', '', 'mpira', 20),
(173, 'CORONA Extra Μπίρα Lager 330ml', 'PHOTO/products/mpira/CORONA.jpg', '2.70', '', 'mpira', 20),
(174, 'JOSE CUERVO Especial Τεκίλα Reposado 700ml', 'PHOTO/products/pota/tekila.jpg', '25.90', '', 'pota', 20),
(175, 'CHIVAS REGAL Ουίσκι 12ετών 700ml', 'PHOTO/products/pota/CHIVAS.jpg', '32.60', '', 'pota', 20),
(176, 'JOHNNIE WALKER Ουίσκι Black Label 700ml', 'PHOTO/products/pota/JOHNNIEBLACK.jpg', '32.00', '', 'pota', 20),
(177, 'HENDRICK\'S Τζιν 700ml', 'PHOTO/products/pota/HENDRICKS.jpg', '38.50', '', 'pota', 20),
(178, 'BELVEDERE Βότκα 700ml', 'PHOTO/products/pota/BELVE.jpg', '49.70', '', 'pota', 20),
(179, 'SERKOVA Crystal Pure Βότκα 700ml', 'PHOTO/products/pota/SERKOVA.jpg', '19.00', '', 'pota', 20),
(180, 'BACARDI Ρούμι 700ml', 'PHOTO/products/pota/BACARDI.jpg', '19.55', '', 'pota', 20),
(181, 'ΝΟΥΝΟΥ Family Γάλα Υψηλής Παστερίωσης Ελαφρύ 1,5lt', 'PHOTO/products/galata/NOYNOYEL.jpg', '2.72', '', 'gala', 20),
(182, 'ΝΟΥΝΟΥ Family Γάλα Υψηλής Παστερίωσης Πλήρες 1,5lt', 'PHOTO/products/galata/NOYNOYPL.jpg', '2.72', '', 'gala', 20),
(183, 'ΜΕΒΓΑΛ Κεφίρ 500ml', 'PHOTO/products/galata/MEBGALKEF.jpg', '1.35', '', 'gala', 20),
(184, 'ΟΛΥΜΠΟΣ Κρέμα Γάλακτος 12% Λιπαρά 2x200ml +1 Δώρο', 'PHOTO/products/kremesgal/OLYMPOSEL.jpg', '4.04', '', 'krema', 20),
(185, 'ADORO Per Pasta Κρέμα Γάλακτος 2x200ml +1 Δώρο', 'PHOTO/products/kremesgal/ADORO.jpg', '7.10', '', 'krema', 20),
(186, 'ROYAL Φυτική Κρέμα Μαγειρικής 500ml', 'PHOTO/products/kremesgal/ROYAL.jpg', '2.08', '', 'krema', 20),
(187, 'ΟΛΥΜΠΟΣ Κρέμα Γάλακτος 35% 2x200ml +1 Δώρο', 'PHOTO/products/kremesgal/OLYMPOS.jpg', '7.32', '', 'krema', 20),
(188, 'ΝΟΥΝΟΥ Γιαούρτι Στραγγιστό 1,5% 2x200gr +1 Δώρο', 'PHOTO/products/giaoyrtia/NOYNOYSTR.jpg', '5.11', '', 'giaoyrtia', 20),
(189, 'ΝΟΥΝΟΥ Γιαούρτι Στραγγιστό 5% 2x200gr +1 Δώρο', 'PHOTO/products/giaoyrtia/NOYNOYSTR2.jpg', '5.12', '', 'giaoyrtia', 20),
(190, 'ΡΟΔΟΠΗ Γιαούρτι Κατσικίσιο Παραδοσιακό 200g', 'PHOTO/products/giaoyrtia/RODOPH.jpg', '1.33', '', 'giaoyrtia', 20),
(191, 'ΜΕΒΓΑΛ Γιαούρτι Κατσικίσιο Παραδοσιακό 4% 220gr', 'PHOTO/products/giaoyrtia/MEBGAL.jpg', '1.40', '', 'giaoyrtia', 20),
(192, 'ΚΡΙ ΚΡΙ Kids Scooby Doo Επιδόρπιο Γιαουρτιού Μπισκότο με Σοκομπιλάκια 3x150gr', 'PHOTO/products/bGiaoyrtia/KRIKRIscoo.jpg', '3.84', '', 'gBaby', 20),
(193, 'ΜΕΒΓΑΛ Frutomaniacs Επιδόρπιο Γιαουρτιού Φράουλα με Σοκολατομπαλίτσες Ίον 3x140gr', 'PHOTO/products/bGiaoyrtia/MEBGAL.jpeg', '3.87', '', 'gBaby', 20),
(194, 'NESTLE Kit Kat Mix-in Επιδόρπιο Γιαουρτιού Βανίλια με Pop Choc 115gr', 'PHOTO/products/bGiaoyrtia/KITKAT.jpg', '1.16', '', 'gBaby', 20),
(195, 'Λευκό Τυρί ΡΟΔΟΠΗ 400gr', 'PHOTO/products/leykaT/RODOPHlt.jpg', '2.76', '', 'leykoTyri', 20),
(196, 'Φέτα ΗΠΕΙΡΟΣ ΠΟΠ σε Άλμη Χωρίς λακτόζη 400gr', 'PHOTO/products/leykaT/HPEIROSfeta.jpg', '6.28', '', 'leykoTyri', 20),
(197, 'Βαρελίσια Φέτα ΣΥΝΕΤΑΙΡΙΣΜΟΣ ΚΑΛΑΒΡΥΤΩΝ ΠΟΠ', 'PHOTO/products/leykaT/fetaKALABRYTWN.jpg', '6.15', '', 'leykoTyri', 20),
(198, 'Τυρί ΗΠΕΙΡΟΣ Ελαφρύ 180gr', 'PHOTO/products/leykaT/HPEIROSel.jpg', '3.14', '', 'leykoTyri', 20),
(199, 'Βαρελίσια Φέτα ΚΑΡΑΛΗΣ Ηπείρου ΠΟΠ 1kg', 'PHOTO/products/leykaT/fetaKARALHS.jpg', '12.60', '', 'leykoTyri', 20),
(200, 'Κεφαλοτύρι ΟΛΥΜΠΟΣ Αιγοπρόβειο 250gr', 'PHOTO/products/kitrinoT/OLYMPOS.jpg', '5.28', '', 'kitrinoTyri', 20),
(201, 'Γραβιέρα ΧΩΡΙΟ 7μηνης Ωρίμανσης 250gr', 'PHOTO/products/kitrinoT/XWRIO.jpg', '5.72', '', 'kitrinoTyri', 20),
(202, 'Γραβιέρα ΚΑΡΑΛΗΣ Βιολογική 200gr', 'PHOTO/products/kitrinoT/KARALHS.jpg', '3.95', '', 'kitrinoTyri', 20),
(203, 'Σκληρό Τυρί VERGEER 12μηνης Ωρίμανσης 200gr', 'PHOTO/products/kitrinoT/VERGEER.jpg', '3.33', '', 'kitrinoTyri', 20),
(204, 'Αυγά ΤΣΑΟΥΣΗ Μεσαία 15τεμ 53-63gr', 'PHOTO/products/ayga/TSAOYSH15.jpg', '3.05', '', 'ayga', 20),
(205, 'Αυγά ΤΣΑΟΥΣΗ Μεσαία 30τεμ 53-63gr', 'PHOTO/products/ayga/TSAOYSH30.jpg', '6.00', '', 'ayga', 20),
(206, 'Αυγά Ελευθέρας Βοσκής ΠΙΝΔΟΣ Ηπειρώτικα Μεγάλα 6τεμ 63gr-73gr', 'PHOTO/products/ayga/PINDOS6.jpg', '4.40', '', 'ayga', 20),
(207, 'Ασπράδι Αυγού ΒΛΑΧΑΚΗ Παστεριωμένο 500ml', 'PHOTO/products/ayga/BLAXAKHaspradi.jpg', '3.87', '', 'ayga', 20),
(208, 'ΟΛΥΜΠΟΣ Βούτυρο Αγελάδος 250gr', 'PHOTO/products/boytyro/OLYMPOS.jpg', '3.98', '', 'boytyro', 20),
(209, 'LURPAK Βούτυρο Ανάλατo 250gr', 'PHOTO/products/boytyro/LURPAK.jpg', '4.44', '', 'boytyro', 20),
(210, 'ADORO Βούτυρο 250gr', 'PHOTO/products/boytyro/ADORO.jpg', '4.18', '', 'boytyro', 20),
(211, 'SUPER FRESCO Μαργαρίνη 200gr', 'PHOTO/products/boytyro/SUPERFRESCO.jpg', '0.90', '', 'boytyro', 20),
(212, 'ΦΑΣΤ Classic Μαργαρίνη 225gr', 'PHOTO/products/boytyro/FASTC.jpg', '1.36', '', 'boytyro', 20),
(213, 'ΦΑΣΤ Soft Μαργαρίνη 220gr', 'PHOTO/products/boytyro/FAST.jpg', '1.55', '', 'boytyro', 20),
(214, 'KANAKI Φρέσκο Φύλλο Σφολιάτας 700gr', 'PHOTO/products/zymes/KANAKI.jpg', '4.90', '', 'nwpesZymes', 20),
(215, 'ΓΙΑΝΝΙΩΤΗ Φύλλο Χωριάτικο 500gr', 'PHOTO/products/zymes/GIANNIWTH.jpg', '3.44', '', 'nwpesZymes', 20),
(216, 'POP BAKERY Ζύμη για Κρουασάν 240gr', 'PHOTO/products/zymes/POPBAKERY.jpg', '3.18', '', 'nwpesZymes', 20),
(217, 'POP BAKERY Ζύμη για Πίτσα Αφράτη 400gr', 'PHOTO/products/zymes/POPBAKERYPIZZA.jpg', '3.60', '', 'nwpesZymes', 20),
(218, 'Κοτόπουλο ΠΙΝΔΟΣ ολόκληρο Βιολογικό', 'PHOTO/products/kotopoylo/PINDOSBIO.jpg', '26.95', '', 'kotopoylo', 20),
(219, 'Κοτόπουλο ΠΙΝΔΟΣ μισό Βιολογικό', 'PHOTO/products/kotopoylo/MISOPINDOSBIO.jpg', '12.86', '', 'kotopoylo', 20),
(220, 'Κοτόπουλο Μπούτι ΝΙΤΣΙΑΚΟΣ Ελληνικό 850gr +50% Δώρο', 'PHOTO/products/kotopoylo/NISTIAKOS.jpg', '7.60', '', 'kotopoylo', 20),
(221, 'Κοτόπουλο Στήθος MIMIKOS φιλέτο 650gr +20% Δώρο', 'PHOTO/products/kotopoylo/MIMIKOS.jpg', '11.50', '', 'kotopoylo', 20),
(222, 'Μπριζόλα Ribeye Βόεια DEVESA άνευ οστού Αργεντινής 240gr', 'PHOTO/products/mosxari/DEVESA.jpg', '12.40', '', 'mosxari', 20),
(223, 'Σπάλα Μόσχου Α/Ο Γαλλίας', 'PHOTO/products/mosxari/SPALA.jpg', '11.95', '', 'mosxari', 20),
(224, 'Βόειο Μπούτι Α/Ο Γαλλίας', 'PHOTO/products/mosxari/MPOYTI.jpg', '13.60', '', 'mosxari', 20),
(225, 'Μπούτι Κατσικίσιο Γάλακτος με Παϊδάκι ΦΑΡΜΑ ΕΛΑΣΣΟΝΑΣ Ελληνικό', 'PHOTO/products/arni/KATSIKI.jpg', '18.15', '', 'arni', 20),
(226, 'Μπούτι Αρνί Γάλακτος με Παϊδάκι ΦΑΡΜΑ ΕΛΑΣΣΟΝΑΣ Ελληνικό', 'PHOTO/products/arni/ARNI.jpg', '16.38', '', 'arni', 20),
(227, 'Χοιρινή Μπριζόλα Μ/Ο Εισαγωγής', 'PHOTO/products/xoirino/MPRIZOLA.jpg', '7.10', '', 'xoirino', 20),
(228, 'Χοιρινός Λαιμός Μ/Ο Εισαγωγής', 'PHOTO/products/xoirino/LAIMOS.jpg', '6.90', '', 'xoirino', 20),
(229, 'Χοιρινό Κότσι Νωπό Μ/Ο Α/Δ Εισαγωγής', 'PHOTO/products/xoirino/KOTSI.jpg', '5.30', '', 'xoirino', 20),
(230, 'Χοιρινή Πανσέτα Μ/Ο Ελληνική', 'PHOTO/products/xoirino/PANSETA.jpg', '7.20', '', 'xoirino', 20),
(231, 'ALLANTON Καπνιστή Γαλοπoύλα Χωρίς γλουτένη 300gr', 'PHOTO/products/allantikaGalKot/ALLANTONGAL.jpg', '2.22', '', 'galKot', 20),
(232, 'IFANTIS Καπνιστή Γαλοπούλα Φιλέτο σε φέτες 200gr', 'PHOTO/products/allantikaGalKot/IFANTISGAL.jpg', '3.28', '', 'galKot', 20),
(233, 'ΑΓΡΟΚΤΗΜΑ ΑΝΑΒΡΑ Καπνιστή Γαλοπούλα Φιλέτο Στήθος 150gr', 'PHOTO/products/allantikaGalKot/AGROKTHMAGAL.jpg', '3.72', '', 'galKot', 20),
(234, 'TOSTAKI Κοτόπουλο Ψητό σε Φέτες Χωρίς γλουτένη 160gr', 'PHOTO/products/allantikaGalKot/TOSTAKIKOT.jpg', '2.26', '', 'galKot', 20),
(235, 'NIKAS Νιτσιάκος Ψητό Κοτόπουλο σε φέτες Χωρίς γλουτένη 200gr', 'PHOTO/products/allantikaGalKot/NIKASKOT.jpg', '3.40', '', 'galKot', 20),
(236, 'CRETA FARMS Εν Ελλάδι Κοτόπουλο Ψητό Τοστ Χωρίς γλουτένη 300gr', 'PHOTO/products/allantikaGalKot/CFARMS.jpg', '3.85', '', 'galKot', 20),
(237, 'IFANTIS Τα Κoτόπουλα της Μάρως Ψητό Κοτόπουλο σε φέτες Χωρίς γλουτένη 160gr', 'PHOTO/products/allantikaGalKot/IFANTISMARW.jpg', '2.86', '', 'galKot', 20),
(238, 'CRETA FARMS Χωρίς Ζαμπόν Καπνιστό σε φέτες Χωρίς γλουτένη 200gr', 'PHOTO/products/allantikaZampon/CFARM.jpg', '4.25', '', 'zampon', 20),
(239, 'BUENAS Ζαμπόν σε φέτες Χωρίς γλουτένη 300gr', 'PHOTO/products/allantikaZampon/BUENAS.jpg', '1.95', '', 'zampon', 20),
(240, 'IFANTIS Ferrano Ζαμπόν σε φέτες Χωρίς γλουτένη 70gr', 'PHOTO/products/allantikaZampon/IFANTIS.jpg', '1.76', '', 'zampon', 20),
(241, 'NIKAS Μπέικον Χωρίς γλουτένη 150gr', 'PHOTO/products/allantikaMpeikon/NIKAS.jpg', '2.30', '', 'mpeikon', 20),
(242, 'IFANTIS Καπνιστό Μπέικον 300gr', 'PHOTO/products/allantikaMpeikon/IFANTIS.jpg', '4.32', '', 'mpeikon', 20),
(243, 'IFANTIS Καπνιστό Μπέικον Χωρίς γλουτένη 100gr', 'PHOTO/products/allantikaMpeikon/IFANTISXG.jpg', '2.40', '', 'mpeikon', 20),
(244, 'BUENAS Σαλάμι Αέρος σε φέτες Χωρίς γλουτένη 200gr', 'PHOTO/products/salamia/BUENAS.jpg', '1.98', '', 'salamia', 20),
(245, 'ΝΤΕΛΗΜΑΡΗ Σαλάμι Αέρος Λευκάδος 225gr', 'PHOTO/products/salamia/NTELHMARH.jpg', '4.56', '', 'salamia', 20),
(246, 'NIKAS Σαλάμι Αέρος Πικάντικο Χωρίς γλουτένη 165gr', 'PHOTO/products/salamia/NIKAS.jpg', '3.20', '', 'salamia', 20),
(247, 'IFANTIS Βραστό Σαλάμι Σκορδάτο Χωρίς γλουτένη 330gr', 'PHOTO/products/salamia/IFANTIS.jpg', '3.55', '', 'salamia', 20),
(248, 'ΜΑΔΕΡΑΚΗΣ Λουκάνικα τύπου Φρανκφούρτης 270gr', 'PHOTO/products/loykanika/MADERAKHS.jpg', '1.66', '', 'loykanika', 20),
(249, 'ΜΑΡΑΤΑ Λουκάνικα Χοιρινά Πικάντικα Χωρίς γλουτένη 360gr', 'PHOTO/products/loykanika/MARATA.jpg', '2.68', '', 'loykanika', 20),
(250, 'TOSTAKI Λουκάνικα τύπου Φρανκφούρτης Χωρίς γλουτένη 270gr', 'PHOTO/products/loykanika/TOSTAKI.jpg', '2.98', '', 'loykanika', 20),
(251, 'ΠΑΤΕΡΑΚΗΣ FAMILY Λουκάνικα με Πράσο Χωρίς γλουτένη 280gr', 'PHOTO/products/loykanika/PATERAKHS.jpg', '2.58', '', 'loykanika', 20),
(252, 'Τσιπούρα Καθαρισμένη Ελληνικής Εκτροφής 400gr έως 600gr', 'PHOTO/products/psaria/TSIPOYRA.jpg', '5.20', '', 'psaria', 20),
(253, 'Λαβράκι Καθαρισμένο Ελληνικής Εκτροφής 400gr έως 600gr', 'PHOTO/products/psaria/LABRAKI.jpg', '4.69', '', 'psaria', 20),
(254, 'Φαγκρί Καθαρισμένο Ελληνικής Eκτρoφής 400gr έως 600gr', 'PHOTO/products/psaria/FAGKRI.jpg', '7.80', '', 'psaria', 20),
(255, 'Τσιπούρα Φιλέτο Ελληνικής Εκτροφής 200gr', 'PHOTO/products/psaria/FILTSIPOYRA.jpg', '5.27', '', 'psaria', 20),
(256, 'Σολομός Φιλέτο με δέρμα Εκτροφής Νορβηγίας', 'PHOTO/products/psaria/SOLOMOS.jpeg', '7.31', '', 'psaria', 20),
(257, 'Θράψαλo Αποψυγμένo Εισαγωγής', 'PHOTO/products/malakia/THRAPSALO.jpg', '3.77', '', 'malakia', 20),
(258, 'Γαρίδες Vannamei Αποψυγμένες Εισαγωγής', 'PHOTO/products/ostrakoeidh/VANNAMEI.jpg', '4.16', '', 'ostrakoeidh', 20),
(259, 'OREO Μπισκότα Γεμιστά Original 3x154gr', 'PHOTO/products/mpiskota/OREOx3.jpg', '4.44', '', 'mpiskota', 20),
(260, 'OREO Μπισκότα Γεμιστά Original 154gr', 'PHOTO/products/mpiskota/OREO.jpg', '1.48', '', 'mpiskota', 20),
(261, 'ΠΑΠΑΔΟΠΟΥΛΟΥ Μιράντα Μπισκότα 250gr', 'PHOTO/products/mpiskota/PAPADOPOYLOY1.jpg', '1.50', '', 'mpiskota', 20),
(262, 'ΑΛΛΑΤΙΝΗ Soft Kings Μπισκότα Dark Chocolate Chunks 160gr', 'PHOTO/products/mpiskota/ALLATINH.jpg', '1.99', '', 'mpiskota', 20),
(263, 'KINDER Cards 128gr', 'PHOTO/products/mpiskota/KINDER.jpg', '2.70', '', 'mpiskota', 20),
(264, 'ΑΛΛΑΤΙΝΗ Cookie Μπισκότα με Κομμάτια Σοκολάτας 175gr', 'PHOTO/products/mpiskota/COOKIE.jpg', '1.55', '', 'mpiskota', 20),
(265, 'OREO Mini Μπισκότα Γεμιστά με Κρέμα Βανίλια 115gr', 'PHOTO/products/mpiskota/OREOmini.jpg', '1.88', '', 'mpiskota', 20),
(266, 'LACTA Nuts Σοκολάτα Γάλακτος με Ολόκληρο Αμύγδαλο 100gr', 'PHOTO/products/sokolata/LACTANUTS.jpg', '1.94', '', 'sokolata', 20),
(267, 'ΙΟΝ Γάλακτος Σοκολάτα 100gr', 'PHOTO/products/sokolata/ION100GR.jpg', '1.88', '', 'sokolata', 20),
(268, 'MALTESERS Teasers Μπάρα Σοκολάτας 35gr', 'PHOTO/products/sokolata/MALTESERS35GR.jpg', '1.10', '', 'sokolata', 20),
(269, 'ΙΟΝ Σοκοφρέτα Γκοφρέτα Σοκολάτας Γάλακτος με Φουντούκια 38gr', 'PHOTO/products/sokolata/IONSOKOF.jpg', '0.55', '', 'sokolata', 20),
(270, 'LAY\'S Πατατάκια με Αλάτι 150gr', 'PHOTO/products/snaks/LAYSALATI.jpg', '1.95', '', 'snaks', 20),
(271, 'LAY\'S Πατατάκια με Ρίγανη 150gr', 'PHOTO/products/snaks/LAYSRIGANH.jpg', '1.95', '', 'snaks', 20),
(272, 'LAY\'S Στο Φούρνο Πατατάκια Barbeque 105gr', 'PHOTO/products/snaks/LAYSBBQ.jpg', '1.85', '', 'snaks', 20),
(273, 'CHEETOS Σνακ Πακοτίνια Τυρί 125gr', 'PHOTO/products/snaks/CHEETOSTYRI.jpg', '1.50', '', 'snaks', 20),
(274, 'CHEETOS Σνακ Δρακουλίνια με γεύση Τυρί & Ντομάτα 100gr', 'PHOTO/products/snaks/CHEETOSDRAK.jpg', '1.50', '', 'snaks', 20),
(275, 'PRINGLES Σνακ Sour Cream & Onion 165gr', 'PHOTO/products/snaks/PRINGLES.jpg', '3.33', '', 'snaks', 20),
(276, 'GULLON Mini Mix Κράκερ 350gr', 'PHOTO/products/snaks/GULLON.jpg', '1.52', '', 'snaks', 20),
(277, 'DIMFIL Bio Farma Αμύγδαλα Ψίχα Ωμά Βιολογικά Χωρίς αλάτι 200gr', 'PHOTO/products/kshroiKarpoi/DIMFIL.jpg', '4.96', '', 'kshroiKarpoi', 20),
(278, 'SNUTS Salad Mate Ξηροί Καρποί για Πράσινη Σαλάτα 105gr', 'PHOTO/products/kshroiKarpoi/SNUTSMATE.jpg', '1.58', '', 'kshroiKarpoi', 20),
(279, 'TROFINO Ηλιόσπορος Ψίχα Βιολογικός 250gr', 'PHOTO/products/kshroiKarpoi/TROFINO.jpg', '1.68', '', 'kshroiKarpoi', 20),
(280, 'TROFINO Κολοκυθόσπορος Ψίχα Βιολογικός 200gr', 'PHOTO/products/kshroiKarpoi/TROFINOKOL.jpg', '2.86', '', 'kshroiKarpoi', 20),
(281, 'DIMFIL Bio Farma Φουντούκια Ψίχα Εισαγωγής Βιολογικά 200gr', 'PHOTO/products/kshroiKarpoi/DIMFILFOYNT.jpg', '4.55', '', 'kshroiKarpoi', 20),
(282, 'MILLHOUSE Νιφάδες Βρόμης 500gr', 'PHOTO/products/dhmhtriaka/MILLHOUSE.jpg', '1.41', '', 'dhmhtriaka', 20),
(283, 'QUAKER Τραγάνες Μπουκιές Δημητριακών με Βρόμη & Σοκολάτα 600gr', 'PHOTO/products/dhmhtriaka/QUAKER.jpg', '6.32', '', 'dhmhtriaka', 20),
(284, 'KELLOGG\'S Coco Pops Δημητριακά Chocos 550gr', 'PHOTO/products/dhmhtriaka/KELLOGGS.jpg', '5.68', '', 'dhmhtriaka', 20),
(285, 'NATURE VALLEY Crunchy Μπάρες Δημητριακών με Βρόμη & Μέλι 5x42gr', 'PHOTO/products/dhmhtriaka/VALLEY.jpg', '3.48', '', 'dhmhtriaka', 20),
(286, 'NESTLE Fitness Δημητριακά Ολικής Άλεσης με Σκούρα Σοκολάτα 375gr', 'PHOTO/products/dhmhtriaka/NESTLE.jpg', '5.28', '', 'dhmhtriaka', 20),
(287, 'ΙΟΝ Protein Μπάρα Σοκολάτας με Φουντούκι Χωρίς προσθήκη ζάχαρης με Στέβια 50gr', 'PHOTO/products/dhmhtriaka/IONPROTEIN.jpg', '1.28', '', 'dhmhtriaka', 20),
(288, 'ΠΑΠΑΔΟΠΟΥΛΟΥ Digestive Bar Μπάρες Δημητριακών με Μαύρη Σοκολάτα Χωρίς προσθήκη ζάχαρης 5x28gr', 'PHOTO/products/dhmhtriaka/PAPX5.jpg', '3.28', '', 'dhmhtriaka', 20),
(289, 'NESCAFE Azera Καφές Στιγμιαίος Freddo Espresso 100% Arabica 95gr', 'PHOTO/products/rofhmata/NESAZERA.jpg', '6.70', '', 'rofhmata', 20),
(290, 'NESCAFE Classic Καφές Στιγμιαίος 200gr', 'PHOTO/products/rofhmata/NESCLASSIC.jpg', '8.27', '', 'rofhmata', 20),
(291, 'ΛΟΥΜΙΔΗΣ ΠΑΠΑΓΑΛΟΣ Παραδοσιακός Καφές Ελληνικός 340gr', 'PHOTO/products/rofhmata/LOYMIDHS.jpg', '6.30', '', 'rofhmata', 20),
(292, 'ΙΟΝ Κακάο σε Σκόνη 125gr', 'PHOTO/products/rofhmata/IONKAKAO.jpg', '2.00', '', 'rofhmata', 20),
(293, 'LIPTON Yellow Label Μαύρο Τσάι 20 φακελάκια x1,5gr', 'PHOTO/products/rofhmata/LIPTON.jpg', '2.14', '', 'rofhmata', 20),
(294, 'FINO Τσάι Βουνού 10 φακελάκια x 0,8gr', 'PHOTO/products/rofhmata/FINOBOYNOY.jpg', '1.06', '', 'rofhmata', 20),
(295, 'NESTLE Nan Optipro 2 Γάλα 2ης Βρεφικής Ηλικίας από τον 6ο Μήνα σε σκόνη 800gr', 'PHOTO/products/babyGala/NAN2.jpg', '18.80', '', 'babyGala', 20),
(296, 'NESTLE Nan Optipro 1 Γάλα για Βρέφη από τη Γέννηση σε σκόνη 800gr', 'PHOTO/products/babyGala/NAN1.jpg', '22.40', '', 'babyGala', 20),
(297, 'NUTRICIA Almiron 1 Γάλα 1ης Βρεφικής Ηλικίας 0-6 Μηνών σε σκόνη χωρίς φοινικέλαιο 800gr', 'PHOTO/products/babyGala/NUTRICIA.jpg', '24.90', '', 'babyGala', 20),
(298, 'ΓΙΩΤΗΣ Sanilac 2 Γάλα 2ης Βρεφικής Ηλικίας 6+ Μηνών σε σκόνη 400gr', 'PHOTO/products/babyGala/GIWTHS.jpg', '10.00', '', 'babyGala', 20),
(299, 'NESTLE Έτοιμο Γεύμα Λαχανικά με Μοσχάρι από 6 Μηνών Χωρίς γλουτένη 190gr', 'PHOTO/products/babyFoods/NESTLELAXMOS.jpg', '2.35', '', 'babyFoods', 20),
(300, 'NESTLE Έτοιμο Γεύμα Λαχανικά με Κοτόπουλο από 7 Μηνών Χωρίς γλουτένη 190gr', 'PHOTO/products/babyFoods/NESTLELAXKOT.jpg', '2.35', '', 'babyFoods', 20),
(301, 'NULICIOUS Παιδικό Γεύμα Μήλο Μπανάνα Βιολογικό Χωρίς προσθήκη ζάχαρης & αλατιού 150gr', 'PHOTO/products/babyFoods/NULICIUS.jpg', '3.20', '', 'babyFoods', 20),
(302, 'NULICIOUS Παιδικό Γεύμα Αχλάδι με Μπανάνα & Μάνγκο Βιολογικό Χωρίς προσθήκη ζάχαρης & αλατιού 150gr', 'PHOTO/products/babyFoods/NULICIOUS.jpg', '3.20', '', 'babyFoods', 20),
(303, 'ΓΛΑΡΟΣ Χαρτί Κουζίνας Επαγγελματικό 800gr', 'PHOTO/products/xartikaa/GLAROSKOYZ.jpg', '2.50', '', 'xartika', 20),
(304, 'ΓΛΑΡΟΣ Χαρτοπετσέτες 30x30cm 110τεμ 190gr', 'PHOTO/products/xartikaa/GLAROSXART.jpg', '0.78', '', 'xartika', 20),
(305, 'ΓΛΑΡΟΣ Χαρτί Υγείας Μίνι 2 Φύλλων 10τεμ 750gr', 'PHOTO/products/xartikaa/GLAROSYGEIAS.jpg', '2.48', '', 'xartika', 20),
(306, 'ZEWA Deluxe Χαρτομάντιλα Γραφείου Design 3 Φύλλων 90τεμ 162gr', 'PHOTO/products/xartikaa/ZEWA.jpg', '3.65', '', 'xartika', 20),
(307, 'SETTE Comfort Υποσέντονα 60x90cm 15τεμ', 'PHOTO/products/enhlikwn/SETTE.jpg', '3.60', '', 'panesEnhlikwn', 20),
(308, 'ALWAYS Daily to Go Σερβιετάκια Normal Fresh Scent 20τεμ', 'PHOTO/products/enhlikwn/ALWAYS.jpg', '2.78', '', 'panesEnhlikwn', 20),
(309, 'ALWAYS Ultra Σερβιέτες No2 Long 36τεμ', 'PHOTO/products/enhlikwn/ALWAYSULTRA.jpg', '8.00', '', 'panesEnhlikwn', 20),
(310, 'EVERYDAY Hyperdry Σερβιέτες Ultra Plus Maxi Night 18τεμ', 'PHOTO/products/enhlikwn/EVERYDAY.jpg', '4.28', '', 'panesEnhlikwn', 20),
(311, 'SETTE Comfort Pants Εσώρουχα Ακράτειας Νο3 Large 14τεμ', 'PHOTO/products/enhlikwn/SETTEPANTS.jpg', '6.91', '', 'panesEnhlikwn', 20),
(312, 'SANI Sensitive Pants Εσώρουχα Ακράτειας Νο2 Medium 24τεμ', 'PHOTO/products/enhlikwn/SANIPANTS.jpg', '25.34', '', 'panesEnhlikwn', 20),
(313, 'BABYLINO Sensitive Pants Πάνες Βρακάκι Cotton Soft Νο5 10-16kg 34τεμ', 'PHOTO/products/panesBrefikes/BABYLINO1.jpg', '17.68', '', 'panesBrefikes', 20),
(314, 'BABYLINO Sensitive Πάνες Cotton Soft Νο4 Maxi 8-13kg 50τεμ', 'PHOTO/products/panesBrefikes/BABYLINO2.jpg', '20.00', '', 'panesBrefikes', 20),
(315, 'PAMPERS Pants Πάνες Βρακάκι Νο5 12-17kg 56τεμ', 'PHOTO/products/panesBrefikes/PAMPERS1.jpg', '28.37', '', 'panesBrefikes', 20),
(316, 'PAMPERS Μωρομάντιλα Fresh Clean 4x52τεμ', 'PHOTO/products/panesBrefikes/PAMPERSFRESHCLEAN.jpg', '4.18', '', 'panesBrefikes', 20),
(317, 'BABYCARE Μωρομάντιλα Sensitive Plus 2x54τεμ +1 Δώρο', 'PHOTO/products/panesBrefikes/BABYCARE.jpg', '2.20', '', 'panesBrefikes', 20),
(318, 'SEPTONA Baby Μωρομάντιλα Calm n\' Care Aloe Vera 2x60τεμ +1 Δώρο', 'PHOTO/products/panesBrefikes/SEPTONA.jpg', '2.26', '', 'panesBrefikes', 20),
(319, 'SYOSS Men Power Σαμπουάν για Κανονικά Μαλλιά 440ml', 'PHOTO/products/malliwn/SYOSSMEN.jpg', '4.60', '', 'malliwn', 20),
(320, 'SYOSS Keratin Σαμπουάν για Αδύναμα Μαλλιά που Σπάνε 440ml', 'PHOTO/products/malliwn/SYOSSKERAT.jpg', '4.60', '', 'malliwn', 20),
(321, 'PANTENE Repair & Protect Κρέμα Conditioner για Αδύναμα & Ταλαιπωρημένα Μαλλιά 500ml', 'PHOTO/products/malliwn/PANTENE.jpg', '8.88', '', 'malliwn', 20),
(322, 'TRESEMME Biotin Repair Κρέμα Conditioner για Ταλαιπωρημένα Μαλλιά 400ml', 'PHOTO/products/malliwn/TRESEMME.jpg', '5.00', '', 'malliwn', 20),
(323, 'PANTENE Repair & Protect Μάσκα για Αδύναμα & Ταλαιπωρημένα Μαλλιά 300ml', 'PHOTO/products/malliwn/PANTENEMASKA.jpg', '9.99', '', 'malliwn', 20),
(324, 'ELVIVE Extraordinary Oil Λάδι για Ξηρά Μαλλιά 100ml', 'PHOTO/products/malliwn/ELVIVEOIL.jpg', '10.26', '', 'malliwn', 20),
(325, 'SYOSS Max Hold Λακ Μαλλιών για Μέγα Δυνατό Κράτημα 400ml', 'PHOTO/products/malliwn/SYOSSLAK.jpg', '7.50', '', 'malliwn', 20),
(326, 'WELLA Wellaflex Invisible Hold Λακ Μαλλιών \"Εξτρα Δυνατό Κράτημα Vegan 75ml', 'PHOTO/products/malliwn/WELLALAK.jpg', '2.95', '', 'malliwn', 20),
(327, 'FLIPPER Ζελέ Μαλλιών Extra Strong 250ml', 'PHOTO/products/malliwn/FLIPPER.jpg', '2.50', '', 'malliwn', 20),
(328, 'SYOSS Max Hold Ζελέ Μαλλιών για Έξτρα Δυνατό Κράτημα 250ml', 'PHOTO/products/malliwn/SYOSSGEL.jpg', '7.14', '', 'malliwn', 20),
(329, 'DOVE Αφρόλουτρο Hydrate 450ml', 'PHOTO/products/swma/DOVEH.jpg', '4.60', '', 'swma', 20),
(330, 'PALMOLIVE Thermal Spa Αφρόλουτρο Smooth Butter 650ml', 'PHOTO/products/swma/PALMOLIVE.jpg', '4.78', '', 'swma', 20),
(331, 'SETTE Elements Κρεμοσάπουνο Γιασεμί 500ml', 'PHOTO/products/swma/SETTEKREMOS.jpg', '1.32', '', 'swma', 20),
(332, 'STR8 Αποσμητικό Σπρέι Original 150ml', 'PHOTO/products/swma/STR8.jpg', '5.18', '', 'swma', 20),
(333, 'STR8 Game Σετ Δώρου Grooming Pack', 'PHOTO/products/swma/STR8SET.jpg', '15.18', '', 'swma', 20),
(334, 'AXE Αποσμητικό Σπρει Black Vanilla 150ml', 'PHOTO/products/swma/AXE.jpg', '3.43', '', 'swma', 20),
(335, 'STR8 Original Σετ Δώρου με Νεσεσέρ', 'PHOTO/products/swma/STR8SET2.jpg', '17.80', '', 'swma', 20),
(336, 'GILLETTE Fusion 5 Ανταλλακτικές Κεφαλές Ξυρίσματος 8τεμ', 'PHOTO/products/ksyrisma/GILLETTEFUSION5.jpg', '30.98', '', 'ksyrisma', 20),
(337, 'GILLETTE Original Αφρός Ξυρίσματος Sensitive 200ml', 'PHOTO/products/ksyrisma/GILLETTEAFROS.jpg', '4.14', '', 'ksyrisma', 20),
(338, 'NIVEA Men Αφρός Ξυρίσματος Sensitive 250ml', 'PHOTO/products/ksyrisma/NIVEAAFROS.jpg', '5.22', '', 'ksyrisma', 20),
(339, 'GILLETTE VENUS Ξυριστική Μηχανή Smooth +2 Ανταλλακτικές Κεφαλές', 'PHOTO/products/ksyrisma/VENUS.jpg', '10.90', '', 'ksyrisma', 20),
(340, 'OLD SPICE After Shave Original 100ml', 'PHOTO/products/ksyrisma/OLDSPICEAFTER.jpg', '11.30', '', 'ksyrisma', 20),
(341, 'BIOTEN Hyaluronic Μάσκα Προσώπου Υφασμάτινη Nourishing & Firming 20ml', 'PHOTO/products/proswpo/BIOTEN.jpg', '2.38', '', 'proswpo', 20),
(342, 'BIOTEN Vitamin C Μάσκα Προσώπου Υφασμάτινη Brightening & Revitalizing 20ml', 'PHOTO/products/proswpo/BIOTENC.jpg', '2.38', '', 'proswpo', 20),
(343, 'GARNIER Skin Active Moisture Bomb Patches Ματιών με Χυμό Πορτοκάλι 6gr', 'PHOTO/products/proswpo/GARNIER.jpg', '2.53', '', 'proswpo', 20),
(344, 'JOHNSON\'S Lipcare Θρεπτικό Balm Χειλιών 4,9gr', 'PHOTO/products/proswpo/JOHNSON.jpg', '2.48', '', 'proswpo', 20),
(345, 'AIM Οδοντόβουρτσα Anti-Plaque μέτρια 2τεμ', 'PHOTO/products/stoma/AIMODON.jpg', '2.18', '', 'stoma', 20),
(346, 'COLGATE Οδοντόκρεμα Triple Action 75ml', 'PHOTO/products/stoma/COLGATE.jpeg', '2.36', '', 'stoma', 20),
(347, 'ORAL-B Essential Οδοντικό Νήμα Κηρωμένο 50m', 'PHOTO/products/stoma/ORAL-B.jpg', '3.98', '', 'stoma', 20),
(348, 'ORAL-B Στοματικό Διάλυμα Δοντιών & Ούλων 500ml', 'PHOTO/products/stoma/ORAL--B.jpg', '6.64', '', 'stoma', 20),
(349, 'ARIEL Alpine Απορρυπαντικό Πλυντηρίου Ρούχων Υγρό 90 πλύσεις', 'PHOTO/products/royxwn/ARIEL.jpg', '23.00', '', 'royxwn', 20),
(350, 'SOUPLINE Μαλακτικό Ρούχων Συμπυκνωμένο Mistral 92 πλύσεις', 'PHOTO/products/royxwn/SOUPLINE.jpg', '11.49', '', 'royxwn', 20),
(351, 'LENOR Συμπυκνωμένο Μαλακτικό Ρούχων Amber & Orchid 56 πλύσεις', 'PHOTO/products/royxwn/LENOR.jpg', '7.93', '', 'royxwn', 20),
(352, 'KRISTAL Σόδα Πλύσεως Πλυντηρίου Ρούχων 1kg', 'PHOTO/products/royxwn/KRISTAL.jpg', '1.88', '', 'royxwn', 20),
(353, 'FAIRY Max Power Απορρυπαντικό Πιάτων Υγρό Original 660ml', 'PHOTO/products/piata/FAIRYMAX.jpg', '4.86', '', 'piata', 20),
(354, 'FAIRY Απορρυπαντικό Πιάτων Υγρό Λεμόνι 900ml', 'PHOTO/products/piata/FAIRYLEMON.jpg', '4.86', '', 'piata', 20),
(355, 'AVA Perle Απορρυπαντικό Πιάτων Υγρό με άρωμα Λεμόνι & Εκχύλισμα Χαμομηλιού 1500ml', 'PHOTO/products/piata/AVA.jpg', '5.53', '', 'piata', 20),
(356, 'ΕΥΡΗΚΑ Baby Απορρυπαντικό Πιάτων Υγρό 500ml', 'PHOTO/products/piata/EYRHKA.jpg', '2.48', '', 'piata', 20),
(357, 'KLINEX Ultra Χλωρίνη Λεμόνι 750ml', 'PHOTO/products/katharistika/KLINEX.jpg', '1.96', '', 'katharistika', 20),
(358, 'CIF Κρέμα Καθαρισμού για Όλες τις Επιφάνειες Λεμόνι 500ml', 'PHOTO/products/katharistika/CIF.jpg', '2.88', '', 'katharistika', 20),
(359, 'AJAX Boost Καθαριστικό Πατώματος Ξίδι & Μήλο 1lt', 'PHOTO/products/katharistika/AJAX.jpg', '2.83', '', 'katharistika', 20),
(360, 'DETTOL All In One Απολυμαντικό Σπρέι Crisp Linen 400ml', 'PHOTO/products/katharistika/DETTOL.jpg', '4.66', '', 'katharistika', 20),
(361, 'NEAT WASH Σφουγγάρι Κουζίνας 6τεμ', 'PHOTO/products/synergaKatharismoy/NEAT.jpg', '0.97', '', 'synergaKatharismoy', 20),
(362, 'Κοντάρι Μεταλλικό με Χονδρό Σπείρωμα', 'PHOTO/products/synergaKatharismoy/KONTARI.jpeg', '1.00', '', 'synergaKatharismoy', 20),
(363, 'KITTEN Σφουγγαρίστρα Χονδρό Σπείρωμα', 'PHOTO/products/synergaKatharismoy/KITTEN.jpg', '2.80', '', 'synergaKatharismoy', 20),
(364, 'PRACTIC Γάντια Νιτριλίου Large μαύρα 50τεμ', 'PHOTO/products/synergaKatharismoy/PRACTIC.jpg', '2.00', '', 'synergaKatharismoy', 10),
(654, 'Mellisa Pasta Kids', 'PHOTO/products/zymarika/melissa-pasta-kids.jfif', '2.40', 'Mellisa Pasta Kids', 'zymarika', 20);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `registration`
--

CREATE TABLE `registration` (
  `id` int(10) NOT NULL,
  `fName` varchar(30) NOT NULL,
  `lName` varchar(30) NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(60) NOT NULL,
  `password` varchar(255) NOT NULL,
  `number` varchar(10) NOT NULL,
  `odos` varchar(30) NOT NULL,
  `polh` varchar(30) NOT NULL,
  `tk` varchar(8) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `registration`
--

INSERT INTO `registration` (`id`, `fName`, `lName`, `username`, `email`, `password`, `number`, `odos`, `polh`, `tk`, `role`) VALUES
(21, 'Test ', 'Test', 'test', 'test@test.com', '$2y$10$Mnb8ZewNAXxFzu2I9UHUmO3SJWJfp7qnl3eHxsghpvbKK6hUqm2vW', '6912345678', 'Test Address', 'Test City', '12345', 'user'),
(24, 'Admin', 'Admin', 'admin', 'admin@admin.com', '$2y$10$Xmc/YcWkUJFbqehwPC/XWe9bo6Ti9kKnYvZwRsBvQAA1pS.wZ1p9y', '6947811245', 'Admin', 'Admin', '12345', 'admin'),
(32, 'Μαρία', 'Παπαδάκη', 'mpapad', 'mpapad@eshop.com', '$2y$10$rvEE/51E.BU2CnIsxIgje.kIX6/rzx1AKZPQRyEgE6p9yIhke9dbK', '6976543201', 'Λεωφόρος Κηφισίας', 'Μαρούσι, Αθήνα', '15124', 'user'),
(33, 'Νίκος', 'Παπαδόπουλος', 'papadnikos', 'papadnikos@email.com', '$2y$10$bQ9Igw66Huy7ei7/jDbka.OgB0lBPxwJ3Z.V./szdh6Vpfvyo97NO', '6901122334', 'Λεωφόρος Δημοκρατίας', 'Αθήνα', '11764', 'user'),
(34, 'dimis', 'dimis', 'dimis', 'dimis@dimis.gr', '$2y$10$7RnkO9cQvPrpjiQaZZiF.eqxcatRv43hOwiF/e6OEojEYfxvVNrwG', '6955543902', 'ΓΟΡΤΥΝΗΣ, 46', 'ΗΡΑΚΛΕΙΟ, ΚΡΗΤΗΣ', '71303', 'admin');

--
-- Ευρετήρια για άχρηστους πίνακες
--

--
-- Ευρετήρια για πίνακα `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_contact_messages_user` (`user_id`);

--
-- Ευρετήρια για πίνακα `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Ευρετήρια για πίνακα `message_replies`
--
ALTER TABLE `message_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_id` (`message_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Ευρετήρια για πίνακα `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Ευρετήρια για πίνακα `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Ευρετήρια για πίνακα `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT για άχρηστους πίνακες
--

--
-- AUTO_INCREMENT για πίνακα `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=369;

--
-- AUTO_INCREMENT για πίνακα `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT για πίνακα `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT για πίνακα `message_replies`
--
ALTER TABLE `message_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT για πίνακα `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT για πίνακα `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=229;

--
-- AUTO_INCREMENT για πίνακα `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=655;

--
-- AUTO_INCREMENT για πίνακα `registration`
--
ALTER TABLE `registration`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Περιορισμοί για άχρηστους πίνακες
--

--
-- Περιορισμοί για πίνακα `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD CONSTRAINT `fk_contact_messages_user` FOREIGN KEY (`user_id`) REFERENCES `registration` (`id`) ON DELETE CASCADE;

--
-- Περιορισμοί για πίνακα `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `registration` (`id`) ON DELETE CASCADE;

--
-- Περιορισμοί για πίνακα `message_replies`
--
ALTER TABLE `message_replies`
  ADD CONSTRAINT `message_replies_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `contact_messages` (`id`) ON DELETE CASCADE;

--
-- Περιορισμοί για πίνακα `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `registration` (`id`) ON DELETE CASCADE;

--
-- Περιορισμοί για πίνακα `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
