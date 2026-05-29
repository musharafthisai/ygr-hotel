-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 27, 2026 at 11:26 AM
-- Server version: 11.8.6-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u777110831_briyani_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `branch_name` varchar(100) NOT NULL,
  `location` varchar(200) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `branch_name`, `location`, `is_active`, `created_at`) VALUES
(2, 'Karapakkam', '', 1, '2026-05-20 07:34:08'),
(3, 'Kelambakkam', '', 1, '2026-05-20 07:34:31');

-- --------------------------------------------------------

--
-- Table structure for table `daily_entries`
--

CREATE TABLE `daily_entries` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `entry_date` date NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_mode` enum('CASH','GPAY') NOT NULL DEFAULT 'CASH',
  `entered_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `daily_entries`
--

INSERT INTO `daily_entries` (`id`, `branch_id`, `entry_date`, `item_name`, `quantity`, `unit_price`, `amount`, `payment_mode`, `entered_by`, `created_at`, `updated_at`) VALUES
(9, 2, '2026-05-22', 'Chicken Briyani (Day)', 35.00, 800.00, 28000.00, 'CASH', 8, '2026-05-22 06:18:41', '2026-05-22 06:18:41'),
(10, 2, '2026-05-22', 'Chicken Briyani (Eve)', 8.00, 800.00, 6400.00, 'CASH', 8, '2026-05-22 06:18:41', '2026-05-22 06:18:41'),
(11, 2, '2026-05-22', 'Meat', 1.00, 12007.00, 12007.00, 'CASH', 8, '2026-05-22 06:18:41', '2026-05-22 06:18:41'),
(12, 2, '2026-05-22', 'Vegetable', 1.00, 1432.00, 1432.00, 'CASH', 8, '2026-05-22 06:18:41', '2026-05-22 06:18:41'),
(13, 2, '2026-05-22', 'Stock', 1.00, 7699.00, 7699.00, 'CASH', 8, '2026-05-22 06:18:41', '2026-05-22 06:18:41'),
(14, 2, '2026-05-22', 'Fish/Prawn', 1.00, 1792.00, 1792.00, 'CASH', 8, '2026-05-22 06:18:41', '2026-05-22 06:18:41'),
(15, 2, '2026-05-22', 'Curd', 6.00, 55.00, 330.00, 'CASH', 8, '2026-05-22 06:18:41', '2026-05-22 06:18:41'),
(16, 2, '2026-05-22', 'Kubbus', 100.00, 6.00, 600.00, 'CASH', 8, '2026-05-22 06:18:41', '2026-05-22 06:18:41'),
(17, 2, '2026-05-22', 'Gas', 1.00, 600.00, 600.00, 'CASH', 8, '2026-05-22 06:18:41', '2026-05-22 06:18:41'),
(18, 2, '2026-05-22', 'Tea / Others', 1.00, 4380.00, 4380.00, 'CASH', 8, '2026-05-22 06:18:41', '2026-05-22 06:18:41'),
(19, 2, '2026-05-22', 'Salary', 1.00, 7400.00, 7400.00, 'CASH', 8, '2026-05-22 06:18:41', '2026-05-22 06:18:41'),
(20, 2, '2026-05-22', 'RENT / EB', 1.00, 2000.00, 2000.00, 'CASH', 8, '2026-05-22 06:18:41', '2026-05-22 06:18:41'),
(21, 3, '2026-05-03', 'Chicken Briyani [ Day ]', 35.00, 800.00, 28000.00, 'CASH', 8, '2026-05-22 06:52:35', '2026-05-22 06:52:35'),
(22, 3, '2026-05-03', 'Chicken Briyani [ Eve ]', 8.00, 800.00, 6400.00, 'CASH', 8, '2026-05-22 06:52:35', '2026-05-22 06:52:35'),
(23, 3, '2026-05-03', 'Meat', 1.00, 12007.00, 12007.00, 'CASH', 8, '2026-05-22 06:52:35', '2026-05-22 06:52:35'),
(24, 3, '2026-05-03', 'Vegetable', 1.00, 1432.00, 1432.00, 'CASH', 8, '2026-05-22 06:52:35', '2026-05-22 06:52:35'),
(25, 3, '2026-05-03', 'Stock', 1.00, 7699.00, 7699.00, 'CASH', 8, '2026-05-22 06:52:35', '2026-05-22 06:52:35'),
(26, 3, '2026-05-03', 'Fish/Prawn', 1.00, 1792.00, 1792.00, 'CASH', 8, '2026-05-22 06:52:35', '2026-05-22 06:52:35'),
(27, 3, '2026-05-03', 'Curd', 6.00, 55.00, 330.00, 'CASH', 8, '2026-05-22 06:52:35', '2026-05-22 06:52:35'),
(28, 3, '2026-05-03', 'Kubbus', 100.00, 6.00, 600.00, 'CASH', 8, '2026-05-22 06:52:35', '2026-05-22 06:52:35'),
(29, 3, '2026-05-03', 'CNG [ Petrol ]', 1.00, 600.00, 600.00, 'CASH', 8, '2026-05-22 06:52:35', '2026-05-22 06:52:35'),
(30, 3, '2026-05-03', 'Tea/ Other', 1.00, 4380.00, 4380.00, 'CASH', 8, '2026-05-22 06:52:35', '2026-05-22 06:52:35'),
(31, 3, '2026-05-03', 'Salaray', 1.00, 7400.00, 7400.00, 'CASH', 8, '2026-05-22 06:52:35', '2026-05-22 06:52:35'),
(32, 3, '2026-05-03', 'RENT / EB', 1.00, 2000.00, 2000.00, 'CASH', 8, '2026-05-22 06:52:35', '2026-05-22 06:52:35'),
(33, 3, '2026-05-04', 'Chicken Briyani [ Day ]', 15.00, 800.00, 12000.00, 'CASH', 8, '2026-05-22 06:55:33', '2026-05-22 06:55:33'),
(34, 3, '2026-05-04', 'Chicken Briyani [ Eve ]', 12.00, 800.00, 9600.00, 'CASH', 8, '2026-05-22 06:55:33', '2026-05-22 06:55:33'),
(35, 3, '2026-05-04', 'Meat', 1.00, 9374.00, 9374.00, 'CASH', 8, '2026-05-22 06:55:33', '2026-05-22 06:55:33'),
(36, 3, '2026-05-04', 'Vegetable', 1.00, 1345.00, 1345.00, 'CASH', 8, '2026-05-22 06:55:33', '2026-05-22 06:55:33'),
(37, 3, '2026-05-04', 'Stock', 1.00, 7876.00, 7876.00, 'CASH', 8, '2026-05-22 06:55:33', '2026-05-22 06:55:33'),
(38, 3, '2026-05-04', 'Cylinder', 1.00, 4000.00, 4000.00, 'CASH', 8, '2026-05-22 06:55:33', '2026-05-22 06:55:33'),
(39, 3, '2026-05-04', 'Curd', 6.00, 55.00, 330.00, 'CASH', 8, '2026-05-22 06:55:33', '2026-05-22 06:55:33'),
(40, 3, '2026-05-04', 'Kubbus', 100.00, 6.00, 600.00, 'CASH', 8, '2026-05-22 06:55:33', '2026-05-22 06:55:33'),
(41, 3, '2026-05-04', 'CNG [ Petrol ]', 1.00, 600.00, 600.00, 'CASH', 8, '2026-05-22 06:55:33', '2026-05-22 06:55:33'),
(42, 3, '2026-05-04', 'Tea/ Other', 1.00, 5690.00, 5690.00, 'CASH', 8, '2026-05-22 06:55:33', '2026-05-22 06:55:33'),
(43, 3, '2026-05-04', 'Salaray', 1.00, 7400.00, 7400.00, 'CASH', 8, '2026-05-22 06:55:33', '2026-05-22 06:55:33'),
(44, 3, '2026-05-04', 'RENT / EB', 1.00, 2000.00, 2000.00, 'CASH', 8, '2026-05-22 06:55:33', '2026-05-22 06:55:33'),
(45, 3, '2026-05-06', 'Chicken Briyani [ Day ]', 18.00, 800.00, 14400.00, 'CASH', 8, '2026-05-22 06:58:25', '2026-05-22 06:58:25'),
(46, 3, '2026-05-06', 'Chicken Briyani [ Eve ]', 12.00, 800.00, 9600.00, 'CASH', 8, '2026-05-22 06:58:25', '2026-05-22 06:58:25'),
(47, 3, '2026-05-06', 'Meat', 1.00, 13651.00, 13651.00, 'CASH', 8, '2026-05-22 06:58:25', '2026-05-22 06:58:25'),
(48, 3, '2026-05-06', 'Vegetable', 1.00, 1505.00, 1505.00, 'CASH', 8, '2026-05-22 06:58:25', '2026-05-22 06:58:25'),
(49, 3, '2026-05-06', 'Stock', 1.00, 10870.00, 10870.00, 'CASH', 8, '2026-05-22 06:58:25', '2026-05-22 06:58:25'),
(50, 3, '2026-05-06', 'Cylinder', 1.00, 4000.00, 4000.00, 'CASH', 8, '2026-05-22 06:58:25', '2026-05-22 06:58:25'),
(51, 3, '2026-05-06', 'Curd', 6.00, 55.00, 330.00, 'CASH', 8, '2026-05-22 06:58:25', '2026-05-22 06:58:25'),
(52, 3, '2026-05-06', 'Kubbus', 100.00, 6.00, 600.00, 'CASH', 8, '2026-05-22 06:58:25', '2026-05-22 06:58:25'),
(53, 3, '2026-05-06', 'CNG [ Petrol ]', 1.00, 600.00, 600.00, 'CASH', 8, '2026-05-22 06:58:25', '2026-05-22 06:58:25'),
(54, 3, '2026-05-06', 'Tea/ Other', 1.00, 1650.00, 1650.00, 'CASH', 8, '2026-05-22 06:58:25', '2026-05-22 06:58:25'),
(55, 3, '2026-05-06', 'Salaray', 1.00, 7400.00, 7400.00, 'CASH', 8, '2026-05-22 06:58:25', '2026-05-22 06:58:25'),
(56, 3, '2026-05-06', 'RENT / EB', 1.00, 2000.00, 2000.00, 'CASH', 8, '2026-05-22 06:58:25', '2026-05-22 06:58:25'),
(57, 3, '2026-05-07', 'Chicken Briyani [ Day ]', 18.00, 800.00, 14400.00, 'CASH', 8, '2026-05-22 07:00:27', '2026-05-22 07:00:27'),
(58, 3, '2026-05-07', 'Chicken Briyani [ Eve ]', 10.00, 800.00, 8000.00, 'CASH', 8, '2026-05-22 07:00:27', '2026-05-22 07:00:27'),
(59, 3, '2026-05-07', 'Meat', 1.00, 15860.00, 15860.00, 'CASH', 8, '2026-05-22 07:00:27', '2026-05-22 07:00:27'),
(60, 3, '2026-05-07', 'Vegetable', 1.00, 1705.00, 1705.00, 'CASH', 8, '2026-05-22 07:00:27', '2026-05-22 07:00:27'),
(61, 3, '2026-05-07', 'Stock', 1.00, 5304.00, 5304.00, 'CASH', 8, '2026-05-22 07:00:27', '2026-05-22 07:00:27'),
(62, 3, '2026-05-07', 'Curd', 6.00, 55.00, 330.00, 'CASH', 8, '2026-05-22 07:00:27', '2026-05-22 07:00:27'),
(63, 3, '2026-05-07', 'Kubbus', 100.00, 6.00, 600.00, 'CASH', 8, '2026-05-22 07:00:27', '2026-05-22 07:00:27'),
(64, 3, '2026-05-07', 'CNG [ Petrol ]', 1.00, 600.00, 600.00, 'CASH', 8, '2026-05-22 07:00:27', '2026-05-22 07:00:27'),
(65, 3, '2026-05-07', 'Tea/ Other', 1.00, 1810.00, 1810.00, 'CASH', 8, '2026-05-22 07:00:27', '2026-05-22 07:00:27'),
(66, 3, '2026-05-07', 'Salaray', 1.00, 7400.00, 7400.00, 'CASH', 8, '2026-05-22 07:00:27', '2026-05-22 07:00:27'),
(67, 3, '2026-05-07', 'RENT / EB', 1.00, 2000.00, 2000.00, 'CASH', 8, '2026-05-22 07:00:27', '2026-05-22 07:00:27'),
(68, 3, '2026-05-08', 'Chicken Briyani [ Day ]', 18.00, 800.00, 14400.00, 'CASH', 8, '2026-05-22 07:03:01', '2026-05-22 07:03:01'),
(69, 3, '2026-05-08', 'Chicken Briyani [ Eve ]', 10.00, 800.00, 8000.00, 'CASH', 8, '2026-05-22 07:03:01', '2026-05-22 07:03:01'),
(70, 3, '2026-05-08', 'Meat', 1.00, 13686.00, 13686.00, 'CASH', 8, '2026-05-22 07:03:01', '2026-05-22 07:03:01'),
(71, 3, '2026-05-08', 'Vegetable', 1.00, 1189.00, 1189.00, 'CASH', 8, '2026-05-22 07:03:01', '2026-05-22 07:03:01'),
(72, 3, '2026-05-08', 'Stock', 1.00, 11360.00, 11360.00, 'CASH', 8, '2026-05-22 07:03:01', '2026-05-22 07:03:01'),
(73, 3, '2026-05-08', 'Cylinder', 1.00, 4000.00, 4000.00, 'CASH', 8, '2026-05-22 07:03:01', '2026-05-22 07:03:01'),
(74, 3, '2026-05-08', 'Curd', 6.00, 55.00, 330.00, 'CASH', 8, '2026-05-22 07:03:01', '2026-05-22 07:03:01'),
(75, 3, '2026-05-08', 'Kubbus', 100.00, 6.00, 600.00, 'CASH', 8, '2026-05-22 07:03:01', '2026-05-22 07:03:01'),
(76, 3, '2026-05-08', 'CNG [ Petrol ]', 1.00, 600.00, 600.00, 'CASH', 8, '2026-05-22 07:03:01', '2026-05-22 07:03:01'),
(77, 3, '2026-05-08', 'Tea/ Other', 1.00, 7000.00, 7000.00, 'CASH', 8, '2026-05-22 07:03:01', '2026-05-22 07:03:01'),
(78, 3, '2026-05-08', 'Salaray', 1.00, 7400.00, 7400.00, 'CASH', 8, '2026-05-22 07:03:01', '2026-05-22 07:03:01'),
(79, 3, '2026-05-08', 'RENT / EB', 1.00, 2000.00, 2000.00, 'CASH', 8, '2026-05-22 07:03:01', '2026-05-22 07:03:01'),
(80, 3, '2026-05-09', 'Chicken Briyani [ Day ]', 18.00, 800.00, 14400.00, 'CASH', 8, '2026-05-22 07:04:59', '2026-05-22 07:04:59'),
(81, 3, '2026-05-09', 'Chicken Briyani [ Eve ]', 12.00, 800.00, 9600.00, 'CASH', 8, '2026-05-22 07:04:59', '2026-05-22 07:04:59'),
(82, 3, '2026-05-09', 'Meat', 1.00, 16028.00, 16028.00, 'CASH', 8, '2026-05-22 07:04:59', '2026-05-22 07:04:59'),
(83, 3, '2026-05-09', 'Vegetable', 1.00, 1570.00, 1570.00, 'CASH', 8, '2026-05-22 07:04:59', '2026-05-22 07:04:59'),
(84, 3, '2026-05-09', 'Stock', 1.00, 10319.00, 10319.00, 'CASH', 8, '2026-05-22 07:04:59', '2026-05-22 07:04:59'),
(85, 3, '2026-05-09', 'Cylinder', 1.00, 4100.00, 4100.00, 'CASH', 8, '2026-05-22 07:04:59', '2026-05-22 07:04:59'),
(86, 3, '2026-05-09', 'Curd', 6.00, 55.00, 330.00, 'CASH', 8, '2026-05-22 07:04:59', '2026-05-22 07:04:59'),
(87, 3, '2026-05-09', 'Kubbus', 100.00, 6.00, 600.00, 'CASH', 8, '2026-05-22 07:04:59', '2026-05-22 07:04:59'),
(88, 3, '2026-05-09', 'CNG [ Petrol ]', 1.00, 600.00, 600.00, 'CASH', 8, '2026-05-22 07:04:59', '2026-05-22 07:04:59'),
(89, 3, '2026-05-09', 'Tea/ Other', 1.00, 3000.00, 3000.00, 'CASH', 8, '2026-05-22 07:04:59', '2026-05-22 07:04:59'),
(90, 3, '2026-05-09', 'Salaray', 1.00, 7400.00, 7400.00, 'CASH', 8, '2026-05-22 07:04:59', '2026-05-22 07:04:59'),
(91, 3, '2026-05-09', 'RENT / EB', 1.00, 2000.00, 2000.00, 'CASH', 8, '2026-05-22 07:04:59', '2026-05-22 07:04:59');

