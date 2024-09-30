DROP DATABASE IF EXISTS lozodo;
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2024 at 04:46 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `lozodo` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `lozodo`;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lozodo`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `description`) VALUES
(1, 'Action Figures', 'Collectible action figures from various franchises, perfect for play or display.'),
(2, 'Board Games', 'A variety of fun and engaging board games for family and friends.'),
(3, 'Building Blocks', 'Creative building blocks that encourage imagination and development.'),
(4, 'Dolls', 'A wide range of dolls for children of all ages, including fashion dolls and baby dolls.'),
(5, 'Educational Toys', 'Toys designed to enhance learning and cognitive development in young children.'),
(6, 'Puzzles', 'Engaging puzzles for all skill levels, promoting problem-solving and critical thinking.'),
(7, 'Outdoor Toys', 'Toys designed for outdoor play, including sports equipment and gardening sets.'),
(8, 'Stuffed Animals', 'A collection of cuddly stuffed animals for comfort and companionship.'),
(9, 'Vehicles', 'Toy cars, trucks, and other vehicles that spark imagination and play.'),
(10, 'Art Supplies', 'Creative art supplies to inspire artistic expression in children.');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('pending','completed','shipped','delivered','cancelled') DEFAULT 'pending',
  `created_at` datetime NOT NULL,
  `shipping_address` varchar(255) NOT NULL,
  `voucher_code` varchar(50) DEFAULT NULL,
  `cancel_reason` text DEFAULT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `status`, `created_at`, `shipping_address`, `voucher_code`, `cancel_reason`, `updated_at`) VALUES
(1, 101, 'completed', '2024-09-20 10:15:30', '123 Main St, Springfield', 'DISCOUNT10', NULL, '2024-09-25 06:24:42'),
(2, 102, 'pending', '2024-09-21 14:30:45', '456 Elm St, Shelbyville', NULL, NULL, '2024-09-25 06:19:56'),
(3, 103, 'completed', '2024-09-22 09:50:15', '789 Oak St, Capital City', 'WELCOME20', NULL, '2024-09-25 06:19:56'),
(4, 104, 'pending', '2024-09-22 16:22:30', '101 Maple Ave, Ogdenville', NULL, 'Yes', '2024-09-25 06:46:52'),
(5, 105, 'pending', '2024-09-23 11:12:00', '202 Pine St, North Haverbrook', NULL, '', '2024-09-25 06:41:34'),
(6, 106, 'completed', '2024-09-23 08:45:00', '303 Cedar St, Springfield', 'FREESHIP', NULL, '2024-09-25 06:19:56'),
(7, 107, 'cancelled', '2024-09-24 13:35:45', '404 Birch St, Shelbyville', NULL, 'Order Wrong Item', '2024-09-25 06:52:01'),
(8, 108, 'pending', '2024-09-24 12:30:50', '505 Willow St, Capital City', 'DISCOUNT5', '0', '2024-09-25 06:33:34'),
(9, 109, 'completed', '2024-09-25 07:10:05', '606 Palm St, Ogdenville', NULL, NULL, '2024-09-25 06:24:38'),
(10, 110, 'pending', '2024-09-25 09:20:30', '707 Fir St, North Haverbrooki', 'WELCOME20', '0', '2024-09-25 06:33:50');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `amount` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_id`, `product_id`, `quantity`, `amount`) VALUES
(1, 59, 1, 9999.99),
(1, 68, 2, 19999.98),
(2, 69, 1, 9999.99),
(3, 70, 1, 9999.99),
(4, 59, 1, 9999.99),
(5, 68, 1, 9999.99),
(6, 70, 1, 9999.99),
(7, 59, 1, 9999.99),
(8, 68, 1, 9999.99),
(9, 69, 1, 9999.99),
(10, 70, 1, 9999.99);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `payment_method` enum('cod','credit card','bank transfer','e-wallet') NOT NULL,
  `status` enum('pending','completed') DEFAULT 'pending',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `order_id`, `amount`, `payment_method`, `status`, `created_at`) VALUES
