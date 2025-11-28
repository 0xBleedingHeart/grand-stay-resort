-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2025 at 01:18 AM
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
-- Database: `reservation_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `accommodations`
--

CREATE TABLE `accommodations` (
  `accommodation_id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `category` varchar(20) DEFAULT NULL,
  `capacity` int(11) NOT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `status` varchar(20) DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accommodations`
--

INSERT INTO `accommodations` (`accommodation_id`, `type`, `name`, `category`, `capacity`, `price_per_night`, `status`) VALUES
(1, 'Room', 'Ocean View Suite', 'VIP', 2, 5000.00, 'Available'),
(2, 'Room', 'Deluxe Room', 'Deluxe', 2, 3000.00, 'Available'),
(3, 'Cottage', 'Private Cottage', 'VIP', 4, 8000.00, 'Available'),
(4, 'Room', 'Standard Room', 'Standard', 2, 2000.00, 'Available'),
(5, 'Room', 'Ocean View Suite', 'VIP', 2, 5000.00, 'Available'),
(6, 'Cottage', 'Private Cottage', 'VIP', 4, 8000.00, 'Available'),
(7, 'Room', 'Signature Restaurant', 'Deluxe', 6, 4000.00, 'Available'),
(8, 'Room', 'Rooftop Bar', 'Deluxe', 4, 3500.00, 'Available'),
(9, 'Room', 'Infinity Pool', 'Standard', 8, 3000.00, 'Available'),
(10, 'Room', 'The Grand Spa', 'VIP', 2, 4500.00, 'Available'),
(11, 'Cottage', 'Private Beach', 'VIP', 10, 10000.00, 'Available'),
(12, 'Cottage', 'Tropical Garden', 'Standard', 6, 6000.00, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `addons`
--

CREATE TABLE `addons` (
  `addon_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addons`
--

INSERT INTO `addons` (`addon_id`, `name`, `price`) VALUES
(1, 'Extra Bed', 500.00),
(2, 'Extra Pillow', 100.00),
(3, 'Extra Blanket', 150.00),
(4, 'Breakfast Buffet', 800.00),
(5, 'Lunch Set', 600.00),
(6, 'Dinner Set', 750.00),
(7, 'Airport Transfer', 1500.00),
(8, 'Spa Package', 2000.00),
(9, 'Massage Service', 1200.00),
(10, 'Room Service', 300.00);

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

CREATE TABLE `guests` (
  `guest_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guests`
--

INSERT INTO `guests` (`guest_id`, `full_name`, `contact_number`, `email`, `address`) VALUES
(1, 'samantha kyle', '09088184444', 'sam@gmail.com', NULL),
(2, 'testing2', '09088184444', 'testing@gmail.com', NULL),
(3, 'Samantha', '', 'sam@gmail.com', NULL),
(4, 'Samantha', '', 'sam@gmail.com', NULL),
(5, 'Samantha', '', 'sam@gmail.com', NULL),
(6, 'Samantha', '', 'sam@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `payment_date` datetime DEFAULT current_timestamp(),
  `amount_paid` decimal(10,2) NOT NULL,
  `payment_method` varchar(30) NOT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `reference_number` varchar(50) DEFAULT NULL,
  `payment_status` varchar(20) DEFAULT 'Unpaid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `reservation_id`, `payment_date`, `amount_paid`, `payment_method`, `account_number`, `reference_number`, `payment_status`) VALUES
(2, 3, '2025-11-28 07:39:25', 4000.00, 'GCash', '09088184444', NULL, 'Paid'),
(3, 5, '2025-11-28 07:47:22', 10000.00, 'PayMaya', '123123', '123123', 'Paid'),
(4, 6, '2025-11-28 07:55:43', 242000.00, 'PayMaya', '09088184444', '12312345', 'Paid');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `guest_id` int(11) NOT NULL,
  `accommodation_id` int(11) NOT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `num_pax` int(11) NOT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `reservation_status` varchar(20) DEFAULT 'Pending',
  `date_reserved` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `guest_id`, `accommodation_id`, `check_in_date`, `check_out_date`, `num_pax`, `total_price`, `reservation_status`, `date_reserved`) VALUES
(1, 1, 9, '2026-09-08', '2026-09-09', 2, 3000.00, 'Confirmed', '2025-11-28 06:44:16'),
(3, 3, 7, '2026-09-10', '2026-09-11', 2, 4000.00, 'Confirmed', '2025-11-28 07:39:25'),
(5, 5, 11, '2026-01-02', '2026-01-03', 2, 10000.00, 'Confirmed', '2025-11-28 07:47:22'),
(6, 6, 8, '2026-02-02', '2026-02-26', 5, 242000.00, 'Confirmed', '2025-11-28 07:55:43');

-- --------------------------------------------------------

--
-- Table structure for table `reservation_addons`
--

CREATE TABLE `reservation_addons` (
  `id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `addon_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservation_addons`
