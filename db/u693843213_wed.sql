-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 31, 2017 at 05:04 AM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u693843213_wed`
--

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

CREATE TABLE `guests` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `description` text,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wishlistthings`
--

CREATE TABLE `wishlistthings` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` text,
  `url` text,
  `reserver` varchar(15) DEFAULT NULL,
  `reserverEmail` varchar(100) DEFAULT NULL,
  `cancelationCode` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wishlistthings`
--

INSERT INTO `wishlistthings` (`id`, `title`, `description`, `url`, `reserver`, `reserverEmail`, `cancelationCode`) VALUES
(13, 'plancha', 'plancha', 'sad', 'juan2', 'juan2@juan.com', '95695'),
(15, 'taza', 'para tomar', 'www.google.com', NULL, NULL, ''),
(16, 'taza', '2', NULL, NULL, NULL, ''),
(17, 'taza3', NULL, NULL, NULL, NULL, ''),
(18, 'plancha2', '', NULL, NULL, NULL, ''),
(19, 'plancha4', '', '', NULL, NULL, ''),
(20, 'savana2', 'savana2', NULL, 'eze', 'dasd@dasd.com', '62456');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `wishlistthings`
--
ALTER TABLE `wishlistthings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wishlistthings`
--
ALTER TABLE `wishlistthings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