(1, 1, 120.50, 'credit card', 'completed', '0000-00-00 00:00:00'),
(2, 2, 45.75, 'e-wallet', 'pending', '0000-00-00 00:00:00'),
(3, 3, 78.99, 'bank transfer', 'completed', '0000-00-00 00:00:00'),
(4, 4, 0.00, 'cod', 'pending', '0000-00-00 00:00:00'),
(5, 5, 99.99, 'credit card', 'completed', '0000-00-00 00:00:00'),
(6, 6, 30.50, 'bank transfer', 'pending', '0000-00-00 00:00:00'),
(7, 7, 150.25, 'e-wallet', 'completed', '0000-00-00 00:00:00'),
(8, 8, 220.00, 'cod', 'pending', '0000-00-00 00:00:00'),
(9, 9, 60.00, 'credit card', 'completed', '0000-00-00 00:00:00'),
(10, 10, 80.75, 'bank transfer', 'pending', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `photo1` varchar(255) DEFAULT NULL,
  `photo2` varchar(255) DEFAULT NULL,
  `photo3` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `date_created` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `brand`, `description`, `price`, `stock`, `photo1`, `photo2`, `photo3`, `video_url`, `category_id`, `date_created`) VALUES
(49, 'Pc1', 'Lenovo', '123', 9999.99, 20, 'mba13-spacegray-config-202402.jpeg', 'Macbook_Pro_M3_Space_Gray_PDP_Image_Position_1__GBEN_854be6b2-c1ef-4f82-91ef-065d0d92e7e7.webp', 'download.webp', 'https://youtu.be/tJPAjqFxNnI', 2, '2024-09-22 01:59:13'),
(50, 'Pc2', 'Asus', '321', 9999.99, 20, 'mba13-spacegray-config-202402.jpeg', 'Macbook_Pro_M3_Space_Gray_PDP_Image_Position_1__GBEN_854be6b2-c1ef-4f82-91ef-065d0d92e7e7.webp', 'download.webp', 'https://youtu.be/tJPAjqFxNnI', 3, '2024-09-22 02:01:40'),
(59, 'Pc3', 'Intel', '123', 9999.99, 20, 'product_66ef0df2c388b3.63559020.jpeg', 'product_66ef0df2c3ac13.24369374.webp', 'product_66ef0df2c3cda5.89635663.webp', 'https://youtu.be/tJPAjqFxNnI', 1, '2024-09-22 02:18:26'),
(68, 'Pc4', 'Nvdia', '123', 9999.99, 20, '66efb9daadc6f_Macbook_Pro_M3_Space_Gray_PDP_Image_Position_1__GBEN_854be6b2-c1ef-4f82-91ef-065d0d92e7e7.webp', '66efb9dab11e5_mba13-spacegray-config-202402.jpeg', '66efb9dab3c35_download.webp', 'https://youtu.be/tJPAjqFxNnI', 2, '2024-09-22 14:31:56'),
(69, 'Pc5', 'AMD', '123', 9999.99, 20, 'mba13-spacegray-config-202402.jpeg', 'Macbook_Pro_M3_Space_Gray_PDP_Image_Position_1__GBEN_854be6b2-c1ef-4f82-91ef-065d0d92e7e7.webp', 'download.webp', 'https://youtu.be/tJPAjqFxNnI', 2, '2024-09-22 14:32:47'),
(70, 'Pc6', 'Dell', '2132', 9999.99, 20, '66efba28ed30b_Macbook_Pro_M3_Space_Gray_PDP_Image_Position_1__GBEN_854be6b2-c1ef-4f82-91ef-065d0d92e7e7.webp', '66efba28f1b11_mba13-spacegray-config-202402.jpeg', '66efba28f2993_download.webp', 'https://youtu.be/tJPAjqFxNnI', 2, '2024-09-22 14:33:15');

-- --------------------------------------------------------

--
-- Table structure for table `product_review`
--

