-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2025 at 09:41 AM
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
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_id` varchar(50) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `agency` varchar(255) DEFAULT NULL,
  `bank` varchar(100) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_id`, `name`, `email`, `phone`, `agency`, `bank`, `bank_name`, `total`, `status`, `created_at`) VALUES
(6, 'ORD-67a715254e190', 'juls', 'juls@g', '09924478933', 'DOH', 'govbank', 'bjhyyh', 900000.00, 'Pending', '2025-02-08 08:26:13'),
(7, 'ORD-67a715521388d', 'Cindy', 'cindy@gm', '09924478933', 'DOH', 'unionbank', 'bjhyyh', 2700000.00, 'Pending', '2025-02-08 08:26:58');

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
(3, 9, 'hehe', 'hehe', NULL, 900000.00, 0.00, 0, 'pending', '2025-02-07 07:58:57', '20230808173548775.jpg'),
(4, 8, 'ememe', 'eme eme', 'furniture', 900000.00, 0.00, 0, 'pending', '2025-02-07 08:36:11', 'received_737394008229073.jpeg');

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
(1, 'admin', '', '', 'alex@gmail.com', 'admin', NULL, NULL, NULL, 'verified', '2025-02-07 03:32:24'),
(2, 'admin', '', '', 'eunice@gmail.com', '$2y$10$7L7bgRNUjuSLivyyohgb7O.LC.9H5Ercm062ZKYJBi.afAa4C1edu', NULL, NULL, NULL, 'pending', '2025-02-07 04:36:45'),
(6, 'admin', 'Carl Hugo', 'carl', 'carl@gmail.com', '$2y$10$sghj//W6ssnKOPZ4Dpo6CuJC2DifNdFXy9z3RFxtL5HunucY10U12', NULL, NULL, NULL, 'pending', '2025-02-07 05:14:59'),
(7, 'admin', 'Nicole Paraiso', 'nicole', 'nicole@gmail.com', '$2y$10$xqNMlkSOvfk4yCEPK4rXP.h1DWY6ExqmV/zhbsiBGArZZbJiSw13O', NULL, NULL, NULL, 'pending', '2025-02-07 05:17:42'),
(8, 'supplier', 'Eunice Mae Tanguin', 'eunice', 'eunice2003@gmail.com', '$2y$10$G5DR/xz4Gip4M2WZwl.AReakJakJ832SWjfxFl6i1gfVRHRI6Hmo6', NULL, NULL, NULL, 'verified', '2025-02-07 05:19:16'),
(9, 'supplier', 'Juliana Ebette Quitasol', 'juls', 'juls@gmail.com', '$2y$10$4u.IpSWcEFjrMz10zqZ4dOyVigIsB2fDli9rpzVYZYRxM.xxfPt4e', NULL, NULL, NULL, 'verified', '2025-02-07 05:52:29'),
(10, 'supplier', 'Cindy Shane Dacuba', 'cindy', 'cindy@gmail.com', '$2y$10$BBfvT6d5pqZhoxWiwRejqO/.h5UuCJIamj3RoaFVm3BNPHace73Te', NULL, NULL, NULL, 'verified', '2025-02-07 05:56:05'),
(12, 'government', 'Cindy Shane Dacuba', 'cindy', 'cindyshane@gmail.com', '$2y$10$u17jxhGz0yFpGO2a3o17vulcGzhK2PsbLdP4k1C5QHIFRrxZJ.5q6', NULL, NULL, NULL, 'verified', '2025-02-07 07:22:54');

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
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- AUTO_INCREMENT for table `rfqs`
--
ALTER TABLE `rfqs`
  MODIFY `rfq_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD CONSTRAINT `deliveries_ibfk_1` FOREIGN KEY (`po_id`) REFERENCES `purchaseorders` (`po_id`),
  ADD CONSTRAINT `deliveries_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `users` (`user_id`);

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
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`po_id`) REFERENCES `purchaseorders` (`po_id`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`agency_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`supplier_id`) REFERENCES `users` (`user_id`);

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

--
-- Constraints for table `rfqs`
--
ALTER TABLE `rfqs`
  ADD CONSTRAINT `rfqs_ibfk_1` FOREIGN KEY (`pr_id`) REFERENCES `purchaserequests` (`pr_id`),
  ADD CONSTRAINT `rfqs_ibfk_2` FOREIGN KEY (`agency_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