--

INSERT INTO `reservation_addons` (`id`, `reservation_id`, `addon_id`, `quantity`, `subtotal`) VALUES
(1, 6, 7, 20, 30000.00),
(2, 6, 4, 20, 16000.00),
(3, 6, 6, 20, 15000.00),
(4, 6, 1, 20, 10000.00),
(5, 6, 3, 20, 3000.00),
(6, 6, 2, 20, 2000.00),
(7, 6, 5, 20, 12000.00),
(8, 6, 9, 20, 24000.00),
(9, 6, 10, 20, 6000.00),
(10, 6, 8, 20, 40000.00);

-- --------------------------------------------------------

--
-- Table structure for table `table_reservations`
--

CREATE TABLE `table_reservations` (
  `table_reservation_id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `table_number` varchar(10) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` varchar(20) DEFAULT 'Reserved'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `full_name`, `phone`, `address`, `role`, `created_at`) VALUES
(1, 'sam@gmail.com', '$2y$10$qJ6Ri2eiGOE3kHPf61GU8u1q7NP.QUHwAEGDYFEvVu8rGv08sQK76', 'Samantha', '', 'test', 'user', '2025-11-28 06:32:10'),
(2, 'admin@grandstay.com', '$2y$10$erpKqgK5Z.cQvrMPgr8CYeP/US03bC4ce/lLPp2XWy19v3qyiVBuG', 'Admin User', NULL, NULL, 'admin', '2025-11-28 07:43:04');

-- --------------------------------------------------------

--
-- Table structure for table `user_payment_methods`
--

CREATE TABLE `user_payment_methods` (
  `payment_method_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_type` enum('GCash','PayMaya','Bank Transfer') NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `account_number` varchar(50) NOT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_payment_methods`
--

INSERT INTO `user_payment_methods` (`payment_method_id`, `user_id`, `payment_type`, `account_name`, `account_number`, `mobile_number`, `bank_name`, `is_default`, `created_at`) VALUES
(1, 1, 'GCash', 'test', '0908', '0908', '', 0, '2025-11-28 07:27:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accommodations`
--
ALTER TABLE `accommodations`
  ADD PRIMARY KEY (`accommodation_id`);

--
-- Indexes for table `addons`
--
ALTER TABLE `addons`
  ADD PRIMARY KEY (`addon_id`);

--
-- Indexes for table `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`guest_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `reservation_id` (`reservation_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `guest_id` (`guest_id`),
  ADD KEY `accommodation_id` (`accommodation_id`);

--
-- Indexes for table `reservation_addons`
--
ALTER TABLE `reservation_addons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservation_id` (`reservation_id`),
  ADD KEY `addon_id` (`addon_id`);

--
-- Indexes for table `table_reservations`
--
ALTER TABLE `table_reservations`
  ADD PRIMARY KEY (`table_reservation_id`),
  ADD KEY `reservation_id` (`reservation_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_payment_methods`
--
ALTER TABLE `user_payment_methods`
  ADD PRIMARY KEY (`payment_method_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accommodations`
--
ALTER TABLE `accommodations`
  MODIFY `accommodation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `addons`
--
ALTER TABLE `addons`
  MODIFY `addon_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `guests`
--
ALTER TABLE `guests`
  MODIFY `guest_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reservation_addons`
--
ALTER TABLE `reservation_addons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `table_reservations`
--
ALTER TABLE `table_reservations`
  MODIFY `table_reservation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_payment_methods`
--
ALTER TABLE `user_payment_methods`
  MODIFY `payment_method_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`) ON DELETE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`guest_id`) REFERENCES `guests` (`guest_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`accommodation_id`) REFERENCES `accommodations` (`accommodation_id`) ON DELETE CASCADE;

--
-- Constraints for table `reservation_addons`
--
ALTER TABLE `reservation_addons`
  ADD CONSTRAINT `reservation_addons_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservation_addons_ibfk_2` FOREIGN KEY (`addon_id`) REFERENCES `addons` (`addon_id`) ON DELETE CASCADE;

--
-- Constraints for table `table_reservations`
--
ALTER TABLE `table_reservations`
  ADD CONSTRAINT `table_reservations_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_payment_methods`
--
ALTER TABLE `user_payment_methods`
  ADD CONSTRAINT `user_payment_methods_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
