-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2026 at 08:53 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `support_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT 'Admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`, `name`, `created_at`) VALUES
(1, 'admin@test.com', '$2y$10$atpyHi/FaP8RbIT.sum5t.Xhw6U6f60WVwZOe/nvA1Ow.LiNBQiBy', 'Admin User', '2026-04-17 00:07:20'),
(2, 'mo@test.com', '$2y$10$SC9kjbzdOZgXJMwXfqU1qOrS1cSmQFqoQ6H9Y8ILFeoYLJ5dn47pO', 'mo', '2026-04-17 00:07:20');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `type` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'open',
  `reply` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `order_id`, `customer_email`, `type`, `message`, `status`, `reply`, `created_at`) VALUES
(3, 4, 'memo@gmail.com', 'Missing Item', 'mising', 'Closed', NULL, '2026-04-17 00:08:07'),
(4, 8, '213@gmail.com', 'Refund Issue', '555', 'open', NULL, '2026-04-17 06:43:43'),
(5, 8, '213@gmail.com', 'Refund Issue', '555', 'open', NULL, '2026-04-17 06:44:27'),
(6, 9, 'memo@gmail.com', 'Missing Item', '545hhh', 'Closed', NULL, '2026-04-17 06:44:35'),
(7, 9, 'memo@gmail.com', 'Missing Item', '545hhh', 'open', NULL, '2026-04-17 06:44:41'),
(8, 10, 'm222emo@gmail.com', 'Missing Item', 'jjj', 'open', NULL, '2026-04-17 06:45:09'),
(9, 12, 'm222emo@gmail.com', 'Wrong Product', 'moza', 'Closed', NULL, '2026-04-17 06:49:24');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_number` varchar(100) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `delivery_date` date DEFAULT NULL,
  `checked_by_admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `customer_email`, `status`, `delivery_date`, `checked_by_admin`, `created_at`) VALUES
(1, 'ORD-1001', 'customer1@test.com', 'Processing', '2026-05-02', 0, '2026-04-16 23:49:30'),
(2, 'ORD-1002', 'customer2@test.com', 'Shipped', '2026-05-05', 0, '2026-04-16 23:49:30'),
(3, 'ORD-1003', 'customer3@test.com', 'Delivered', '2026-04-20', 0, '2026-04-16 23:49:30'),
(4, '55', '213@gmail.com', 'Verified', '2026-04-22', 0, '2026-04-16 23:49:30'),
(5, '4', '213@gmail.com', 'Processing', '2026-04-22', 0, '2026-04-16 23:49:30'),
(6, '22', 'memo@gmail.com', 'Pending Review', '2026-04-22', 0, '2026-04-16 23:51:10'),
(7, '222', 'm222emo@gmail.com', 'Pending Review', '2026-04-22', 0, '2026-04-16 23:52:45'),
(8, '54', '213@gmail.com', 'Verified', '2026-04-22', 0, '2026-04-17 06:43:43'),
(9, '445', 'memo@gmail.com', 'Verified', '2026-04-22', 0, '2026-04-17 06:44:35'),
(10, '45', 'm222emo@gmail.com', 'Verified', '2026-04-22', 0, '2026-04-17 06:45:08'),
(11, '67', 'memo@gmail.com', 'Pending Review', '2026-04-22', 0, '2026-04-17 06:46:50'),
(12, '68', 'm222emo@gmail.com', 'Checked', '2026-04-22', 0, '2026-04-17 06:47:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `fk_complaints_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
