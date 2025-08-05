-- version 5.2.1
-- https://www.phpmyadmin.net/
-- aaqib_npd

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
-- Table structure for table `printing_approval`
--

CREATE TABLE `printing_approval` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
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
-- Table structure for table `Product_quality_check`
--

CREATE TABLE `Product_quality_check` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `brand_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `printing_company` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `presented_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `checked_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `entry_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `perfume_products`
--
ALTER TABLE `perfume_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `printing_approval`
--
ALTER TABLE `printing_approval`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Product_quality_check`
--
ALTER TABLE `Product_quality_check`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
