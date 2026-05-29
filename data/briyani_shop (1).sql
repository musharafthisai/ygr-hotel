-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2026 at 01:27 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `briyani_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(200) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `location`, `created_at`) VALUES
(2, 'Karapakkam', '', '2026-05-20 13:04:08'),
(3, 'Kelambakkam', '', '2026-05-20 13:04:31');

-- --------------------------------------------------------

--
-- Table structure for table `daily_entries`
--

CREATE TABLE `daily_entries` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `entry_date` date NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `quantity` decimal(10,2) DEFAULT 0.00,
  `unit_price` decimal(10,2) DEFAULT 0.00,
  `amount` decimal(10,2) DEFAULT 0.00,
  `payment_mode` enum('CASH','GPAY') NOT NULL DEFAULT 'CASH',
  `entered_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_payments`
--

CREATE TABLE `daily_payments` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `entry_date` date NOT NULL,
  `cash_amount` decimal(10,2) DEFAULT 0.00,
  `gpay_amount` decimal(10,2) DEFAULT 0.00,
  `entered_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_rates`
--

CREATE TABLE `item_rates` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `rate` decimal(10,2) DEFAULT 0.00,
  `category` enum('sales','expense') NOT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_rates`
--

INSERT INTO `item_rates` (`id`, `branch_id`, `item_name`, `rate`, `category`, `sort_order`) VALUES
(17, 2, 'Chicken Briyani (Day)', 180.00, 'sales', 1),
(18, 2, 'Chicken Briyani (Eve)', 180.00, 'sales', 2),
(19, 2, 'Chicken Briyani (Nig)', 180.00, 'sales', 3),
(20, 2, 'Mutton Briyani', 280.00, 'sales', 4),
(21, 2, 'Meat', 0.00, 'expense', 5),
(22, 2, 'Vegetable', 0.00, 'expense', 6),
(23, 2, 'Stock', 0.00, 'expense', 7),
(24, 2, 'Cylinder', 0.00, 'expense', 8),
(25, 2, 'Fish/Prawn', 0.00, 'expense', 9),
(26, 2, 'Curd', 0.00, 'expense', 10),
(27, 2, 'Kubbus', 0.00, 'expense', 11),
(28, 2, 'Gas', 0.00, 'expense', 12),
(29, 2, 'Beverages', 0.00, 'expense', 13),
(30, 2, 'Tea / Others', 0.00, 'expense', 14),
(31, 2, 'Salary', 0.00, 'expense', 15),
(32, 2, 'RENT / EB', 0.00, 'expense', 16),
(48, 3, 'Beverages', 0.00, 'expense', 13),
(49, 3, 'Chicken Briyani (Day)', 180.00, 'sales', 1),
(50, 3, 'Chicken Briyani (Eve)', 180.00, 'sales', 2),
(51, 3, 'Chicken Briyani (Nig)', 180.00, 'sales', 3),
(52, 3, 'Curd', 0.00, 'expense', 10),
(53, 3, 'Cylinder', 0.00, 'expense', 8),
(54, 3, 'Fish/Prawn', 0.00, 'expense', 9),
(55, 3, 'Gas', 0.00, 'expense', 12),
(56, 3, 'Kubbus', 0.00, 'expense', 11),
(57, 3, 'Meat', 0.00, 'expense', 5),
(58, 3, 'Mutton Briyani', 280.00, 'sales', 4),
(59, 3, 'RENT / EB', 0.00, 'expense', 16),
(60, 3, 'Salary', 0.00, 'expense', 15),
(61, 3, 'Stock', 0.00, 'expense', 7),
(62, 3, 'Tea / Others', 0.00, 'expense', 14),
(63, 3, 'Vegetable', 0.00, 'expense', 6);

-- --------------------------------------------------------

--
-- Table structure for table `online_sales`
--

CREATE TABLE `online_sales` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `sale_date` date NOT NULL,
  `platform` enum('Swiggy','Zomato') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `entered_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('owner','branch_admin','staff') NOT NULL,
  `is_active` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `allowed_categories` varchar(50) DEFAULT 'all',
  `totp_secret` varchar(32) DEFAULT NULL,
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `branch_id`, `name`, `username`, `email`, `password`, `role`, `is_active`, `created_at`, `allowed_categories`, `totp_secret`, `reset_token`, `reset_token_expiry`) VALUES
(8, NULL, '', 'admin', 'rahulrahuli1999@gmail.com', '$2y$10$o5nJBb896UPw7eAdNBSiU.tIQwTx/It/WZtqgq6wcsVarezvC9Ieu', 'owner', 1, '2026-05-22 10:13:50', 'all', NULL, '350d9a9b46b330c37e8cc15ed6cfe0bfcd0178caf8ce771b47a64323599c34e4', '2026-05-22 17:39:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `daily_entries`
--
ALTER TABLE `daily_entries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_entry` (`branch_id`,`entry_date`,`item_name`),
  ADD KEY `entered_by` (`entered_by`);

--
-- Indexes for table `daily_payments`
--
ALTER TABLE `daily_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_daily_payment` (`branch_id`,`entry_date`),
  ADD KEY `entered_by` (`entered_by`);

--
-- Indexes for table `item_rates`
--
ALTER TABLE `item_rates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_rate` (`branch_id`,`item_name`);

--
-- Indexes for table `online_sales`
--
ALTER TABLE `online_sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `branch_id` (`branch_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `daily_entries`
--
ALTER TABLE `daily_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `daily_payments`
--
ALTER TABLE `daily_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `item_rates`
--
ALTER TABLE `item_rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `online_sales`
--
ALTER TABLE `online_sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `daily_entries`
--
ALTER TABLE `daily_entries`
  ADD CONSTRAINT `daily_entries_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `daily_entries_ibfk_2` FOREIGN KEY (`entered_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `daily_payments`
--
ALTER TABLE `daily_payments`
  ADD CONSTRAINT `daily_payments_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `daily_payments_ibfk_2` FOREIGN KEY (`entered_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `online_sales`
--
ALTER TABLE `online_sales`
  ADD CONSTRAINT `online_sales_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