-- --------------------------------------------------------

--
-- Table structure for table `daily_payments`
--

CREATE TABLE `daily_payments` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `entry_date` date NOT NULL,
  `cash_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `gpay_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `entered_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `daily_payments`
--

INSERT INTO `daily_payments` (`id`, `branch_id`, `entry_date`, `cash_amount`, `gpay_amount`, `entered_by`, `created_at`, `updated_at`) VALUES
(4, 2, '2026-05-22', 4000.00, 42415.00, 8, '2026-05-22 06:18:41', '2026-05-22 06:18:41'),
(5, 3, '2026-05-03', 4000.00, 42415.00, 8, '2026-05-22 06:52:35', '2026-05-22 06:52:35'),
(6, 3, '2026-05-04', 12530.00, 30890.00, 8, '2026-05-22 06:55:33', '2026-05-22 06:55:33'),
(7, 3, '2026-05-06', 9750.00, 26630.00, 8, '2026-05-22 06:58:25', '2026-05-22 06:58:25'),
(8, 3, '2026-05-07', 9500.00, 25210.00, 8, '2026-05-22 07:00:27', '2026-05-22 07:00:27'),
(9, 3, '2026-05-08', 11590.00, 32745.00, 8, '2026-05-22 07:03:01', '2026-05-22 07:03:01'),
(10, 3, '2026-05-09', 9520.00, 28180.00, 8, '2026-05-22 07:04:59', '2026-05-22 07:04:59');

-- --------------------------------------------------------

--
-- Table structure for table `item_rates`
--

CREATE TABLE `item_rates` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `category` enum('sales','expense') NOT NULL DEFAULT 'sales',
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_rates`
--

