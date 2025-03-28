-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2025 at 07:18 AM
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
-- Database: `market_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int(11) NOT NULL,
  `banner_image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `banner_image`, `created_at`, `status`) VALUES
(1, '1741874968_bannerMarket1.png', '2025-03-13 14:09:28', 1),
(2, '1741875465_bannerMarket2.png', '2025-03-13 14:09:38', 1),
(3, '1741874986_bannerMarket3.png', '2025-03-13 14:09:46', 1),
(6, '1741876722_banner4.png', '2025-03-13 14:38:42', 0);

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `zone_number_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `shop_name` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) NOT NULL,
  `booking_date` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `zone_number_id`, `user_id`, `name`, `shop_name`, `phone_number`, `booking_date`, `created_at`, `updated_at`) VALUES
(19, 17, 9, 'เสเหลือง', 'เฮียหมูโดนนู๋แกล้ง', '7777777777', '2025-02-24 15:53:03', '2025-02-24 14:53:03', '2025-02-24 14:55:10'),
(30, 2, 9, 'Phiraphat', 'สลัดผักตักเอง', '0879896545', '2025-03-14 00:45:18', '2025-03-13 17:45:18', '2025-03-13 17:45:18');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `created_at`) VALUES
(1, 'จองแผงต้องทำยังไง?', 'เข้าไปหน้าจองที่ขาย แล้วเลือกแผงที่ต้องการ จากนั้นไปที่แผงของฉันแล้วอัปโหลดสลิปโอนเงินจะมีแอดมินคอยตรวจสอบครับ', '2025-03-14 18:22:59'),
(2, 'เปลี่ยนรหัสผ่านยังไง?', 'เข้าไปที่หน้า profile แล้วทำตามขั้นตอนได้เลยครับ', '2025-03-14 18:26:19');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_slip` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `booking_id`, `user_id`, `payment_slip`, `status`, `created_at`, `updated_at`) VALUES
(9, 19, 9, '1740408817_9929da90-c10c-4910-880b-6fea5c75b1f4.jpeg', 'approved', '2025-02-24 14:53:37', '2025-03-16 01:22:47'),
(17, 30, 9, '1741887975_9929da90-c10c-4910-880b-6fea5c75b1f4.jpeg', 'pending', '2025-03-13 17:46:15', '2025-03-16 01:26:00');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `answered_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','answered') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `user_id`, `question`, `answer`, `created_at`, `answered_at`, `status`) VALUES
(1, 9, 'test', 'reply', '2025-03-15 19:03:51', '2025-03-15 13:22:15', 'answered');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `comment`, `created_at`) VALUES
(1, 11, 'ระบบจองค่อยข้างห่วยเลยระบบยังไม่สมบูรณ์ซักอย่างแก้ไขหน่อยครับ', '2025-03-11 15:19:16'),
(6, 11, 'test', '2025-03-11 15:23:08'),
(7, 11, 'test2', '2025-03-13 12:08:59'),
(10, 11, 'dadadadada', '2025-03-16 01:04:04'),
(11, 11, 'webดีมากเลยครับ', '2025-03-27 06:10:29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `phone`, `profile_pic`, `created_at`, `role`) VALUES
(9, 'kaito', '$2y$10$.q/ekOkaRZJPoJMCuiRg8eto271nm6KkAstPU8Wei4GzT1U1xSws2', 'nook_neerada@gmail.com', '0645946274', '67db2a02488ad.jpg', '2025-02-18 04:37:42', 'user'),
(11, 'admin', '$2y$10$F.qC8Z89JIy68ilsDYnSR.d0fg.9lzgEgTKOLd04oNN4LndD6yzje', 'admin01@gmail.com', '0774546553', NULL, '2025-02-19 12:46:19', 'admin'),
(15, 'admin1', '$2y$10$VEr.IiUARapWbFjN2HrRleGVF7Az8jlhKLbl9aPagw2NkOJB8asVm', 'koontoon.2ch@gmail.com', '0233211223', NULL, '2025-02-19 13:01:15', 'admin'),
(19, 'mama', '$2y$10$TqCwPxYRNcJjx5il4xLr3eXqNluQV7wXDNI8h7vhiKbvFh69FRDGu', 'aff@gmail.com', '0111111111', NULL, '2025-02-20 08:33:21', 'user'),
(25, 'admin7', '$2y$10$e7TcpUamoyuVniM91LihqOW/LSfd7EQg7hvguNHRQMdD1uXdBrbHS', 'adminn@gmail.com', '0646567887', NULL, '2025-03-13 18:11:38', 'admin'),
(27, 'cat007x', '$2y$10$svl8uzroPI2bvGlJlS/Q.e6E8suU4eUYFojyzvi27fiHe2j95iMO6', 'banana.toon2545@gmail.com', '0774546554', '67d61cbc56160.jpeg', '2025-03-15 23:42:33', 'user'),
(28, 'rko', '$2y$10$9VXCHk/F0R1JFGOQy4NwqebJLhGQ.4LjaiS17aei3W1jaZWBFnsYa', 'dd@gmail.com', '0888888888', 'rko_1742090684.jpeg', '2025-03-16 02:04:44', 'user'),
(29, 'nn', '$2y$10$SPUngTl/V7VYCTU2XGfMWOxdCJvr46ln0Y27q/rb8DxrSz8m/I9f6', 'spoyloily@gmail.com', '0774546554', 'default.png', '2025-03-16 02:07:31', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `zone`
--

CREATE TABLE `zone` (
  `id` int(10) NOT NULL,
  `zone` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `zone`
--

INSERT INTO `zone` (`id`, `zone`) VALUES
(1, 'A'),
(2, 'B'),
(3, 'C');

-- --------------------------------------------------------

--
-- Table structure for table `zone_number`
--

CREATE TABLE `zone_number` (
  `id` int(10) NOT NULL,
  `number` varchar(20) NOT NULL,
  `zone` int(10) NOT NULL,
  `status` int(2) NOT NULL COMMENT '0=ว่าง 1=จองแล้ว',
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `zone_number`
--

INSERT INTO `zone_number` (`id`, `number`, `zone`, `status`, `price`, `image`) VALUES
(1, 'A1', 1, 0, 1400.00, 'A01.jpg'),
(2, 'A2', 1, 1, 3000.00, 'A02.jpg'),
(9, 'A3', 1, 0, 2000.00, 'A03.jpg'),
(10, 'A4', 1, 0, 1000.00, 'A04.jpg'),
(11, 'A5', 1, 0, 1500.00, 'A06.jpg'),
(12, 'A6', 1, 0, 1000.00, 'A07.jpg'),
(13, 'A7', 1, 0, 1000.00, 'A07.jpg'),
(14, 'A8', 1, 0, 1000.00, 'A08.jpg'),
(15, 'A9', 1, 0, 1200.00, 'A09.jpg'),
(16, 'A10', 1, 0, 1000.00, 'A10.jpg'),
(17, 'B1', 2, 1, 1000.00, 'B01.jpg'),
(18, 'B2', 2, 0, 1500.00, 'B02.jpg'),
(19, 'B3', 2, 0, 1000.00, 'B03.jpg'),
(20, 'B4', 2, 0, 1000.00, 'B04.jpg'),
(21, 'B5', 2, 0, 2000.00, 'DEMO_B.jpg'),
(22, 'B6', 2, 0, 1500.00, 'DEMO_B.jpg'),
(23, 'B7', 2, 0, 1000.00, 'DEMO_B.jpg'),
(24, 'B8', 2, 0, 2000.00, 'DEMO_B.jpg'),
(25, 'B9', 2, 0, 1500.00, 'DEMO_B.jpg'),
(26, 'B10', 2, 0, 1000.00, 'DEMO_B.jpg'),
(27, 'C1', 3, 0, 5000.00, 'DEMO_C.jpg'),
(28, 'C2', 3, 0, 1500.00, 'DEMO_C.jpg'),
(29, 'C3', 3, 0, 2500.00, 'DEMO_C.jpg'),
(30, 'C4', 3, 0, 1000.00, 'DEMO_C.jpg'),
(31, 'C5', 3, 0, 2000.00, 'DEMO_C.jpg'),
(32, 'C6', 3, 0, 3000.00, 'DEMO_C.jpg'),
(33, 'C7', 3, 0, 1500.00, 'DEMO_C.jpg'),
(34, 'C8', 3, 0, 4000.00, 'DEMO_C.jpg'),
(35, 'C9', 3, 0, 3000.00, 'DEMO_C.jpg'),
(36, 'C10', 3, 0, 3500.00, 'DEMO_C.jpg'),
(37, 'C11', 3, 0, 5000.00, 'DEMO_C.jpg'),
(38, 'C12', 3, 0, 1000.00, 'DEMO_C.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `zone_number_id` (`zone_number_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `zone`
--
ALTER TABLE `zone`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zone_number`
--
ALTER TABLE `zone_number`
  ADD PRIMARY KEY (`id`),
  ADD KEY `zone` (`zone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `zone`
--
ALTER TABLE `zone`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `zone_number`
--
ALTER TABLE `zone_number`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`zone_number_id`) REFERENCES `zone_number` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `zone_number`
--
ALTER TABLE `zone_number`
  ADD CONSTRAINT `zone_number_ibfk_1` FOREIGN KEY (`zone`) REFERENCES `zone` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
