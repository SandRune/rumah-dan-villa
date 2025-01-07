-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2025 at 07:51 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rumahdanvilla`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '202cb962ac59075b964b07152d234b70');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `reservation_id`, `message`, `created_at`) VALUES
(1, 5, 15, 'Reservation approved! Your reservation for Batu 3 from 2025-03-01 to 2025-03-01 has been approved. Enjoy your stay!', '2025-01-07 01:23:24'),
(2, 15, 17, 'Reservation rejected. Reason: No reason provided.', '2025-01-07 05:49:35'),
(3, 15, 19, 'Reservation rejected. Reason: No please 12 orang hell nah.', '2025-01-07 05:59:36');

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `propertyType` varchar(50) NOT NULL,
  `guestrooms` int(11) DEFAULT 0,
  `bedrooms` int(11) DEFAULT 0,
  `beds` int(11) DEFAULT 0,
  `bathrooms` int(11) DEFAULT 0,
  `amenities` text DEFAULT NULL,
  `images` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `map_location` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `location`, `title`, `description`, `price`, `propertyType`, `guestrooms`, `bedrooms`, `beds`, `bathrooms`, `amenities`, `images`, `created_at`, `map_location`) VALUES
(56, 'Batu', 'Batu', 'Batu', 11111111.00, 'Entire Place', 1, 1, 1, 1, 'Air Conditioning', 'uploads/677b2cef4d242_property2 (1).png', '2025-01-06 01:07:59', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d223.9603371844989!2d-80.34115665200741!3d26.086988457700198!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sid!4v1736038574832!5m2!1sen!2sid\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>'),
(57, 'Batu 3', 'Batu 3', 'Batu 3', 1500000.00, 'Entire Place', 2, 2, 2, 2, 'Wifi, TV, Kitchen, Workspace', 'uploads/677bf0cdbcee6_property1 (1).png', '2025-01-06 15:03:41', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d223.9603371844989!2d-80.34115665200741!3d26.086988457700198!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sid!4v1736038574832!5m2!1sen!2sid\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>'),
(58, 'Kota Batu, Jawa Timur', 'Batu Villa Resort', 'Vila dengan teras dan pemandangan gunung ini mempunyai 3 kamar tidur, ruang keluarga, TV layar datar, dapur lengkap dengan kulkas, dan 4 kamar mandi kamar mandi dengan shower.\r\n', 2500000.00, 'Entire Place', 2, 3, 3, 2, 'Wifi, TV, Kitchen, Air Conditioning, Pool', 'uploads/677cb83f6f0d0_Screenshot 2025-01-07 121153.png', '2025-01-07 05:14:39', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d987.9824625164048!2d112.5286120695607!3d-7.902397799507535!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7881a12aeeb13f%3A0x3ac88213f0b71584!2sVilla%20Sunrise%20Batu!5e0!3m2!1sen!2sid!4v1736226783278!5m2!1sen!2sid\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `guests` int(11) NOT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `receipt` text DEFAULT NULL,
  `purchase_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `property_id`, `start_date`, `end_date`, `guests`, `total_price`, `status`, `reason`, `created_at`, `receipt`, `purchase_date`) VALUES
