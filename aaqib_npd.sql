-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 21, 2025 at 01:15 AM
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
-- Table structure for table `daily_posts`
--

CREATE TABLE `daily_posts` (
  `id` int(11) NOT NULL,
  `platform` enum('Instagram','Facebook','TikTok','LinkedIn') COLLATE utf8_unicode_ci NOT NULL,
  `post_date` date NOT NULL,
  `post_time` time NOT NULL,
  `post_type` enum('Image','Video','Story','Reel') COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `daily_posts`
--



-- --------------------------------------------------------

--
-- Table structure for table `marketing_stats`
--

CREATE TABLE `marketing_stats` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `platform` enum('Instagram','Facebook','TikTok','LinkedIn','Website') COLLATE utf8_unicode_ci NOT NULL,
  `stat_date` date NOT NULL,
  `followers` int(11) DEFAULT NULL,
  `likes` int(11) DEFAULT NULL,
  `comments` int(11) DEFAULT NULL,
  `shares` int(11) DEFAULT NULL,
  `posts_count` int(11) DEFAULT '0',
  `website_views` int(11) DEFAULT NULL,
  `website_clicks` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `marketing_stats`
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
  `size` text,
  `product_type` varchar(50) DEFAULT NULL,
  `comments` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `perfume_products`
--



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


-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `brand` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `products`
--

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
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `invite_token` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--



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


--
-- Indexes for dumped tables
--

--
-- Indexes for table `daily_posts`
--
ALTER TABLE `daily_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `marketing_stats`
--
ALTER TABLE `marketing_stats`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `products`
--
ALTER TABLE `products`
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
-- AUTO_INCREMENT for table `daily_posts`
--
ALTER TABLE `daily_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `marketing_stats`
--
ALTER TABLE `marketing_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `perfume_products`
--
ALTER TABLE `perfume_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `printing_approval`
--
ALTER TABLE `printing_approval`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

--
-- AUTO_INCREMENT for table `Product_quality_check`
--
ALTER TABLE `Product_quality_check`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `product_tasks`
--
ALTER TABLE `product_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `weekly_reports`
--
ALTER TABLE `weekly_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
