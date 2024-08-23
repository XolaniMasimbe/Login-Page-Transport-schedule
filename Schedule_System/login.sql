-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 23, 2024 at 04:01 PM
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
-- Database: `login`
--

-- --------------------------------------------------------

--
-- Table structure for table `bus_schedule`
--

CREATE TABLE `bus_schedule` (
  `id` int(11) NOT NULL,
  `route_from` varchar(50) DEFAULT NULL,
  `route_to` varchar(50) DEFAULT NULL,
  `bus_number` varchar(20) DEFAULT NULL,
  `number_plate` varchar(20) DEFAULT NULL,
  `departure_time` time DEFAULT NULL,
  `arrival_time` time DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bus_schedule`
--

INSERT INTO `bus_schedule` (`id`, `route_from`, `route_to`, `bus_number`, `number_plate`, `departure_time`, `arrival_time`, `status`) VALUES
(1, 'Bellville Campus', 'Cape Town D6 ', 'Bus001', 'CA123', '08:00:00', '09:00:00', 'Moving'),
(3, 'Cape Town D6', 'Bellville', 'Bus002', 'CA1234', '08:00:00', '09:00:00', 'Moving'),
(4, 'Bellville', 'Wellignton', 'Bus002', 'CA12345', '08:00:00', '09:00:00', 'Moving');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstName`, `lastName`, `email`, `password`, `role`, `reset_token`, `reset_expiry`) VALUES
(1, 'Xolani', 'Masimbe', '222410817@mycput.ac.za', '046d26430ec50d73760e36542c7473eb', '', NULL, NULL),
(2, 'Admin', 'User', 'xmasimbe@gmail.com', 'e3274be5c857fb42ab72d786e281b4b8', 'admin', NULL, NULL),
(3, 'Puleng', 'Nakedi', '222914556@mycput.ac.za', 'a43c27c2babefd68df8a694900f30a1c', 'user', NULL, NULL),
(7, 'Nosiphiwo', 'Mshweshwe', '220094896@mycput.ac.za', '36fdba5968850579c0a89444f4ca4772', 'user', NULL, NULL),
(8, 'User2', 'user', 'user@gmail.com', '6ad14ba9986e3615423dfca256d04e3f', 'admin', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bus_schedule`
--
ALTER TABLE `bus_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bus_schedule`
--
ALTER TABLE `bus_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
