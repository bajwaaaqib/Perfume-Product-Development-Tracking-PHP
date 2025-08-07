-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 07, 2025 at 02:09 AM
-- Server version: 5.7.23-23
-- PHP Version: 8.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `marcoluc_npd`
--

-- --------------------------------------------------------

--
-- Table structure for table `perfume_products`
--

CREATE TABLE `perfume_products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `brand_name` varchar(255) DEFAULT NULL,
  `batch_no` varchar(100) DEFAULT NULL,
  `budget` varchar(100) DEFAULT NULL,
  `fragrance_type` text,
  `target_audience` text,
  `design_style` text,
  `box_packaging_type` text,
  `bottle_coating` text,
  `box_finishing` text,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(50) DEFAULT 'Tasks To Do',
  `color` text,
  `size` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `perfume_products`
--

INSERT INTO `perfume_products` (`id`, `product_name`, `brand_name`, `batch_no`, `budget`, `fragrance_type`, `target_audience`, `design_style`, `box_packaging_type`, `bottle_coating`, `box_finishing`, `created_by`, `created_at`, `status`, `color`, `size`) VALUES
(9, ' 400ml Jawhara AF', 'SHANGANI', 'SHGCK0825', '', 'Floral', 'Unisex', 'Oriental', 'Metallic Board', '', 'Glossy', 5, '2025-08-04 08:42:17', 'In Progress', NULL, NULL),
(10, '400ml Mulki AF', 'SHANGANI', 'SHGCK0825', '', 'Floral', 'Unisex', 'Oriental', 'Metallic Board', '', 'Glossy', 5, '2025-08-04 08:43:27', 'In Progress', NULL, NULL),
(11, '400ml Rashida AF', 'SHANGANI', 'SHGCK0825', '', 'Floral', 'Unisex', 'Oriental', 'Metallic Board', '', 'Glossy', 5, '2025-08-04 08:44:17', 'In Progress', NULL, NULL),
(12, '400ml Mariya AF', 'SHANGANI', 'SHGCK0825', '', 'Floral', 'Unisex', 'Oriental', 'Metallic Board', '', 'Glossy', 5, '2025-08-04 08:44:52', 'In Progress', NULL, NULL),
(13, '300ml Jawhara AF', 'ARD PERFUMES', 'ARDCK0825', '', 'Floral', 'Unisex', 'Oriental', 'Metallic Board', '', 'Glossy', 5, '2025-08-04 08:46:19', 'Approved Internally', NULL, NULL),
(14, '300ml Janan AF', 'ARD PERFUMES', 'ARDCK0825', '', 'Floral', 'Unisex', 'Oriental', 'Metallic Board', '', 'Glossy', 5, '2025-08-04 08:46:51', 'Approved Internally', NULL, NULL),
(15, '300ml Mulki AF', 'ARD PERFUMES', 'ARDCK0825', '', 'Floral', 'Unisex', 'Oriental', 'Metallic Board', '', 'Glossy', 5, '2025-08-04 08:47:22', 'Approved Internally', NULL, NULL),
(16, '300ml Rashida AF', 'ARD PERFUMES', 'ARDCK0825', '', 'Fresh', 'Unisex', 'Oriental', 'Metallic Board', '', 'Glossy', 5, '2025-08-04 08:48:01', 'Approved Internally', NULL, NULL),
(17, '300ml Mariya AF', 'ARD PERFUMES', 'ARDCK0825', '', 'Oriental', 'Unisex', 'Oriental', 'Metallic Board', '', 'Glossy', 5, '2025-08-04 08:48:44', 'Approved Internally', 'Pink', '80x80x255'),
(18, 'Zuhran EDP', 'MARCO LUCIO', 'ARDM072025', '', 'Fresh', 'Men', 'Modern', 'Metallic Board', 'Glossy', 'Glossy', 5, '2025-08-04 08:50:39', 'Completion', NULL, NULL),
(19, 'Firdan EDP', 'MARCO LUCIO', 'ARDM072025', '', 'Citrus', 'Men', 'Modern', 'Metallic Board', 'Glossy', 'Glossy', 5, '2025-08-04 08:51:33', 'Completion', NULL, NULL),
(20, 'Odette EDP', 'MARCO LUCIO', 'ARDM072025', '', 'Floral', 'Women', 'Modern', 'Foodboard', 'Half Coating', 'Matte', 5, '2025-08-04 08:52:20', 'Completion', NULL, NULL),
(21, 'Bleu de Patron EDP', 'MARCO LUCIO', 'MARM0825', '', 'Citrus', 'Men', 'Modern', 'Metallic Board', 'Glossy', 'Glossy', 5, '2025-08-04 08:54:12', 'Tasks To Do', NULL, NULL),
(22, 'Secret Men EDP', 'ARD PERFUMES', 'ARDM0825', '', 'Woody', 'Men', 'Modern', 'Metallic Board', 'Glossy', 'Glossy', 5, '2025-08-04 08:55:10', 'Tasks To Do', NULL, NULL),
(23, 'Bint Al Arab EDP', 'ARD PERFUMES', 'ARDCK0825', '', 'Floral', '', 'Oriental', 'Metallic Board', 'Transparent', 'Glossy', 5, '2025-08-04 08:56:16', 'Tasks To Do', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `printing_approval`
--

CREATE TABLE `printing_approval` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch_number` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `brand_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `printing_company` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `checked_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entry_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `printing_approval`
--