(1, 5, 56, '2025-01-08', '2025-01-10', 3, 33333333.00, 'Approved', NULL, '2025-01-06 14:17:07', NULL, '2025-01-06 14:56:40'),
(2, 5, 56, '2025-01-08', '2025-01-10', 3, 33333333.00, 'Approved', NULL, '2025-01-06 14:27:17', NULL, '2025-01-06 14:56:40'),
(3, 5, 56, '2025-01-08', '2025-01-10', 3, 33333333.00, 'Approved', NULL, '2025-01-06 14:30:07', NULL, '2025-01-06 14:56:40'),
(4, 5, 56, '2025-01-08', '2025-01-10', 3, 33333333.00, 'Approved', NULL, '2025-01-06 14:30:11', NULL, '2025-01-06 14:56:40'),
(5, 5, 56, '2025-01-08', '2025-01-10', 3, 33333333.00, 'Approved', NULL, '2025-01-06 14:58:06', 'Reservation ID: 5\nProperty: Batu\nLocation: Batu\nStart Date: 2025-01-08\nEnd Date: 2025-01-10\nGuests: 3\nTotal Price: Rp. 33.333.333\nPurchase Date: 2025-01-06 15:58:06', '2025-01-06 08:58:06'),
(6, 5, 56, '2025-01-08', '2025-01-11', 3, 44444444.00, 'Approved', NULL, '2025-01-06 15:00:14', 'Reservation ID: 6\nProperty: Batu\nLocation: Batu\nStart Date: 2025-01-08\nEnd Date: 2025-01-11\nGuests: 3\nTotal Price: Rp. 44.444.444\nPurchase Date: 2025-01-06 16:00:14', '2025-01-06 09:00:14'),
(7, 5, 57, '2025-01-07', '2025-01-09', 11, 4500000.00, 'Approved', NULL, '2025-01-06 15:07:17', 'Reservation ID: {RESERVATION_ID}\nProperty: Batu 3\nLocation: Batu 3\nStart Date: 2025-01-07\nEnd Date: 2025-01-09\nGuests: 11\nTotal Price: Rp. 4.500.000\nPurchase Date: 2025-01-06 16:07:17', '2025-01-06 09:07:17'),
(8, 5, 57, '2025-01-07', '2025-01-09', 11, 4500000.00, 'Approved', NULL, '2025-01-06 15:12:45', 'Reservation ID: 0\nProperty: Batu 3\nLocation: Batu 3\nStart Date: 2025-01-07\nEnd Date: 2025-01-09\nGuests: 11\nTotal Price: Rp. 4.500.000\nPurchase Date: 2025-01-06 16:12:45', '2025-01-06 09:12:45'),
(9, 5, 57, '2025-01-07', '2025-01-08', 2, 3000000.00, 'Approved', NULL, '2025-01-06 15:53:55', 'Reservation ID: 9\nProperty: Batu 3\nLocation: Batu 3\nStart Date: 2025-01-07\nEnd Date: 2025-01-08\nGuests: 2\nTotal Price: Rp. 3.000.000\nPurchase Date: 2025-01-06 16:53:55', '2025-01-06 09:53:55'),
(10, 5, 57, '2025-01-15', '2025-01-18', 2, 6000000.00, 'Approved', NULL, '2025-01-06 16:23:21', 'Reservation ID: 10\nProperty: Batu 3\nLocation: Batu 3\nStart Date: 2025-01-15\nEnd Date: 2025-01-18\nGuests: 2\nTotal Price: Rp. 6.000.000\nPurchase Date: 2025-01-06 17:23:21', '2025-01-06 10:23:21'),
(11, 5, 57, '2025-01-16', '2025-01-18', 2, 4500000.00, 'Approved', NULL, '2025-01-06 16:24:10', 'Reservation ID: 11\nProperty: Batu 3\nLocation: Batu 3\nStart Date: 2025-01-16\nEnd Date: 2025-01-18\nGuests: 2\nTotal Price: Rp. 4.500.000\nPurchase Date: 2025-01-06 17:24:10', '2025-01-06 10:24:10'),
(12, 5, 57, '2025-01-31', '2025-02-21', 12, 33000000.00, 'Approved', NULL, '2025-01-06 16:28:13', 'Reservation ID: 12\nProperty: Batu 3\nLocation: Batu 3\nStart Date: 2025-01-31\nEnd Date: 2025-02-21\nGuests: 12\nTotal Price: Rp. 33.000.000\nPurchase Date: 2025-01-06 17:28:13', '2025-01-06 10:28:13'),
(13, 5, 57, '2025-01-07', '2025-01-09', 12, 4500000.00, 'Approved', NULL, '2025-01-06 16:32:51', 'Reservation ID: 13\nProperty: Batu 3\nLocation: Batu 3\nStart Date: 2025-01-07\nEnd Date: 2025-01-09\nGuests: 12\nTotal Price: Rp. 4.500.000\nPurchase Date: 2025-01-06 23:32:51', '2025-01-06 16:32:51'),
(14, 5, 57, '2025-01-07', '2025-01-09', 2, 4500000.00, 'Approved', NULL, '2025-01-06 16:38:44', 'Reservation ID: 14\nProperty: Batu 3\nLocation: Batu 3\nStart Date: 2025-01-07\nEnd Date: 2025-01-09\nGuests: 2\nTotal Price: Rp. 4.500.000\nPurchase Date: 2025-01-06 23:38:44', '2025-01-06 16:38:44'),
(15, 5, 57, '2025-03-01', '2025-03-01', 9, 1500000.00, 'Approved', NULL, '2025-01-07 00:57:31', 'Reservation ID: 15\nProperty: Batu 3\nLocation: Batu 3\nStart Date: 2025-03-01\nEnd Date: 2025-03-01\nGuests: 9\nTotal Price: Rp. 1.500.000\nPurchase Date: 2025-01-07 07:57:31', '2025-01-07 00:57:31'),
(16, 6, 57, '2025-03-03', '2025-03-03', 2, 1500000.00, 'Pending', NULL, '2025-01-07 03:26:33', 'Reservation ID: 16\nProperty: Batu 3\nLocation: Batu 3\nStart Date: 2025-03-03\nEnd Date: 2025-03-03\nGuests: 2\nTotal Price: Rp. 1.500.000\nPurchase Date: 2025-01-07 10:26:33', '2025-01-07 03:26:33'),
(17, 15, 58, '2025-01-07', '2025-01-09', 12, 7500000.00, 'Rejected', NULL, '2025-01-07 05:49:20', 'Reservation ID: 17\nProperty: Batu Villa Resort\nLocation: Kota Batu, Jawa Timur\nStart Date: 2025-01-07\nEnd Date: 2025-01-09\nGuests: 12\nTotal Price: Rp. 7.500.000\nPurchase Date: 2025-01-07 12:49:20\nRejection Reason: No reason provided', '2025-01-07 05:49:20'),
(18, 15, 58, '2025-01-08', '2025-01-08', 12, 2500000.00, 'Rejected', 'No Suprises', '2025-01-07 05:55:06', 'Reservation ID: 18\nProperty: Batu Villa Resort\nLocation: Kota Batu, Jawa Timur\nStart Date: 2025-01-08\nEnd Date: 2025-01-08\nGuests: 12\nTotal Price: Rp. 2.500.000\nPurchase Date: 2025-01-07 12:55:06', '2025-01-07 05:55:06'),
(19, 15, 58, '2025-01-09', '2025-01-09', 12, 2500000.00, 'Rejected', NULL, '2025-01-07 05:59:23', 'Reservation ID: 19\nProperty: Batu Villa Resort\nLocation: Kota Batu, Jawa Timur\nStart Date: 2025-01-09\nEnd Date: 2025-01-09\nGuests: 12\nTotal Price: Rp. 2.500.000\nPurchase Date: 2025-01-07 12:59:23\nRejection Reason: No please 12 orang hell nah', '2025-01-07 05:59:23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `created_at`) VALUES
(1, 'aku@gmail.com', '$2y$10$vi6E9y094LUpy74w2PiKHeFyLHp6CPpBYSAgfqCXD.7n6mLHHBgAa', '2024-12-22 17:57:35'),
(3, 'aku123@gmail.com', '$2y$10$HKwnET6o3yeiFYdwD073EeQYRXYXwGyw8N6bp6UEs9hp5VxEbtcPy', '2024-12-22 17:58:21'),
(4, 'aku12322@gmail.com', '$2y$10$SbT8JwkSxW0XLDXfx1bB8OfppIRhLIFTrXEWo4YUMoaNIIbe8XEm2', '2024-12-22 18:50:48'),
(5, 'sandro@gmail.com', '$2y$10$viYg/iU6V3Uq7r1nDGHoNOob748gj7EAIhbxiT9RR7ifWXo0C155m', '2024-12-28 11:12:52'),
(6, '123@gmail.com', '$2y$10$oJD2Y1ZIC4sty83fRqGX5.axaDHMxjj6OYhlV9JIzL6A.WCT0VBxC', '2025-01-05 01:08:47'),
(7, 'hallo@gmail.com', '$2y$10$63IXncgKinDzKOh2xQDqLOrpmOd2GUsDUymu1HPPX5y6tCCHgTiGS', '2025-01-07 03:29:46'),
(15, 'sandro123@gmail.com', '$2y$10$nsfNUFDiR2eAlWLmuMdqjecyD2lIpGYLVBSfpIniK/cNFw2eGnOT2', '2025-01-07 05:47:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`id`) REFERENCES `messages` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