CREATE TABLE `product_review` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review` text DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `token`
--

CREATE TABLE `token` (
  `id` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `expire` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `role` enum('Admin','Member') NOT NULL,
  `login_attempts` int(11) DEFAULT 0,
  `status` enum('inactive','active','blocked','freeze') DEFAULT 'inactive',
  `blocked_until` datetime DEFAULT NULL,
  `registration_date` datetime DEFAULT current_timestamp(),
  `reward_points` int(11) DEFAULT 0,
  `preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`preferences`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `name`, `photo`, `address`, `phone_number`, `role`, `login_attempts`, `status`, `blocked_until`, `registration_date`, `reward_points`, `preferences`) VALUES
(101, 'john.doe@example.com', '$2y$10$R26Pna2EzGgy4QpPj0WevudtzgqkAwy/RHTcMIPVexUNXFnoE1Vr.', 'John Doe', NULL, NULL, NULL, 'Admin', 0, 'active', NULL, '2024-09-30 02:46:21', 100, NULL),
(102, 'jane.smith@example.com', '$2y$10$R26Pna2EzGgy4QpPj0WevudtzgqkAwy/RHTcMIPVexUNXFnoE1Vr.', 'Jane Smith', NULL, NULL, NULL, 'Admin', 0, 'active', NULL, '2024-09-30 02:46:21', 150, NULL),
(103, 'robert.jones@example.com', '$2y$10$R26Pna2EzGgy4QpPj0WevudtzgqkAwy/RHTcMIPVexUNXFnoE1Vr.', 'Robert Jones', NULL, NULL, NULL, 'Member', 0, 'active', NULL, '2024-09-30 02:46:21', 200, NULL),
(104, 'alice.johnson@example.com', '$2y$10$R26Pna2EzGgy4QpPj0WevudtzgqkAwy/RHTcMIPVexUNXFnoE1Vr.', 'Alice Johnson', NULL, NULL, NULL, 'Member', 0, 'active', NULL, '2024-09-30 02:46:21', 50, NULL),
(105, 'michael.brown@example.com', '$2y$10$R26Pna2EzGgy4QpPj0WevudtzgqkAwy/RHTcMIPVexUNXFnoE1Vr.', 'Michael Brown', NULL, NULL, NULL, 'Member', 0, 'active', NULL, '2024-09-30 02:46:21', 80, NULL),
(106, 'emily.davis@example.com', '$2y$10$R26Pna2EzGgy4QpPj0WevudtzgqkAwy/RHTcMIPVexUNXFnoE1Vr.', 'Emily Davis', NULL, NULL, NULL, 'Member', 0, 'active', NULL, '2024-09-30 02:46:21', 120, NULL),
(107, 'chris.wilson@example.com', '$2y$10$R26Pna2EzGgy4QpPj0WevudtzgqkAwy/RHTcMIPVexUNXFnoE1Vr.', 'Chris Wilson', NULL, NULL, NULL, 'Member', 0, 'active', NULL, '2024-09-30 02:46:21', 90, NULL),
(108, 'olivia.miller@example.com', '$2y$10$R26Pna2EzGgy4QpPj0WevudtzgqkAwy/RHTcMIPVexUNXFnoE1Vr.', 'Olivia Miller', NULL, NULL, NULL, 'Member', 0, 'active', NULL, '2024-09-30 02:46:21', 140, NULL),
(109, 'james.moore@example.com', '$2y$10$R26Pna2EzGgy4QpPj0WevudtzgqkAwy/RHTcMIPVexUNXFnoE1Vr.', 'James Moore', NULL, NULL, NULL, 'Member', 0, 'active', NULL, '2024-09-30 02:46:21', 110, NULL),
(110, 'sophia.taylor@example.com', '$2y$10$R26Pna2EzGgy4QpPj0WevudtzgqkAwy/RHTcMIPVexUNXFnoE1Vr.', 'Sophia Taylor', NULL, NULL, NULL, 'Member', 0, 'active', NULL, '2024-09-30 02:46:21', 60, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_category` (`category_id`);

--
-- Indexes for table `product_review`
--
ALTER TABLE `product_review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `token`
--
ALTER TABLE `token`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `product_review`
--
ALTER TABLE `product_review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

--
-- Constraints for table `product_review`
--
ALTER TABLE `product_review`
  ADD CONSTRAINT `product_review_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `product_review_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `token`
--
ALTER TABLE `token`
  ADD CONSTRAINT `token_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
