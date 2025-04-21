-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 21, 2025 lúc 04:49 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `phone_store`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `total_amount`, `created_at`, `updated_at`) VALUES
(9, 4, 104970001.00, '2025-04-19 03:50:22', '2025-04-20 08:58:22'),
(36, 2, 69980001.00, '2025-04-19 22:56:22', '2025-04-21 03:43:05');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(37, 9, 18, 1, 34990001.00, '2025-04-19 05:04:23', '2025-04-19 05:04:23'),
(38, 9, 19, 1, 34990000.00, '2025-04-19 05:04:24', '2025-04-19 05:04:24'),
(67, 36, 18, 1, 34990001.00, '2025-04-19 22:56:22', '2025-04-19 22:56:22'),
(68, 9, 20, 1, 34990000.00, '2025-04-20 08:58:22', '2025-04-20 08:58:22'),
(69, 36, 19, 1, 34990000.00, '2025-04-21 03:43:05', '2025-04-21 03:43:05');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'iPhone', 'iphone', 'Điện thoại iPhone1', 'iphone-category.jpg', '2025-04-16 15:27:32', '2025-04-19 00:26:35', NULL),
(2, 'Samsung', 'samsung', 'Điện thoại Samsung', 'samsung-category.jpg', '2025-04-16 15:27:32', '2025-04-16 16:06:04', NULL),
(3, 'Xiaomi', 'xiaomi', 'Điện thoại Xiaomi', 'xiaomi-category.jpg', '2025-04-16 15:27:32', '2025-04-16 16:06:30', NULL),
(4, 'OPPO', 'oppo', 'Điện thoại OPPO', 'oppo-category.jpg', '2025-04-16 15:27:32', '2025-04-17 16:16:31', NULL),
(5, 'Vivo', 'vivo', 'Điện thoại Vivo', 'vivo-category.jpg', '2025-04-16 15:27:32', '2025-04-17 16:17:55', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"25659d06-835f-4272-bd03-889b79f75708\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmation\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:26:\\\"App\\\\Mail\\\\OrderConfirmation\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:67;s:9:\\\"relations\\\";a:2:{i:0;s:4:\\\"user\\\";i:1;s:9:\\\"user.cart\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"}}', 0, NULL, 1745077597, 1745077597),
(2, 'default', '{\"uuid\":\"ac5b9c18-1345-4240-b97d-a656a705cf83\",\"displayName\":\"App\\\\Mail\\\\OrderConfirmation\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:26:\\\"App\\\\Mail\\\\OrderConfirmation\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:68;s:9:\\\"relations\\\";a:2:{i:0;s:4:\\\"user\\\";i:1;s:9:\\\"user.cart\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"}}', 0, NULL, 1745077772, 1745077772),
(3, 'default', '{\"uuid\":\"962c6ce2-7ecc-4956-88e3-d9dd55db0f8d\",\"displayName\":\"App\\\\Notifications\\\\PaymentSuccessNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:2;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:44:\\\"App\\\\Notifications\\\\PaymentSuccessNotification\\\":2:{s:8:\\\"\\u0000*\\u0000order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:69;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"ee973b72-890c-4fe7-927c-f0bdcd2435a5\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}', 0, NULL, 1745078078, 1745078078),
(4, 'default', '{\"uuid\":\"42d806c5-a643-477e-b493-0d36bf4e61ff\",\"displayName\":\"App\\\\Notifications\\\\PaymentSuccessNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:2;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:44:\\\"App\\\\Notifications\\\\PaymentSuccessNotification\\\":2:{s:8:\\\"\\u0000*\\u0000order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:71;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"5651b0c5-4fec-4b28-8e91-20c6ebfa77bf\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}', 0, NULL, 1745078335, 1745078335),
(5, 'default', '{\"uuid\":\"f096373b-02a7-4ece-bc43-0b40ddaa08cf\",\"displayName\":\"App\\\\Notifications\\\\PaymentSuccessNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:2;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:44:\\\"App\\\\Notifications\\\\PaymentSuccessNotification\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:72;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"7335879e-acef-4c89-8d1c-22d985d83a47\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}', 0, NULL, 1745078538, 1745078538),
(6, 'default', '{\"uuid\":\"44a40441-e33f-446c-9114-0108617e9fce\",\"displayName\":\"App\\\\Notifications\\\\PaymentSuccessNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:2;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:44:\\\"App\\\\Notifications\\\\PaymentSuccessNotification\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:73;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"7a3ccb85-dc44-4336-8138-7a688d699a3b\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}', 0, NULL, 1745078736, 1745078736),
(7, 'default', '{\"uuid\":\"908718fe-9ed7-4818-b247-3625cbec595d\",\"displayName\":\"App\\\\Notifications\\\\PaymentSuccessNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:2;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:44:\\\"App\\\\Notifications\\\\PaymentSuccessNotification\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:74;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"1c706420-cab6-4494-9c08-7ade62ab8dd1\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}', 0, NULL, 1745078807, 1745078807),
(8, 'default', '{\"uuid\":\"6742afa2-0300-4849-abdc-2e44f4166815\",\"displayName\":\"App\\\\Notifications\\\\PaymentSuccessNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:2;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:44:\\\"App\\\\Notifications\\\\PaymentSuccessNotification\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:75;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"8edceed8-6c38-408c-965b-25960d8f0ad7\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}', 0, NULL, 1745079001, 1745079001),
(9, 'default', '{\"uuid\":\"85eeb129-f4c2-49fa-9a2a-fbc104381d26\",\"displayName\":\"App\\\\Notifications\\\\PaymentSuccessNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:2;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:44:\\\"App\\\\Notifications\\\\PaymentSuccessNotification\\\":2:{s:5:\\\"order\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:16:\\\"App\\\\Models\\\\Order\\\";s:2:\\\"id\\\";i:78;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"c30ded51-55e9-4a0a-ad78-cfd591bc5bce\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}', 0, NULL, 1745080071, 1745080071),
(10, 'default', '{\"uuid\":\"2e0a7adf-3848-4036-94f8-3a7da8892473\",\"displayName\":\"App\\\\Listeners\\\\SendPromotionNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Events\\\\CallQueuedListener\",\"command\":\"O:36:\\\"Illuminate\\\\Events\\\\CallQueuedListener\\\":20:{s:5:\\\"class\\\";s:39:\\\"App\\\\Listeners\\\\SendPromotionNotification\\\";s:6:\\\"method\\\";s:6:\\\"handle\\\";s:4:\\\"data\\\";a:1:{i:0;O:25:\\\"App\\\\Events\\\\PromotionEvent\\\":2:{s:9:\\\"promotion\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\Promotion\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"type\\\";s:7:\\\"updated\\\";}}s:5:\\\"tries\\\";N;s:13:\\\"maxExceptions\\\";N;s:7:\\\"backoff\\\";N;s:10:\\\"retryUntil\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"failOnTimeout\\\";b:0;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1745163090, 1745163090),
(11, 'default', '{\"uuid\":\"d0c7e33a-7327-44bf-8b51-1ef96fc428af\",\"displayName\":\"App\\\\Listeners\\\\SendPromotionNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Events\\\\CallQueuedListener\",\"command\":\"O:36:\\\"Illuminate\\\\Events\\\\CallQueuedListener\\\":20:{s:5:\\\"class\\\";s:39:\\\"App\\\\Listeners\\\\SendPromotionNotification\\\";s:6:\\\"method\\\";s:6:\\\"handle\\\";s:4:\\\"data\\\";a:1:{i:0;O:25:\\\"App\\\\Events\\\\PromotionEvent\\\":2:{s:9:\\\"promotion\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\Promotion\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"type\\\";s:7:\\\"updated\\\";}}s:5:\\\"tries\\\";N;s:13:\\\"maxExceptions\\\";N;s:7:\\\"backoff\\\";N;s:10:\\\"retryUntil\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"failOnTimeout\\\";b:0;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1745163297, 1745163297),
(12, 'default', '{\"uuid\":\"d42cef93-5a01-47cb-97d0-3c4ed79dc448\",\"displayName\":\"App\\\\Listeners\\\\SendPromotionNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Events\\\\CallQueuedListener\",\"command\":\"O:36:\\\"Illuminate\\\\Events\\\\CallQueuedListener\\\":20:{s:5:\\\"class\\\";s:39:\\\"App\\\\Listeners\\\\SendPromotionNotification\\\";s:6:\\\"method\\\";s:6:\\\"handle\\\";s:4:\\\"data\\\";a:1:{i:0;O:25:\\\"App\\\\Events\\\\PromotionEvent\\\":2:{s:9:\\\"promotion\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\Promotion\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"type\\\";s:7:\\\"updated\\\";}}s:5:\\\"tries\\\";N;s:13:\\\"maxExceptions\\\";N;s:7:\\\"backoff\\\";N;s:10:\\\"retryUntil\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"failOnTimeout\\\";b:0;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1745163310, 1745163310),
(13, 'default', '{\"uuid\":\"c6811b20-2c4d-461e-8449-92686720fa18\",\"displayName\":\"App\\\\Listeners\\\\SendPromotionNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Events\\\\CallQueuedListener\",\"command\":\"O:36:\\\"Illuminate\\\\Events\\\\CallQueuedListener\\\":20:{s:5:\\\"class\\\";s:39:\\\"App\\\\Listeners\\\\SendPromotionNotification\\\";s:6:\\\"method\\\";s:6:\\\"handle\\\";s:4:\\\"data\\\";a:1:{i:0;O:25:\\\"App\\\\Events\\\\PromotionEvent\\\":2:{s:9:\\\"promotion\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\Promotion\\\";s:2:\\\"id\\\";i:3;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"type\\\";s:7:\\\"created\\\";}}s:5:\\\"tries\\\";N;s:13:\\\"maxExceptions\\\";N;s:7:\\\"backoff\\\";N;s:10:\\\"retryUntil\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"failOnTimeout\\\";b:0;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1745163331, 1745163331),
(14, 'default', '{\"uuid\":\"d658eaec-34b4-4758-bbf5-c680efa152bd\",\"displayName\":\"App\\\\Listeners\\\\SendPromotionNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Events\\\\CallQueuedListener\",\"command\":\"O:36:\\\"Illuminate\\\\Events\\\\CallQueuedListener\\\":20:{s:5:\\\"class\\\";s:39:\\\"App\\\\Listeners\\\\SendPromotionNotification\\\";s:6:\\\"method\\\";s:6:\\\"handle\\\";s:4:\\\"data\\\";a:1:{i:0;O:25:\\\"App\\\\Events\\\\PromotionEvent\\\":2:{s:9:\\\"promotion\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\Promotion\\\";s:2:\\\"id\\\";i:3;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"type\\\";s:7:\\\"updated\\\";}}s:5:\\\"tries\\\";N;s:13:\\\"maxExceptions\\\";N;s:7:\\\"backoff\\\";N;s:10:\\\"retryUntil\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"failOnTimeout\\\";b:0;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1745163471, 1745163471),
(15, 'default', '{\"uuid\":\"58364dc8-ad3b-4f3c-b2d7-572327234394\",\"displayName\":\"App\\\\Listeners\\\\SendPromotionNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Events\\\\CallQueuedListener\",\"command\":\"O:36:\\\"Illuminate\\\\Events\\\\CallQueuedListener\\\":20:{s:5:\\\"class\\\";s:39:\\\"App\\\\Listeners\\\\SendPromotionNotification\\\";s:6:\\\"method\\\";s:6:\\\"handle\\\";s:4:\\\"data\\\";a:1:{i:0;O:25:\\\"App\\\\Events\\\\PromotionEvent\\\":2:{s:9:\\\"promotion\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\Promotion\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"type\\\";s:7:\\\"updated\\\";}}s:5:\\\"tries\\\";N;s:13:\\\"maxExceptions\\\";N;s:7:\\\"backoff\\\";N;s:10:\\\"retryUntil\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"failOnTimeout\\\";b:0;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1745163576, 1745163576),
(16, 'default', '{\"uuid\":\"2c7a3834-84c9-4418-8db7-e3128d8b6667\",\"displayName\":\"App\\\\Listeners\\\\SendPromotionNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Events\\\\CallQueuedListener\",\"command\":\"O:36:\\\"Illuminate\\\\Events\\\\CallQueuedListener\\\":20:{s:5:\\\"class\\\";s:39:\\\"App\\\\Listeners\\\\SendPromotionNotification\\\";s:6:\\\"method\\\";s:6:\\\"handle\\\";s:4:\\\"data\\\";a:1:{i:0;O:25:\\\"App\\\\Events\\\\PromotionEvent\\\":2:{s:9:\\\"promotion\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\Promotion\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"type\\\";s:7:\\\"updated\\\";}}s:5:\\\"tries\\\";N;s:13:\\\"maxExceptions\\\";N;s:7:\\\"backoff\\\";N;s:10:\\\"retryUntil\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"failOnTimeout\\\";b:0;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1745163657, 1745163657),
(17, 'default', '{\"uuid\":\"de828cde-d55a-4b3f-910a-5560854df19b\",\"displayName\":\"App\\\\Listeners\\\\SendPromotionNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Events\\\\CallQueuedListener\",\"command\":\"O:36:\\\"Illuminate\\\\Events\\\\CallQueuedListener\\\":20:{s:5:\\\"class\\\";s:39:\\\"App\\\\Listeners\\\\SendPromotionNotification\\\";s:6:\\\"method\\\";s:6:\\\"handle\\\";s:4:\\\"data\\\";a:1:{i:0;O:25:\\\"App\\\\Events\\\\PromotionEvent\\\":2:{s:9:\\\"promotion\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\Promotion\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"type\\\";s:7:\\\"updated\\\";}}s:5:\\\"tries\\\";N;s:13:\\\"maxExceptions\\\";N;s:7:\\\"backoff\\\";N;s:10:\\\"retryUntil\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"failOnTimeout\\\";b:0;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1745163852, 1745163852),
(18, 'default', '{\"uuid\":\"d88bc985-7eb0-499d-a31a-9fa06a8ff66d\",\"displayName\":\"App\\\\Listeners\\\\SendPromotionNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Events\\\\CallQueuedListener\",\"command\":\"O:36:\\\"Illuminate\\\\Events\\\\CallQueuedListener\\\":20:{s:5:\\\"class\\\";s:39:\\\"App\\\\Listeners\\\\SendPromotionNotification\\\";s:6:\\\"method\\\";s:6:\\\"handle\\\";s:4:\\\"data\\\";a:1:{i:0;O:25:\\\"App\\\\Events\\\\PromotionEvent\\\":2:{s:9:\\\"promotion\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\Promotion\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"type\\\";s:7:\\\"updated\\\";}}s:5:\\\"tries\\\";N;s:13:\\\"maxExceptions\\\";N;s:7:\\\"backoff\\\";N;s:10:\\\"retryUntil\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"failOnTimeout\\\";b:0;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1745163856, 1745163856),
(19, 'default', '{\"uuid\":\"75022b9c-73bc-4a3a-b271-00e97b633a41\",\"displayName\":\"App\\\\Listeners\\\\SendPromotionNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Events\\\\CallQueuedListener\",\"command\":\"O:36:\\\"Illuminate\\\\Events\\\\CallQueuedListener\\\":20:{s:5:\\\"class\\\";s:39:\\\"App\\\\Listeners\\\\SendPromotionNotification\\\";s:6:\\\"method\\\";s:6:\\\"handle\\\";s:4:\\\"data\\\";a:1:{i:0;O:25:\\\"App\\\\Events\\\\PromotionEvent\\\":2:{s:9:\\\"promotion\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\Promotion\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"type\\\";s:7:\\\"updated\\\";}}s:5:\\\"tries\\\";N;s:13:\\\"maxExceptions\\\";N;s:7:\\\"backoff\\\";N;s:10:\\\"retryUntil\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"failOnTimeout\\\";b:0;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1745163962, 1745163962),
(20, 'default', '{\"uuid\":\"67bc4d5b-5b0c-4e35-928f-fa3f02128ea2\",\"displayName\":\"App\\\\Listeners\\\\SendPromotionNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Events\\\\CallQueuedListener\",\"command\":\"O:36:\\\"Illuminate\\\\Events\\\\CallQueuedListener\\\":20:{s:5:\\\"class\\\";s:39:\\\"App\\\\Listeners\\\\SendPromotionNotification\\\";s:6:\\\"method\\\";s:6:\\\"handle\\\";s:4:\\\"data\\\";a:1:{i:0;O:25:\\\"App\\\\Events\\\\PromotionEvent\\\":2:{s:9:\\\"promotion\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\Promotion\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"type\\\";s:7:\\\"created\\\";}}s:5:\\\"tries\\\";N;s:13:\\\"maxExceptions\\\";N;s:7:\\\"backoff\\\";N;s:10:\\\"retryUntil\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"failOnTimeout\\\";b:0;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1745163989, 1745163989),
(21, 'default', '{\"uuid\":\"5b8cb5f2-9f5a-4f7a-90f3-ce7a41ddf8f2\",\"displayName\":\"App\\\\Listeners\\\\SendPromotionNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Events\\\\CallQueuedListener\",\"command\":\"O:36:\\\"Illuminate\\\\Events\\\\CallQueuedListener\\\":20:{s:5:\\\"class\\\";s:39:\\\"App\\\\Listeners\\\\SendPromotionNotification\\\";s:6:\\\"method\\\";s:6:\\\"handle\\\";s:4:\\\"data\\\";a:1:{i:0;O:25:\\\"App\\\\Events\\\\PromotionEvent\\\":2:{s:9:\\\"promotion\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\Promotion\\\";s:2:\\\"id\\\";i:3;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"type\\\";s:7:\\\"updated\\\";}}s:5:\\\"tries\\\";N;s:13:\\\"maxExceptions\\\";N;s:7:\\\"backoff\\\";N;s:10:\\\"retryUntil\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"failOnTimeout\\\";b:0;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1745164143, 1745164143),
(22, 'default', '{\"uuid\":\"c2bd8535-77b2-463e-b208-968909ad2368\",\"displayName\":\"App\\\\Listeners\\\\SendPromotionNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Events\\\\CallQueuedListener\",\"command\":\"O:36:\\\"Illuminate\\\\Events\\\\CallQueuedListener\\\":20:{s:5:\\\"class\\\";s:39:\\\"App\\\\Listeners\\\\SendPromotionNotification\\\";s:6:\\\"method\\\";s:6:\\\"handle\\\";s:4:\\\"data\\\";a:1:{i:0;O:25:\\\"App\\\\Events\\\\PromotionEvent\\\":2:{s:9:\\\"promotion\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\Promotion\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"type\\\";s:7:\\\"updated\\\";}}s:5:\\\"tries\\\";N;s:13:\\\"maxExceptions\\\";N;s:7:\\\"backoff\\\";N;s:10:\\\"retryUntil\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"failOnTimeout\\\";b:0;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1745164323, 1745164323),
(23, 'default', '{\"uuid\":\"ac3005d1-5f4f-445e-8dc1-89addc245e84\",\"displayName\":\"App\\\\Listeners\\\\SendPromotionNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Events\\\\CallQueuedListener\",\"command\":\"O:36:\\\"Illuminate\\\\Events\\\\CallQueuedListener\\\":20:{s:5:\\\"class\\\";s:39:\\\"App\\\\Listeners\\\\SendPromotionNotification\\\";s:6:\\\"method\\\";s:6:\\\"handle\\\";s:4:\\\"data\\\";a:1:{i:0;O:25:\\\"App\\\\Events\\\\PromotionEvent\\\":2:{s:9:\\\"promotion\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\Promotion\\\";s:2:\\\"id\\\";i:3;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"type\\\";s:7:\\\"updated\\\";}}s:5:\\\"tries\\\";N;s:13:\\\"maxExceptions\\\";N;s:7:\\\"backoff\\\";N;s:10:\\\"retryUntil\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"failOnTimeout\\\";b:0;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1745164434, 1745164434),
(24, 'default', '{\"uuid\":\"0c3a962b-2427-4f9e-841a-9eee78b0a617\",\"displayName\":\"App\\\\Notifications\\\\PromotionNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:2;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:39:\\\"App\\\\Notifications\\\\PromotionNotification\\\":3:{s:12:\\\"\\u0000*\\u0000promotion\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\Promotion\\\";s:2:\\\"id\\\";i:3;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"\\u0000*\\u0000type\\\";s:7:\\\"updated\\\";s:2:\\\"id\\\";s:36:\\\"0b71a982-21dd-4f12-8360-9e2556ab467b\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:8:\\\"database\\\";}}\"}}', 0, NULL, 1745165160, 1745165160),
(25, 'default', '{\"uuid\":\"5488809b-1c35-4c0b-9d31-c1d3370535c6\",\"displayName\":\"App\\\\Notifications\\\\PromotionNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:4;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:39:\\\"App\\\\Notifications\\\\PromotionNotification\\\":3:{s:12:\\\"\\u0000*\\u0000promotion\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\Promotion\\\";s:2:\\\"id\\\";i:3;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"\\u0000*\\u0000type\\\";s:7:\\\"updated\\\";s:2:\\\"id\\\";s:36:\\\"9407131a-0d8f-46f9-90fb-2f2bff1c25e7\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:8:\\\"database\\\";}}\"}}', 0, NULL, 1745165160, 1745165160),
(26, 'default', '{\"uuid\":\"fef0a008-7144-45d0-9964-018f0cb1e82b\",\"displayName\":\"App\\\\Notifications\\\\PromotionNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:5;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:39:\\\"App\\\\Notifications\\\\PromotionNotification\\\":3:{s:12:\\\"\\u0000*\\u0000promotion\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\Promotion\\\";s:2:\\\"id\\\";i:3;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:7:\\\"\\u0000*\\u0000type\\\";s:7:\\\"updated\\\";s:2:\\\"id\\\";s:36:\\\"071ee87a-9fe6-4f14-b4c2-47772edd7bb1\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:8:\\\"database\\\";}}\"}}', 0, NULL, 1745165160, 1745165160),
(27, 'default', '{\"uuid\":\"4903bbce-fc5d-4ca2-851a-251ed77be4ef\",\"displayName\":\"App\\\\Events\\\\PromotionEvent\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":14:{s:5:\\\"event\\\";O:25:\\\"App\\\\Events\\\\PromotionEvent\\\":2:{s:9:\\\"promotion\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:20:\\\"App\\\\Models\\\\Promotion\\\";s:2:\\\"id\\\";i:3;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:4:\\\"type\\\";s:7:\\\"updated\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"}}', 0, NULL, 1745165160, 1745165160);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2025_04_16_150510_create_sessions_table', 1),
(4, '2025_04_16_163956_create_personal_access_tokens_table', 2),
(5, '2014_10_12_100000_create_password_reset_tokens_table', 3),
(6, '2025_04_17_114223_modify_carts_table_total_amount_column', 4),
(7, '2025_04_18_054425_update_orders_and_order_items_tables', 5),
(8, '2025_04_18_055241_modify_orders_total_amount_column', 6),
(9, '2014_10_12_100000_create_is_block_table copy', 7),
(10, '2025_04_19_055821_add_price_to_deleted_at_table', 8),
(11, '2024_01_16_000000_add_soft_deletes_to_categories_table', 9),
(12, '2024_04_19_create_orders_table', 10),
(13, '2025_04_20_152851_create_notifications_table', 11),
(14, '2024_03_21_create_notifications_table', 12);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
(5, 'promotion', 'App\\Models\\User', 2, '\"{\\\"title\\\":\\\"C\\\\u1eadp nh\\\\u1eadt khuy\\\\u1ebfn m\\\\u00e3i\\\",\\\"content\\\":\\\"Khuy\\\\u1ebfn m\\\\u00e3i hehe1 \\\\u0111\\\\u00e3 \\\\u0111\\\\u01b0\\\\u1ee3c c\\\\u1eadp nh\\\\u1eadt. Gi\\\\u1ea3m gi\\\\u00e1 12.00%\\\",\\\"promotion_id\\\":3,\\\"start_date\\\":\\\"2025-04-17T22:35:00.000000Z\\\",\\\"end_date\\\":\\\"2025-04-30T22:35:00.000000Z\\\"}\"', NULL, '2025-04-20 09:46:02', '2025-04-20 10:08:36'),
(6, 'promotion', 'App\\Models\\User', 5, '\"{\\\"title\\\":\\\"C\\\\u1eadp nh\\\\u1eadt khuy\\\\u1ebfn m\\\\u00e3i\\\",\\\"content\\\":\\\"Khuy\\\\u1ebfn m\\\\u00e3i hehe1 \\\\u0111\\\\u00e3 \\\\u0111\\\\u01b0\\\\u1ee3c c\\\\u1eadp nh\\\\u1eadt. Gi\\\\u1ea3m gi\\\\u00e1 12.00%\\\",\\\"promotion_id\\\":3,\\\"start_date\\\":\\\"2025-04-17T22:35:00.000000Z\\\",\\\"end_date\\\":\\\"2025-04-30T22:35:00.000000Z\\\"}\"', NULL, '2025-04-20 09:46:02', '2025-04-20 09:46:02'),
(7, 'promotion', 'App\\Models\\User', 2, '\"{\\\"title\\\":\\\"C\\\\u1eadp nh\\\\u1eadt khuy\\\\u1ebfn m\\\\u00e3i\\\",\\\"content\\\":\\\"Khuy\\\\u1ebfn m\\\\u00e3i hehe11 \\\\u0111\\\\u00e3 \\\\u0111\\\\u01b0\\\\u1ee3c c\\\\u1eadp nh\\\\u1eadt. Gi\\\\u1ea3m gi\\\\u00e1 11.00%\\\",\\\"promotion_id\\\":4,\\\"start_date\\\":\\\"2025-04-10T22:46:00.000000Z\\\",\\\"end_date\\\":\\\"2025-04-30T22:46:00.000000Z\\\"}\"', '2025-04-21 03:36:40', '2025-04-20 09:50:29', '2025-04-21 03:36:40'),
(8, 'promotion', 'App\\Models\\User', 5, '\"{\\\"title\\\":\\\"C\\\\u1eadp nh\\\\u1eadt khuy\\\\u1ebfn m\\\\u00e3i\\\",\\\"content\\\":\\\"Khuy\\\\u1ebfn m\\\\u00e3i hehe11 \\\\u0111\\\\u00e3 \\\\u0111\\\\u01b0\\\\u1ee3c c\\\\u1eadp nh\\\\u1eadt. Gi\\\\u1ea3m gi\\\\u00e1 11.00%\\\",\\\"promotion_id\\\":4,\\\"start_date\\\":\\\"2025-04-10T22:46:00.000000Z\\\",\\\"end_date\\\":\\\"2025-04-30T22:46:00.000000Z\\\"}\"', NULL, '2025-04-20 09:50:29', '2025-04-20 09:50:29');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_code` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `shipping_name` varchar(255) NOT NULL,
  `shipping_phone` varchar(255) NOT NULL,
  `status` enum('pending','pending_confirmation','processing','shipping','completed','cancelled') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(50) NOT NULL,
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `shipping_address` text NOT NULL,
  `shipping_city` varchar(255) NOT NULL,
  `shipping_district` varchar(255) NOT NULL,
  `shipping_ward` varchar(255) NOT NULL,
  `note` text DEFAULT NULL,
  `shipping_status` enum('pending','shipped','delivered') DEFAULT 'pending',
  `tracking_number` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `total_amount` decimal(20,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `order_code`, `user_id`, `shipping_name`, `shipping_phone`, `status`, `payment_method`, `payment_status`, `shipping_address`, `shipping_city`, `shipping_district`, `shipping_ward`, `note`, `shipping_status`, `tracking_number`, `created_at`, `updated_at`, `total_amount`) VALUES
(55, 'ORD-BO8T4VKVVP', 2, 'Nguyen Huu Khai', '0902429971', 'cancelled', 'momo', 'failed', 'Tp Hồ Chí Minh', 'Tp Hồ Chí Minh', 'Cần Giờ', 'An Thới Đông', NULL, 'pending', NULL, '2025-04-18 00:42:51', '2025-04-19 01:04:03', 9490000.00),
(56, 'ORD-O71APDR0DQ', 2, 'Nguyen Huu Khai', '0902429971', 'cancelled', 'momo', 'paid', 'Tp Hồ Chí Minh', 'Tp Hồ Chí Minh', 'Cần Giờ', 'An Thới Đông', NULL, 'pending', NULL, '2025-04-18 00:44:33', '2025-04-18 02:02:42', 9490000.00),
(59, 'ORD-BKLYP7BXL2', 2, 'Nguyen Huu Khai', '0902429971', 'shipping', 'momo', 'paid', 'Tp Hồ Chí Minh', 'Tp Hồ Chí Minh', 'Cần Giờ', 'An Thới Đông', NULL, 'pending', NULL, '2025-04-18 00:48:18', '2025-04-19 00:59:47', 9490000.00),
(60, 'ORD-04V0XFDOJY', 2, 'Nguyen Huu Khai', '0902429971', 'processing', 'momo', 'paid', 'Tp Hồ Chí Minh', 'Tp Hồ Chí Minh', 'Cần Giờ', 'An Thới Đông', NULL, 'pending', NULL, '2025-04-18 00:50:55', '2025-04-19 00:54:56', 9490000.00),
(62, 'ORD-JAORLX3XYN', 2, 'Nguyen Huu Khai', '0902429971', 'pending', 'vnpay', 'paid', 'Tp Hồ Chí Minh', 'Tp Hồ Chí Minh', 'Cần Giờ', 'An Thới Đông', NULL, 'pending', NULL, '2025-04-18 01:43:23', '2025-04-18 01:43:23', 9490000.00),
(63, 'ORD-87WFEVY0TY', 2, 'Nguyen Huu Khai', '0902429971', 'completed', 'vnpay', 'paid', 'Tp Hồ Chí Minh', 'Tp Hồ Chí Minh', 'Cần Giờ', 'An Thới Đông', NULL, 'pending', NULL, '2025-04-18 01:48:51', '2025-04-18 16:10:28', 9490000.00),
(64, 'ORD-XC2FG86TRW', 2, 'Nguyen Huu Khai', '0902429971', 'pending', 'cod', 'pending', 'An Nghĩa, An Thới Đông, Cần Giờ, Tp Hồ Chí Minh', 'Tp Hồ Chí Minh', 'Cần Giờ', 'An Thới Đông', NULL, 'pending', NULL, '2025-04-19 08:14:14', '2025-04-19 08:14:14', 18980000.00),
(65, 'ORD-U6P8CMOCHK', 2, 'Nguyen Huu Khai', '0902429971', 'pending', 'vnpay', 'paid', 'hehe', 'Tp Hồ Chí Minh', 'Cần Giờ', 'An Thới Đông', NULL, 'pending', NULL, '2025-04-19 08:26:29', '2025-04-19 08:26:29', 9990000.00),
(66, 'ORD-IHGTSZ1HMU', 2, 'Nguyen Huu Khai', '0902429971', 'pending', 'momo', 'paid', 'huhu', 'Tp Hồ Chí Minh', 'Cần Giờ', 'An Thới Đông', NULL, 'pending', NULL, '2025-04-19 08:29:25', '2025-04-19 08:29:25', 9990000.00),
(77, 'ORD-C2RCAFDJZF', 2, 'Nguyen Huu Khai', '0902429971', 'pending', 'cod', 'pending', 'An Thới Đông, Cần Giờ, Tp Hồ Chí Minh', 'Tp Hồ Chí Minh', 'Cần Giờ', 'An Thới Đông', NULL, 'pending', NULL, '2025-04-19 09:17:32', '2025-04-19 09:17:32', 34990000.00),
(78, 'ORD-BOGRBPS3NP', 2, 'Nguyen Huu Khai', '0902429971', 'pending', 'cod', 'pending', 'An Thới Đông, Cần Giờ, Tp Hồ Chí Minh', 'Tp Hồ Chí Minh', 'Cần Giờ', 'An Thới Đông', NULL, 'pending', NULL, '2025-04-19 09:27:51', '2025-04-19 09:27:51', 6490000.00),
(79, 'ORD-ARE2CMBULX', 2, 'Nguyen Huu Khai', '0902429971', 'pending', 'cod', 'pending', 'An Thới Đông, Cần Giờ, Tp Hồ Chí Minh', 'Tp Hồ Chí Minh', 'Cần Giờ', 'An Thới Đông', NULL, 'pending', NULL, '2025-04-19 09:30:43', '2025-04-19 09:30:43', 9990000.00),
(80, 'ORD-XMQGQVENQH', 2, 'Nguyen Huu Khai', '0902429971', 'pending', 'momo', 'paid', 'An Thới Đông, Cần Giờ, Tp Hồ Chí Minh', 'Tp Hồ Chí Minh', 'Cần Giờ', 'An Thới Đông', NULL, 'pending', NULL, '2025-04-19 09:34:02', '2025-04-19 09:34:02', 6490000.00),
(87, 'ORD-NSJI5GZHQG', 2, 'Nguyen Huu Khai', '0902429971', 'pending', 'momo', 'paid', 'An Thới Đông, Cần Giờ, Tp Hồ Chí Minh', 'Tp Hồ Chí Minh', 'Cần Giờ', 'An Thới Đông', NULL, 'pending', NULL, '2025-04-19 09:47:57', '2025-04-19 09:47:57', 6490000.00),
(88, 'ORD-M8BIQWPW4M', 2, 'Nguyen Huu Khai', '0902429971', 'pending', 'momo', 'paid', 'An Thới Đông, Cần Giờ, Tp Hồ Chí Minh', 'Tp Hồ Chí Minh', 'Cần Giờ', 'An Thới Đông', NULL, 'pending', NULL, '2025-04-19 10:00:17', '2025-04-19 10:00:17', 6490000.00),
(89, 'ORD-XX7XTBVEGZ', 2, 'Nguyen Huu Khai', '0902429971', 'pending', 'momo', 'paid', 'An Thới Đông, Cần Giờ, Tp Hồ Chí Minh', 'Tp Hồ Chí Minh', 'Cần Giờ', 'An Thới Đông', NULL, 'pending', NULL, '2025-04-19 10:02:13', '2025-04-19 10:02:13', 6490000.00),
(90, 'ORD-ACACXF3K7P', 2, 'Nguyen Huu Khai', '0902429971', 'pending', 'vnpay', 'paid', 'An Thới Đông, Cần Giờ, Tp Hồ Chí Minh', 'Tp Hồ Chí Minh', 'Cần Giờ', 'An Thới Đông', NULL, 'pending', NULL, '2025-04-19 10:04:12', '2025-04-19 10:04:12', 6490000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `product_color` varchar(255) DEFAULT NULL,
  `product_ram` varchar(255) DEFAULT NULL,
  `product_storage` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_image`, `product_color`, `product_ram`, `product_storage`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(91, 55, 10, 'Samsung Galaxy A54', 'a54.jpg', 'Awesome Violet', '8GB', '128GB', 1, 9490000.00, '2025-04-18 00:42:51', '2025-04-18 00:42:51'),
(92, 56, 10, 'Samsung Galaxy A54', 'a54.jpg', 'Awesome Violet', '8GB', '128GB', 1, 9490000.00, '2025-04-18 00:44:33', '2025-04-18 00:44:33'),
(95, 59, 10, 'Samsung Galaxy A54', 'a54.jpg', 'Awesome Violet', '8GB', '128GB', 1, 9490000.00, '2025-04-18 00:48:18', '2025-04-18 00:48:18'),
(96, 60, 10, 'Samsung Galaxy A54', 'a54.jpg', 'Awesome Violet', '8GB', '128GB', 1, 9490000.00, '2025-04-18 00:50:55', '2025-04-18 00:50:55'),
(97, 62, 10, 'Samsung Galaxy A54', 'a54.jpg', 'Awesome Violet', '8GB', '128GB', 1, 9490000.00, '2025-04-18 01:43:23', '2025-04-18 01:43:23'),
(98, 63, 10, 'Samsung Galaxy A54', 'a54.jpg', 'Awesome Violet', '8GB', '128GB', 1, 9490000.00, '2025-04-18 01:48:51', '2025-04-18 01:48:51'),
(99, 64, 10, 'Samsung Galaxy A54', 'a54.jpg', 'Awesome Violet', '8GB', '128GB', 2, 9490000.00, '2025-04-19 08:14:14', '2025-04-19 08:14:14'),
(100, 65, 10, 'Samsung Galaxy A54', 'a54.jpg', 'Awesome Violet', '8GB', '128GB', 1, 9990000.00, '2025-04-19 08:26:29', '2025-04-19 08:26:29'),
(101, 66, 10, 'Samsung Galaxy A54', 'a54.jpg', 'Awesome Violet', '8GB', '128GB', 1, 9990000.00, '2025-04-19 08:29:25', '2025-04-19 08:29:25'),
(112, 77, 20, 'iPhone 16 Pro Max', 'iphone-16-natural.jpg', 'Natural Titanium', '8GB', '256GB', 1, 34990000.00, '2025-04-19 09:17:32', '2025-04-19 09:17:32'),
(113, 78, 11, 'Xiaomi Redmi Note 13', 'redmi13.jpg', 'Mint Green', '6GB', '128GB', 1, 6490000.00, '2025-04-19 09:27:51', '2025-04-19 09:27:51'),
(114, 79, 10, 'Samsung Galaxy A54', 'a54.jpg', 'Awesome Violet', '8GB', '128GB', 1, 9990000.00, '2025-04-19 09:30:43', '2025-04-19 09:30:43'),
(115, 80, 11, 'Xiaomi Redmi Note 13', 'redmi13.jpg', 'Mint Green', '6GB', '128GB', 1, 6490000.00, '2025-04-19 09:34:02', '2025-04-19 09:34:02'),
(122, 87, 11, 'Xiaomi Redmi Note 13', 'redmi13.jpg', 'Mint Green', '6GB', '128GB', 1, 6490000.00, '2025-04-19 09:47:57', '2025-04-19 09:47:57'),
(123, 88, 11, 'Xiaomi Redmi Note 13', 'redmi13.jpg', 'Mint Green', '6GB', '128GB', 1, 6490000.00, '2025-04-19 10:00:17', '2025-04-19 10:00:17'),
(124, 89, 11, 'Xiaomi Redmi Note 13', 'redmi13.jpg', 'Mint Green', '6GB', '128GB', 1, 6490000.00, '2025-04-19 10:02:13', '2025-04-19 10:02:13'),
(125, 90, 11, 'Xiaomi Redmi Note 13', 'redmi13.jpg', 'Mint Green', '6GB', '128GB', 1, 6490000.00, '2025-04-19 10:04:12', '2025-04-19 10:04:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('huukhai023@gmail.com', '$2y$12$zvIGZmA0..ahTyYka3Jaa.Y1XtFAVhYub0qlT0YQhixOP1LL0FiEK', '2025-04-18 22:29:58'),
('nguyenhuukhai22052003@gmail.com', '$2y$12$BwV/roJZQIPHYBksDShAXuAYkbs5zXzHFzdr4/aNp1zILaGMETNp6', '2025-04-18 22:30:24');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int(11) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `color` varchar(50) NOT NULL,
  `storage` varchar(50) NOT NULL,
  `ram` varchar(50) NOT NULL,
  `screen_size` varchar(50) NOT NULL,
  `battery_capacity` varchar(50) NOT NULL,
  `operating_system` varchar(50) NOT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `category_id`, `price`, `discount_price`, `stock_quantity`, `description`, `image`, `color`, `storage`, `ram`, `screen_size`, `battery_capacity`, `operating_system`, `is_featured`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'iPhone 16 Pro Max', 'iphone-16-pro-max-desert', 1, 34990000.00, 30791200.00, 50, 'iPhone 16 Pro Max 256GB - Desert Titanium', 'iphone.jpg', 'Dersert Titanium', '256GB', '16GB', '6.7 inch', '4422 mAh', 'iOS 17', 0, 1, '2025-04-16 15:27:32', '2025-04-20 08:49:03', NULL),
(2, 'Samsung Galaxy S24 Ultra', 'samsung-galaxy-s24-ultra', 2, 32990000.00, 29031200.00, 40, 'Samsung Galaxy S24 Ultra 256GB', 'samsung.jpg', 'Titanium Black', '256GB', '12GB', '6.8 inch', '5000 mAh', 'Android 14', 0, 1, '2025-04-16 15:27:32', '2025-04-20 08:35:31', NULL),
(3, 'Xiaomi 14 Pro', 'xiaomi-14-pro', 3, 24990000.00, NULL, 30, 'Xiaomi 14 Pro 256GB', 'xiaomi.jpg', 'Black', '256GB', '12GB', '6.73 inch', '4880 mAh', 'Android 14', 0, 1, '2025-04-16 15:27:32', '2025-04-16 16:03:15', NULL),
(4, 'OPPO Find X7 Ultra', 'oppo-find-x7-ultra', 4, 29990000.00, 26691100.00, 35, 'OPPO Find X7 Ultra 256GB', 'oppo.jpg', 'Black', '256GB', '12GB', '6.82 inch', '5000 mAh', 'Android 14', 0, 1, '2025-04-16 15:27:32', '2025-04-20 08:46:28', NULL),
(5, 'Vivo X100 Pro', 'vivo-x100-pro', 5, 24990000.00, NULL, 25, 'Vivo X100 Pro 256GB', 'vivo.jpg', 'Black', '256GB', '12GB', '6.78 inch', '5400 mAh', 'Android 14', 0, 1, '2025-04-16 15:27:32', '2025-04-16 16:12:25', NULL),
(6, 'Samsung Galaxy S24 Ultra', 'samsung-galaxy-s24-ultra-titanium-gray', 2, 32990000.00, NULL, 20, 'Samsung Galaxy S24 Ultra 256GB - Titanium Gray', 'samsung-s24-ultra-gray.jpg', 'Titanium Gray', '256GB', '12GB', '6.8 inch', '5000 mAh', 'Android 14', 1, 1, '2025-04-16 17:07:25', '2025-04-16 17:07:25', NULL),
(7, 'iPhone 14 Pro', 'iphone-14-pro', 1, 27990000.00, NULL, 40, 'iPhone 14 Pro 128GB', 'iphone14pro.jpg', 'Space Black', '128GB', '6GB', '6.1 inch', '3200 mAh', 'iOS 16', 0, 1, '2025-04-17 06:06:56', '2025-04-17 06:06:56', NULL),
(8, 'iPhone Pro 13', 'iphone-pro-13', 1, 19990000.00, 17990000.00, 35, 'iPhone 13 Pro 128GB', 'iphone13pro.jpg', 'Blue', '128GB', '4GB', '6.1 inch', '3240 mAh', 'iOS 15', 0, 1, '2025-04-17 06:06:56', '2025-04-17 16:05:07', NULL),
(9, 'Samsung Galaxy Z Flip5', 'samsung-galaxy-z-flip5', 2, 24990000.00, NULL, 25, 'Galaxy Z Flip5 256GB', 'zflip5.jpg', 'Graphite', '256GB', '8GB', '6.7 inch', '3700 mAh', 'Android 13', 1, 1, '2025-04-17 06:06:56', '2025-04-17 06:06:56', NULL),
(10, 'Samsung Galaxy A54', 'samsung-galaxy-a54', 2, 9990000.00, NULL, 52, 'Galaxy A54 128GB', 'a54.jpg', 'Awesome Violet', '128GB', '8GB', '6.4 inch', '5000 mAh', 'Android 13', 0, 1, '2025-04-17 06:06:56', '2025-04-19 09:37:30', NULL),
(11, 'Xiaomi Redmi Note 13', 'xiaomi-redmi-note-13', 3, 6490000.00, NULL, 57, 'Redmi Note 13 128GB', 'redmi13.jpg', 'Mint Green', '128GB', '6GB', '6.67 inch', '5000 mAh', 'Android 13', 0, 1, '2025-04-17 06:06:56', '2025-04-19 10:04:12', NULL),
(12, 'Xiaomi 13T', 'xiaomi-13t', 3, 12990000.00, NULL, 40, 'Xiaomi 13T 256GB', '13t.jpg', 'Black', '256GB', '8GB', '6.67 inch', '5000 mAh', 'Android 13', 1, 1, '2025-04-17 06:06:56', '2025-04-17 06:06:56', NULL),
(13, 'OPPO Reno10 5G', 'oppo-reno10-5g', 4, 10490000.00, NULL, 50, 'OPPO Reno10 5G 256GB', 'reno10.jpg', 'Silver Gray', '256GB', '8GB', '6.7 inch', '5000 mAh', 'Android 13', 0, 1, '2025-04-17 06:06:56', '2025-04-18 23:03:03', NULL),
(14, 'OPPO A78', 'oppo-a78', 4, 6290000.00, NULL, 60, 'OPPO A78 128GB', 'a78.jpg', 'Glowing Blue', '128GB', '8GB', '6.56 inch', '5000 mAh', 'Android 13', 0, 1, '2025-04-17 06:06:56', '2025-04-17 06:06:56', NULL),
(15, 'Vivo V27e', 'vivo-v27e', 5, 8490000.00, 7990000.00, 45, 'Vivo V27e 128GB', 'v27e.jpg', 'Lavender Purple', '128GB', '8GB', '6.62 inch', '4600 mAh', 'Android 13', 0, 1, '2025-04-17 06:06:56', '2025-04-17 06:06:56', NULL),
(16, 'Vivo Y17s', 'vivo-y17s', 5, 3990000.00, NULL, 80, 'Vivo Y17s 128GB', 'y17s.jpg', 'Forest Green', '128GB', '6GB', '6.56 inch', '5000 mAh', 'Android 13', 0, 1, '2025-04-17 06:06:56', '2025-04-17 06:06:56', NULL),
(18, 'iPhone 16 Pro Max', 'iphone-16-pro-max', 1, 34990001.00, NULL, 30, 'iPhone 16 Pro Max 256GB - White Titanium', 'iphone-16-white.jpg', 'White Titanium', '256GB', '16GB', '6.7 inch', '4422 mAh', 'iOS 17', 0, 1, '2025-04-17 06:10:57', '2025-04-20 05:55:57', NULL),
(19, 'iPhone 16 Pro Max', 'iphone-16-pro-max-black-titanium', 1, 34990000.00, NULL, 28, 'iPhone 16 Pro Max 256GB - Black Titanium', 'iphone-16-black.jpg', 'Black Titanium', '256GB', '16GB', '6.7 inch', '4422 mAh', 'iOS 17', 0, 1, '2025-04-17 06:10:57', '2025-04-20 05:56:00', NULL),
(20, 'iPhone 16 Pro Max', 'iphone-16-pro-max-natural-titanium', 1, 34990000.00, NULL, 25, 'iPhone 16 Pro Max 256GB - Natural Titanium', 'iphone-16-natural.jpg', 'Natural Titanium', '256GB', '16GB', '6.7 inch', '4422 mAh', 'iOS 17', 0, 1, '2025-04-17 06:10:57', '2025-04-20 05:56:03', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_promotion`
--

CREATE TABLE `product_promotion` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `promotion_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_promotion`
--

INSERT INTO `product_promotion` (`id`, `product_id`, `promotion_id`, `created_at`, `updated_at`) VALUES
(3, 2, 3, '2025-04-20 08:35:31', '2025-04-20 08:35:31'),
(4, 4, 4, '2025-04-20 08:46:28', '2025-04-20 08:46:28'),
(5, 1, 3, '2025-04-20 08:49:03', '2025-04-20 08:49:03');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `promotions`
--

CREATE TABLE `promotions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `discount_type` enum('percentage','fixed') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `promotions`
--

INSERT INTO `promotions` (`id`, `name`, `description`, `discount_type`, `discount_value`, `start_date`, `end_date`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Nguyen Huu Khai', 'geg', 'percentage', 10.00, '2025-04-17 15:23:00', '2025-04-25 15:23:00', 1, '2025-04-19 01:23:36', '2025-04-19 01:35:59', '2025-04-19 01:35:59'),
(2, 'huuhu12', '12', 'percentage', 10.00, '2025-04-19 16:07:00', '2025-04-26 15:37:00', 1, '2025-04-19 01:37:18', '2025-04-20 08:48:55', '2025-04-20 08:48:55'),
(3, 'hehe1', '123', 'percentage', 12.00, '2025-04-17 22:35:00', '2025-04-30 22:35:00', 1, '2025-04-20 08:35:31', '2025-04-20 09:06:00', NULL),
(4, 'hehe11', 'ưqe', 'percentage', 11.00, '2025-04-10 22:46:00', '2025-04-30 22:46:00', 1, '2025-04-20 08:46:28', '2025-04-20 08:46:28', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Gpm7nhWPASirPPDVDTXPaLCc94P6WAbGCSoib7zP', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWHdwU3dTcFlwRkhMcDRKSjJFd3h2a05DaVRLZld0R3BRa05kemN0cSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jaGVjay1wcm9tb3Rpb25zIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1745246916);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `blocked_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `role`, `remember_token`, `created_at`, `updated_at`, `is_blocked`, `blocked_at`) VALUES
(2, 'Nguyen Huu Khai', 'nguyenhuukhai22052003@gmail.com', '$2y$12$L8L.Oc4FYFQJDh0GVOLvXeyZj.LvHKphSyDYx8voV09hhLq2Nbp0a', '0902429971', 'hehe1', 'user', '81FbiLUzcYi63KKnkUCvwq04oPm4nULJfJWC9gLGmadf1oFhhcQehLWli1yS', '2025-04-16 09:38:43', '2025-04-20 16:45:48', 0, NULL),
(4, 'admin', 'admin@gmail.com', '$2y$12$fSaeF8CfVa7.vhXVhnuBUOufrUoDUCfADw6J/oVPxCEb1LnOky58u', '0902429971', 'Tphcm', 'admin', NULL, '2025-04-16 09:44:53', '2025-04-18 15:25:42', 0, NULL),
(5, 'khai1', 'huukhai023@gmail.com', '$2y$12$uw4wBle1ag/MprV5/fAuCe4OERrgIOklFqOUZBBQ7.kyPf.f5XvIS', '0902429972', 'Tphcm', 'user', NULL, '2025-04-16 09:46:14', '2025-04-18 22:32:24', 0, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `wishlists`
--

CREATE TABLE `wishlists` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `wishlists`
--

INSERT INTO `wishlists` (`id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 2, '2025-04-17 04:31:16', '2025-04-17 04:31:16'),
(2, 4, '2025-04-19 04:02:35', '2025-04-19 04:02:35');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `wishlist_items`
--

CREATE TABLE `wishlist_items` (
  `id` int(11) NOT NULL,
  `wishlist_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `wishlist_items`
--

INSERT INTO `wishlist_items` (`id`, `wishlist_id`, `product_id`, `created_at`, `updated_at`) VALUES
(32, 1, 1, '2025-04-17 08:48:18', '2025-04-17 08:48:18'),
(33, 1, 19, '2025-04-17 08:52:15', '2025-04-17 08:52:15'),
(34, 1, 18, '2025-04-17 08:52:27', '2025-04-17 08:52:27'),
(38, 1, 7, '2025-04-17 09:23:03', '2025-04-17 09:23:03'),
(42, 2, 18, '2025-04-19 04:02:35', '2025-04-19 04:02:35');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Chỉ mục cho bảng `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `product_promotion`
--
ALTER TABLE `product_promotion`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `wishlist_items`
--
ALTER TABLE `wishlist_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wishlist_id` (`wishlist_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `product_promotion`
--
ALTER TABLE `product_promotion`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `wishlist_items`
--
ALTER TABLE `wishlist_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `wishlist_items`
--
ALTER TABLE `wishlist_items`
  ADD CONSTRAINT `wishlist_items_ibfk_1` FOREIGN KEY (`wishlist_id`) REFERENCES `wishlists` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