INSERT INTO `item_rates` (`id`, `branch_id`, `item_name`, `rate`, `category`, `sort_order`) VALUES
(65, 2, 'Chicken Briyani [ DAY ]', 800.00, 'sales', 1),
(66, 2, 'Chicken Briyani [ Eve ]', 800.00, 'sales', 2),
(67, 2, 'Chicken Briyani [ Nig ]', 800.00, 'sales', 3),
(68, 2, 'Mutton Briyani', 1300.00, 'sales', 4),
(69, 2, 'Meat', 0.00, 'expense', 5),
(70, 2, 'Vegitable', 0.00, 'expense', 6),
(71, 2, 'Stock', 0.00, 'expense', 7),
(72, 2, 'Cylinder', 0.00, 'expense', 8),
(76, 2, 'Fish/Prawn', 0.00, 'expense', 9),
(77, 2, 'Curd', 55.00, 'expense', 10),
(79, 2, 'Kubbus', 6.00, 'expense', 11),
(80, 2, 'CNG [ Petrol ]', 0.00, 'expense', 12),
(81, 2, 'Beverages', 0.00, 'expense', 13),
(82, 2, 'Tea / Others', 0.00, 'expense', 14),
(83, 2, 'Salary', 0.00, 'expense', 15),
(84, 2, 'Rent / EB', 0.00, 'expense', 16),
(85, 3, 'Chicken Briyani [ Day ]', 800.00, 'sales', 1),
(86, 3, 'Chicken Briyani [ Eve ]', 800.00, 'sales', 2),
(87, 3, 'Chicken Briyani [ Nig ]', 800.00, 'sales', 3),
(88, 3, 'Mutton Briyani', 1300.00, 'sales', 4),
(89, 3, 'Meat', 0.00, 'expense', 5),
(90, 3, 'Vegetable', 0.00, 'expense', 6),
(91, 3, 'Stock', 0.00, 'expense', 7),
(92, 3, 'Cylinder', 0.00, 'expense', 8),
(93, 3, 'Fish/Prawn', 0.00, 'expense', 9),
(94, 3, 'Curd', 55.00, 'expense', 10),
(95, 3, 'Kubbus', 6.00, 'expense', 11),
(96, 3, 'CNG [ Petrol ]', 0.00, 'expense', 12),
(97, 3, 'Beverages', 0.00, 'expense', 13),
(98, 3, 'Tea/ Other', 0.00, 'expense', 14),
(99, 3, 'Salaray', 0.00, 'expense', 15),
(100, 3, 'RENT / EB', 0.00, 'expense', 16);

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(100) NOT NULL DEFAULT '',
  `success` tinyint(1) NOT NULL DEFAULT 0,
  `attempted_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `login_attempts`
--

INSERT INTO `login_attempts` (`id`, `ip_address`, `username`, `success`, `attempted_at`) VALUES
(1, '2401:4900:1ce2:4935:11f5:447c:bbf:8929', 'admin', 1, '2026-05-27 08:21:41'),
(2, '2401:4900:1ce2:4935:11f5:447c:bbf:8929', 'admin', 1, '2026-05-27 08:23:56'),
(3, '2401:4900:1ce2:4935:11f5:447c:bbf:8929', 'admin', 1, '2026-05-27 08:27:30'),
(4, '2401:4900:1ce2:4935:11f5:447c:bbf:8929', 'admin', 0, '2026-05-27 11:10:57'),
(5, '2401:4900:1ce2:4935:11f5:447c:bbf:8929', 'admin', 1, '2026-05-27 11:11:02');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `online_sales`
--

INSERT INTO `online_sales` (`id`, `branch_id`, `sale_date`, `platform`, `amount`, `entered_by`, `created_at`) VALUES
(5, 3, '2026-05-09', 'Swiggy', 119450.00, 8, '2026-05-25 01:41:47'),
(6, 3, '2026-05-09', 'Zomato', 91238.00, 8, '2026-05-25 01:42:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL,
  `role` enum('owner','branch_admin','staff') NOT NULL DEFAULT 'staff',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `allowed_categories` varchar(50) DEFAULT 'all',
  `totp_secret` varchar(32) DEFAULT NULL,
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `branch_id`, `name`, `username`, `email`, `password`, `role`, `is_active`, `allowed_categories`, `totp_secret`, `reset_token`, `reset_token_expiry`, `created_at`) VALUES
(8, NULL, '', 'admin', 'rahulrahuli1999@gmail.com', '$2y$10$o5nJBb896UPw7eAdNBSiU.tIQwTx/It/WZtqgq6wcsVarezvC9Ieu', 'owner', 1, 'all', NULL, NULL, NULL, '2026-05-22 04:43:50');

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
  ADD UNIQUE KEY `uk_unique_entry` (`branch_id`,`entry_date`,`item_name`),
  ADD KEY `idx_de_branch_date` (`branch_id`,`entry_date`),
  ADD KEY `idx_de_branch_item` (`branch_id`,`item_name`),
  ADD KEY `idx_de_entered_by` (`entered_by`);

--
-- Indexes for table `daily_payments`
--
ALTER TABLE `daily_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_daily_payment` (`branch_id`,`entry_date`),
  ADD KEY `idx_dp_branch_date` (`branch_id`,`entry_date`),
  ADD KEY `idx_dp_entered_by` (`entered_by`);

--
-- Indexes for table `item_rates`
--
ALTER TABLE `item_rates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_branch_item` (`branch_id`,`item_name`),
  ADD KEY `idx_ir_branch_sort` (`branch_id`,`sort_order`),
  ADD KEY `idx_ir_branch_item` (`branch_id`,`item_name`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ip_time` (`ip_address`,`attempted_at`);

--
-- Indexes for table `online_sales`
--
ALTER TABLE `online_sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_os_branch_date_platform` (`branch_id`,`sale_date`,`platform`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_username` (`username`),
  ADD KEY `idx_users_branch` (`branch_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `daily_payments`
--
ALTER TABLE `daily_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `item_rates`
--
ALTER TABLE `item_rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `online_sales`
--
ALTER TABLE `online_sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  ADD CONSTRAINT `fk_de_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `fk_de_user` FOREIGN KEY (`entered_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `daily_payments`
--
ALTER TABLE `daily_payments`
  ADD CONSTRAINT `fk_dp_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `fk_dp_user` FOREIGN KEY (`entered_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `item_rates`
--
ALTER TABLE `item_rates`
  ADD CONSTRAINT `fk_ir_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`);

--
-- Constraints for table `online_sales`
--
ALTER TABLE `online_sales`
  ADD CONSTRAINT `fk_os_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_usr_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
