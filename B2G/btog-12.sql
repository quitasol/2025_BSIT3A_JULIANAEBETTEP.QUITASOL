-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2025 at 08:19 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `btog`
--

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `delivery_id` int(11) NOT NULL,
  `po_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `delivery_status` enum('pending','in-transit','delivered','accepted') DEFAULT 'pending',
  `received_by` varchar(255) DEFAULT NULL,
  `inspection_status` enum('pending','passed','failed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `agency_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comments` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `government`
--

CREATE TABLE `government` (
  `government_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `agency_name` varchar(255) NOT NULL,
  `position` varchar(255) DEFAULT NULL,
  `verification_status` enum('pending','verified','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notif_id` int(11) NOT NULL,
  `user_type` varchar(50) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `quote_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notif_id`, `user_type`, `message`, `quote_id`, `created_at`, `user_id`) VALUES
(65, 'government', 'A quote has been accepted and is ready for checkout.', 57, '2025-05-20 00:15:57', NULL),
(66, 'government', 'A quote has been accepted and is ready for checkout.', 62, '2025-05-20 10:25:58', NULL),
(67, 'government', 'A quote has been accepted and is ready for checkout.', 72, '2025-05-20 11:13:35', NULL),
(68, 'government', 'A quote has been accepted and is ready for checkout.', 73, '2025-05-20 11:20:13', NULL),
(69, 'government', 'A quote has been accepted and is ready for checkout.', 74, '2025-05-20 11:40:16', NULL),
(70, 'government', 'A quote has been accepted and is ready for checkout.', 75, '2025-05-20 12:30:59', NULL),
(71, 'government', 'A quote has been accepted and is ready for checkout.', 76, '2025-05-20 13:10:56', NULL),
(72, 'government', 'A quote has been accepted and is ready for checkout.', 77, '2025-05-20 13:17:38', NULL),
(73, 'government', 'A quote has been accepted and is ready for checkout.', 78, '2025-05-20 13:18:51', NULL),
(74, 'government', 'A quote has been accepted and is ready for checkout.', 79, '2025-05-20 15:10:03', NULL),
(75, 'government', 'A quote has been accepted and is ready for checkout.', 80, '2025-05-20 15:10:53', NULL),
(76, 'government', 'A quote has been accepted and is ready for checkout.', 81, '2025-05-20 15:15:56', NULL),
(77, 'government', 'A quote has been accepted and is ready for checkout.', 82, '2025-05-20 15:16:09', NULL),
(78, 'government', 'A quote has been accepted and is ready for checkout.', 84, '2025-05-20 15:18:10', NULL),
(79, 'government', 'A quote has been accepted and is ready for checkout.', 83, '2025-05-20 15:18:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_id` varchar(50) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `agency` varchar(255) DEFAULT NULL,
  `bank` varchar(100) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `details` text DEFAULT NULL,
  `quote_id` int(11) DEFAULT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `payment_status` enum('Pending','Paid') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_id`, `supplier_id`, `name`, `email`, `phone`, `agency`, `bank`, `bank_name`, `total`, `status`, `created_at`, `details`, `quote_id`, `reference_number`, `payment_status`) VALUES
(38, 'ORD-682bf65e1674e', 14, 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'sdf', NULL, NULL, 90.00, 'Confirmed', '2025-05-19 21:26:22', '{\"delivery_address\":\"Banga Caves, Ragay, Camarines Sur\",\"reference_number\":\"09513373760\",\"special_instructions\":\"\"}', 73, NULL, 'Paid'),
(39, 'ORD-682bf77c0a6f6', 16, 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'sdf', NULL, NULL, 76.00, 'Confirmed', '2025-05-19 21:31:08', '{\"delivery_address\":\"Banga Caves, Ragay, Camarines Sur\",\"reference_number\":\"09513373760\",\"special_instructions\":\"\"}', 72, NULL, 'Paid'),
(40, 'ORD-682c0e097f70d', 14, 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'DOH', NULL, NULL, 90.00, 'Confirmed', '2025-05-19 23:07:21', '{\"delivery_address\":\"Banga Caves, Ragay, Camarines Sur\",\"reference_number\":\"09513373760\",\"special_instructions\":\"\"}', 75, NULL, 'Paid'),
(41, 'ORD-682c0e118d38f', 14, 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'sdf', NULL, NULL, 90.00, 'Confirmed', '2025-05-19 23:07:29', '{\"delivery_address\":\"Banga Caves, Ragay, Camarines Sur\",\"reference_number\":\"09513373760\",\"special_instructions\":\"\"}', 74, NULL, 'Paid'),
(42, 'ORD-682c0eee3da4a', 14, 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'DOH', NULL, NULL, 90.00, 'Confirmed', '2025-05-19 23:11:10', '{\"delivery_address\":\"Banga Caves, Ragay, Camarines Sur\",\"reference_number\":\"09513373760\",\"special_instructions\":\"\"}', 76, NULL, 'Paid'),
(43, 'ORD-682c107a1d06d', 14, 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Ddsafsfdv', NULL, NULL, 90.00, 'Confirmed', '2025-05-19 23:17:46', '{\"delivery_address\":\"Banga Caves, Ragay, Camarines Sur\",\"reference_number\":\"09513373760\",\"special_instructions\":\"\"}', 77, NULL, 'Paid'),
(44, 'ORD-682c10c290a9a', 14, 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Ddsafsfdv', NULL, NULL, 90.00, 'Confirmed', '2025-05-19 23:18:58', '{\"delivery_address\":\"Banga Caves, Ragay, Camarines Sur\",\"reference_number\":\"09513373760\",\"special_instructions\":\"\"}', 78, NULL, 'Paid'),
(45, 'ORD-682c2ad1b22e1', 14, 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'DOH', NULL, NULL, 90.00, 'Confirmed', '2025-05-20 01:10:09', '{\"delivery_address\":\"Banga Caves, Ragay, Camarines Sur\",\"reference_number\":\"09513373760\",\"special_instructions\":\"\"}', 79, NULL, 'Paid'),
(46, 'ORD-682c2b04afcfe', 14, 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'DOH', NULL, NULL, 180.00, 'Confirmed', '2025-05-20 01:11:00', '{\"delivery_address\":\"Banga Caves, Ragay, Camarines Sur\",\"reference_number\":\"09513373760\",\"special_instructions\":\"\"}', 80, NULL, 'Paid'),
(47, 'ORD-682c2c34ee844', 14, 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'dsfdv', NULL, NULL, 90.00, 'Confirmed', '2025-05-20 01:16:04', '{\"delivery_address\":\"Banga Caves, Ragay, Camarines Sur\",\"reference_number\":\"09513373760\",\"special_instructions\":\"\"}', 81, NULL, 'Paid'),
(48, 'ORD-682c2c4028ff4', 14, 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'ggk', NULL, NULL, 90.00, 'Confirmed', '2025-05-20 01:16:16', '{\"delivery_address\":\"Banga Caves, Ragay, Camarines Sur\",\"reference_number\":\"09513373760\",\"special_instructions\":\"\"}', 82, NULL, 'Paid'),
(49, 'ORD-682c2cc32ed5d', 14, 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'dsfdv', NULL, NULL, 90.00, 'Confirmed', '2025-05-20 01:18:27', '{\"delivery_address\":\"Banga Caves, Ragay, Camarines Sur\",\"reference_number\":\"09513373760\",\"special_instructions\":\"\"}', 83, NULL, 'Paid'),
(50, 'ORD-682c2cc8acc73', 14, 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'dsfdv', NULL, NULL, 90.00, 'Confirmed', '2025-05-20 01:18:32', '{\"delivery_address\":\"Banga Caves, Ragay, Camarines Sur\",\"reference_number\":\"09513373760\",\"special_instructions\":\"\"}', 84, NULL, 'Paid');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `po_id` int(11) NOT NULL,
  `agency_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_method` enum('LandBank','Check','Bank Transfer') NOT NULL,
  `status` enum('pending','completed','failed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL COMMENT 'stationery\r\noffice supplies\r\nfurniture\r\ncleaning materials',
  `price` decimal(15,2) NOT NULL,
  `abc` decimal(15,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `supplier_id`, `product_name`, `description`, `category`, `price`, `abc`, `stock`, `status`, `created_at`, `image`) VALUES
(7, 16, 'Book', 'book', 'paper-products', 76.00, 0.00, 0, 'pending', '2025-05-07 02:02:43', 'books.jpg'),
(10, 14, 'Clock', 'pra may time sya sayo', 'desk-accessories', 1000.00, 0.00, 0, 'pending', '2025-05-20 07:57:14', 'clock.jpg'),
(11, 14, 'Computer', 'ewan', 'office-tools', 50000.00, 0.00, 0, 'pending', '2025-05-20 08:17:17', 'computer.jpg'),
(13, 14, 'ememe', 'jkj', 'paper-products', 90.00, 0.00, 0, 'pending', '2025-05-20 18:11:26', 'books.jpg'),
(19, 14, 'hgdjhas', 'shadhhdash', 'writing-instruments', 234.00, 0.00, 0, 'pending', '2025-05-21 06:10:56', '0');

-- --------------------------------------------------------

--
-- Table structure for table `purchaserequests`
--

CREATE TABLE `purchaserequests` (
  `pr_id` int(11) NOT NULL,
  `agency_id` int(11) NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `quotation_id` int(11) NOT NULL,
  `rfq_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quoted_price` decimal(15,2) NOT NULL,
  `status` enum('submitted','accepted','rejected') DEFAULT 'submitted',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotes`
--

CREATE TABLE `quotes` (
  `id` int(11) NOT NULL,
  `agency` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `delivery_address` text NOT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `special_instructions` text DEFAULT NULL,
  `supplier_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quotes`
--

INSERT INTO `quotes` (`id`, `agency`, `contact_person`, `email`, `phone`, `delivery_address`, `reference_number`, `special_instructions`, `supplier_id`, `total`, `created_at`, `status`) VALUES
(59, 'cdrg', 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Banga Caves, Ragay, Camarines Sur', '09513373760', '', 1, 90.00, '2025-05-20 09:42:05', NULL),
(60, 'cdrg', 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Banga Caves, Ragay, Camarines Sur', '09513373760', '', 1, 90.00, '2025-05-20 09:46:22', NULL),
(61, 'DOH', 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Banga Caves, Ragay, Camarines Sur', '09513373760', '', 0, 90.00, '2025-05-20 10:19:07', NULL),
(62, 'DOH', 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Banga Caves, Ragay, Camarines Sur', '09513373760', '', 14, 90.00, '2025-05-20 10:25:34', 'accepted'),
(63, 'DOH', 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Banga Caves, Ragay, Camarines Sur', '09513373760', '', 14, 90.00, '2025-05-20 10:28:36', NULL),
(64, 'sdf', 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Banga Caves, Ragay, Camarines Sur', '09513373760', '', 14, 90.00, '2025-05-20 10:29:03', NULL),
(65, 'sdf', 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Banga Caves, Ragay, Camarines Sur', '09513373760', '', 14, 76.00, '2025-05-20 10:31:12', NULL),
(66, 'sdf', 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Banga Caves, Ragay, Camarines Sur', '09513373760', '', 14, 76.00, '2025-05-20 10:34:02', NULL),
(67, 'sdf', 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Banga Caves, Ragay, Camarines Sur', '09513373760', '', 14, 76.00, '2025-05-20 10:58:05', NULL),
(68, 'sdf', 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Banga Caves, Ragay, Camarines Sur', '09513373760', '', 14, 90.00, '2025-05-20 10:58:43', NULL),
(69, 'sdf', 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Banga Caves, Ragay, Camarines Sur', '09513373760', '', 16, 76.00, '2025-05-20 11:02:46', NULL),
(70, 'sdf', 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Banga Caves, Ragay, Camarines Sur', '09513373760', '', 16, 90.00, '2025-05-20 11:03:56', NULL),
(71, 'sdf', 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Banga Caves, Ragay, Camarines Sur', '09513373760', '', 14, 76.00, '2025-05-20 11:05:26', NULL),
(72, 'sdf', 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Banga Caves, Ragay, Camarines Sur', '09513373760', '', 16, 76.00, '2025-05-20 11:12:11', 'Ordered'),
(73, 'sdf', 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Banga Caves, Ragay, Camarines Sur', '09513373760', '', 14, 90.00, '2025-05-20 11:19:48', 'Ordered'),
(74, 'sdf', 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Banga Caves, Ragay, Camarines Sur', '09513373760', '', 14, 90.00, '2025-05-20 11:39:59', 'Ordered'),
(75, 'DOH', 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Banga Caves, Ragay, Camarines Sur', '09513373760', '', 14, 90.00, '2025-05-20 12:30:34', 'Ordered'),
(76, 'DOH', 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Banga Caves, Ragay, Camarines Sur', '09513373760', '', 14, 90.00, '2025-05-20 13:10:23', 'Ordered'),
(77, 'Ddsafsfdv', 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Banga Caves, Ragay, Camarines Sur', '09513373760', '', 14, 90.00, '2025-05-20 13:17:30', 'Ordered'),
(78, 'Ddsafsfdv', 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Banga Caves, Ragay, Camarines Sur', '09513373760', '', 14, 90.00, '2025-05-20 13:18:42', 'Ordered'),
(79, 'DOH', 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Banga Caves, Ragay, Camarines Sur', '09513373760', '', 14, 90.00, '2025-05-20 15:09:47', 'Ordered'),
(80, 'DOH', 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Banga Caves, Ragay, Camarines Sur', '09513373760', '', 14, 180.00, '2025-05-20 15:10:45', 'Ordered'),
(81, 'dsfdv', 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Banga Caves, Ragay, Camarines Sur', '09513373760', '', 14, 90.00, '2025-05-20 15:15:33', 'Ordered'),
(82, 'ggk', 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Banga Caves, Ragay, Camarines Sur', '09513373760', '', 14, 90.00, '2025-05-20 15:15:44', 'Ordered'),
(83, 'dsfdv', 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Banga Caves, Ragay, Camarines Sur', '09513373760', '', 14, 90.00, '2025-05-20 15:17:50', 'Ordered'),
(84, 'dsfdv', 'Juliana Ebette Quitasol', 'alex@gmail.com', '09513373760', 'Banga Caves, Ragay, Camarines Sur', '09513373760', '', 14, 90.00, '2025-05-20 15:18:03', 'Ordered');

-- --------------------------------------------------------

--
-- Table structure for table `rfqs`
--

CREATE TABLE `rfqs` (
  `rfq_id` int(11) NOT NULL,
  `pr_id` int(11) NOT NULL,
  `agency_id` int(11) NOT NULL,
  `status` enum('open','closed','awarded') DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_type` enum('government','supplier','admin') NOT NULL,
  `full_name` varchar(250) NOT NULL,
  `user_name` varchar(250) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `philgeps_number` varchar(50) DEFAULT NULL,
  `business_permit` varchar(255) DEFAULT NULL,
  `tax_clearance` varchar(255) DEFAULT NULL,
  `status` enum('pending','verified','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_type`, `full_name`, `user_name`, `email`, `password_hash`, `philgeps_number`, `business_permit`, `tax_clearance`, `status`, `created_at`) VALUES
(13, 'government', 'Governement', 'government', 'government@gmail.com', '$2y$10$2u84u0K5gM.hshwx4S3gz.gB5M8z.5FN0iBLmUQN6ftkbIUgyDYEW', NULL, NULL, NULL, 'verified', '2025-05-07 01:49:50'),
(14, 'supplier', 'Supplier', 'supplier', 'supplier@gmail.com', '$2y$10$TJ7FfT8AKIMlVDY9eymeMOv1PuURscpoL6bGu1d3xfqPyneSrhsCe', NULL, NULL, NULL, 'verified', '2025-05-07 01:51:42'),
(15, 'admin', 'Admin', 'admin', 'admin@gmail.com', '$2y$10$InC6.p4Adayx/X1lvPby/O69L4R7zHEeXXqm93dG/seatSME3o4LO', NULL, NULL, NULL, 'pending', '2025-05-07 01:52:17'),
(16, 'supplier', 'Supplier1', 'supplier1', 'supplier1@gmail.com', '$2y$10$O1N10N1RyfSkrctjpRsjdOhThc/4h8SVWHaD8iaiV3UUuXjnhkGt.', NULL, NULL, NULL, 'verified', '2025-05-07 02:01:24'),
(18, 'government', 'Gov', 'gov', 'gov@gmail.com', '$2y$10$lkS8PjTql/6K4eqjG9nHRuQJTtcp..6sGlgwIPwJjr0ob3.adUBBm', NULL, NULL, NULL, 'verified', '2025-05-20 08:21:30'),
(20, 'government', 'Govi', 'govi', 'govi@gmail.com', '$2y$10$J7mfm50A/4enFuhX91HKjOlt0OdYmbjB9/2mcB0qqUOInET9MIS1S', NULL, NULL, NULL, 'rejected', '2025-05-20 08:33:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`delivery_id`),
  ADD KEY `po_id` (`po_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `agency_id` (`agency_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `government`
--
ALTER TABLE `government`
  ADD PRIMARY KEY (`government_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notif_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `po_id` (`po_id`),
  ADD KEY `agency_id` (`agency_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `purchaserequests`
--
ALTER TABLE `purchaserequests`
  ADD PRIMARY KEY (`pr_id`),
  ADD KEY `agency_id` (`agency_id`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`quotation_id`),
  ADD KEY `rfq_id` (`rfq_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `quotes`
--
ALTER TABLE `quotes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rfqs`
--
ALTER TABLE `rfqs`
  ADD PRIMARY KEY (`rfq_id`),
  ADD KEY `pr_id` (`pr_id`),
  ADD KEY `agency_id` (`agency_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `philgeps_number` (`philgeps_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `delivery_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `government`
--
ALTER TABLE `government`
  MODIFY `government_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notif_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `purchaserequests`
--
ALTER TABLE `purchaserequests`
  MODIFY `pr_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `quotation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotes`
--
ALTER TABLE `quotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `rfqs`
--
ALTER TABLE `rfqs`
  MODIFY `rfq_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`agency_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `government`
--
ALTER TABLE `government`
  ADD CONSTRAINT `government_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `purchaserequests`
--
ALTER TABLE `purchaserequests`
  ADD CONSTRAINT `purchaserequests_ibfk_1` FOREIGN KEY (`agency_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `quotations`
--
ALTER TABLE `quotations`
  ADD CONSTRAINT `quotations_ibfk_1` FOREIGN KEY (`rfq_id`) REFERENCES `rfqs` (`rfq_id`),
  ADD CONSTRAINT `quotations_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `quotations_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