INSERT INTO `printing_approval` (`id`, `product_name`, `batch_number`, `brand_name`, `printing_company`, `checked_by`, `status`, `entry_date`) VALUES
(2, 'Jawhara 6ml', 'ARDRW082025', 'ARD PERFUMES', 'Al Ravi', 'Aaqib', 'Approved', '2025-08-05 06:00:00'),
(3, 'Odette/Firdan/Zuhran Mater/Inner CTN', '016', 'MARCO LUCIO', 'MAHNOOR', 'Aaqib', 'Approved', '2025-08-06 06:00:00'),
(5, '6ml Roll-On Master CTN', '010', 'ARD PERFUMES', 'MAHNOOR', 'Aaqib', 'Approved', '2025-08-06 06:00:00'),
(6, '6ml Roll-On Master CTN', '021', 'ARD PERFUMES', 'MAHNOOR', 'Aaqib', 'Approved', '2025-08-06 06:00:00'),
(9, 'Jawhara 6ml Label', '', 'ARD PERFUMES', 'Star Label', 'Aaqib', 'Approved', '2025-08-06 06:00:00'),
(10, 'Jawhara 100ml Label', '', 'ARD PERFUMES', 'Star Label', 'Aaqib', 'Approved', '2025-08-06 06:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `Product_quality_check`
--

CREATE TABLE `Product_quality_check` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch_number` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `brand_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `printing_company` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `presented_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `checked_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entry_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Product_quality_check`
--

INSERT INTO `Product_quality_check` (`id`, `product_name`, `batch_number`, `brand_name`, `printing_company`, `presented_by`, `checked_by`, `status`, `entry_date`) VALUES
(4, 'Honor EDP', 'ARDRW072025', 'MARCO LUCIO', 'Al Ravi', 'Farhad', 'Aaqib', 'Approved', '2025-08-06 06:00:00'),
(5, 'Zuhran Bottle', '', 'MARCO LUCIO', 'Desert Rose', 'Farhad', 'Aaqib', 'Rejected', '2025-08-07 06:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `product_tasks`
--

CREATE TABLE `product_tasks` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `task_name` varchar(255) DEFAULT NULL,
  `status` enum('to_do','pending','in_progress','approved_internally','printing_approval','completion') DEFAULT 'to_do',
  `completion_percent` int(11) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `invite_token` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `invite_token`, `role`, `created_at`) VALUES
(1, 'info@aaqibbajwa.com', '$2y$10$mz5xreJ6GUmMLRh1QNTRdu3WayciVabzoMZwIxJwT8icpb1bqOpFu', NULL, 'admin', '2025-08-04 06:13:06'),
(2, 'aaqibbajwa0@gmail.com', NULL, '30fcfd2fb093e7e9019984912b2799af', 'user', '2025-08-04 06:14:41'),
(3, 'bajwaaaqib@gmail.com', NULL, 'e75e5742efad198007cf1b345ba18244', 'user', '2025-08-04 08:17:59'),
(4, 'aaqib@example.com', '$2y$10$E7ggXDNoFkzeHRMeK1q.ZOuM6WLMKEJbruBDfg/YKAQUj.BWI7eA.', NULL, 'user', '2025-08-04 10:13:00'),
(5, 'info@ardperfumes.com', '$2y$10$3UaEwWu4Qj/VGaG2AHzzpOSqbVYCErcnonVxEKhZLcYtt1HhffJQK', NULL, 'admin', '2025-08-04 11:08:34');

-- --------------------------------------------------------

--
-- Table structure for table `weekly_reports`
--

CREATE TABLE `weekly_reports` (
  `id` int(11) NOT NULL,
  `report_date` date NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `weekly_reports`
--

INSERT INTO `weekly_reports` (`id`, `report_date`, `content`, `created_at`) VALUES
(7, '2025-08-07', '- New Email Creation for Logistic & Warehouse\r\n- Landline Installation for Logistic & Warehouse\r\n- 400ml Air Freshener artwork designs - (Jawhara, Mulki, Rashida, Mariya)\r\n- Social Media Content design one daily basis\r\n- Product Photography and Editing of Zuhran, Firdan, Odette EDP\r\n- 300ml Air Freshener artwork designs - (Jawhara, Mulki, Rashida, Mariya, Janan )\r\n- Coordination with Vendors/Suppliers for brand consistency\r\n- Meeting Room Landline (IP Phone) Fixed\r\n- Website Plugin Optimized\r\n- operations@ardperfumes.com Email Removed/Deleted\r\n- chemist@ardperfumes.com Email Removed/Deleted after backup', '2025-08-07 05:50:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `perfume_products`
--
ALTER TABLE `perfume_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `printing_approval`
--
ALTER TABLE `printing_approval`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Product_quality_check`
--
ALTER TABLE `Product_quality_check`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_tasks`
--
ALTER TABLE `product_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `weekly_reports`
--
ALTER TABLE `weekly_reports`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `perfume_products`
--
ALTER TABLE `perfume_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `printing_approval`
--
ALTER TABLE `printing_approval`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `Product_quality_check`
--
ALTER TABLE `Product_quality_check`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product_tasks`
--
ALTER TABLE `product_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `weekly_reports`
--
ALTER TABLE `weekly_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `perfume_products`
--
ALTER TABLE `perfume_products`
  ADD CONSTRAINT `perfume_products_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `product_tasks`
--
ALTER TABLE `product_tasks`
  ADD CONSTRAINT `product_tasks_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `perfume_products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
