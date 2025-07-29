-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2017 at 09:53 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `desktopserver`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`username`, `password`) VALUES
('akash', '8cb2237d0679ca88db6464eac60da96345513964');

-- --------------------------------------------------------

--
-- Table structure for table `centres`
--

CREATE TABLE `centres` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` bigint(20) NOT NULL,
  `rooms` int(11) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `centres`
--

INSERT INTO `centres` (`username`, `password`, `name`, `phone`, `rooms`, `address`) VALUES
('centre_1', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'DPS Jaipur', 1234567890, 10, 'DPS Jaipur, Jaipur, 302017'),
('centre_2', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'KVS Malviya Nagar', 8239159833, 5, 'Malviya Nagar,Jaipur');

-- --------------------------------------------------------

--
-- Table structure for table `dvrs`
--

CREATE TABLE `dvrs` (
  `id` varchar(255) NOT NULL,
  `centre` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `port` int(11) NOT NULL,
  `record` int(11) NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dvrs`
--

INSERT INTO `dvrs` (`id`, `centre`, `name`, `port`, `record`, `url`) VALUES
('Cam1', 'centre_1', 'Cam1', 15000, 1, 'rtsp://admin:admin12345@172.18.20.28/MPEG4/ch1/main/av_stream'),
('Cam2', 'centre_1', 'Cam2', 9000, 1, 'rtsp://admin:admin12345@172.18.20.28/MPEG4/ch2/main/av_stream');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `date` date NOT NULL,
  `centre` varchar(255) NOT NULL,
  `room` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `streams`
--

CREATE TABLE `streams` (
  `id` int(11) NOT NULL,
  `centre` varchar(255) NOT NULL,
  `record` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `port` int(11) NOT NULL,
  `lockRoom` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `streams`
--

INSERT INTO `streams` (`id`, `centre`, `record`, `name`, `port`, `lockRoom`) VALUES
(1, 'centre_1', 0, 'Room_1', 12000, 0),
(2, 'centre_1', 0, 'Room_2', 10000, 1),
(3, 'centre_1', 0, 'Room_3', 20000, 0),
(4, 'centre_1', 0, 'Room_4', 30000, 0),
(5, 'centre_1', 0, 'Room_5', 10000, 0),
(6, 'centre_1', 0, 'Room_6', 20000, 0),
(7, 'centre_1', 0, 'Room_7', 9100, 0),
(8, 'centre_1', 0, 'Room_8', 9200, 0),
(9, 'centre_1', 0, 'Room_9', 9300, 0),
(10, 'centre_1', 0, 'Room_10', 9400, 0),
(11, 'centre_2', 0, 'Room_1', 9000, 0),
(12, 'centre_2', 0, 'Room_2', 9100, 0),
(13, 'centre_2', 0, 'Room_3', 9200, 0),
(14, 'centre_2', 0, 'Room_4', 9300, 0),
(15, 'centre_2', 0, 'Room_5', 9400, 0);

-- --------------------------------------------------------

--
-- Table structure for table `viewers`
--

CREATE TABLE `viewers` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `viewers`
--

INSERT INTO `viewers` (`username`, `password`, `phone`) VALUES
('akash', '8cb2237d0679ca88db6464eac60da96345513964', 0),
('nikhil', '8cb2237d0679ca88db6464eac60da96345513964', 0),
('pankaj', '8cb2237d0679ca88db6464eac60da96345513964', 0),
('parth', '8cb2237d0679ca88db6464eac60da96345513964', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `centres`
--
ALTER TABLE `centres`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `dvrs`
--
ALTER TABLE `dvrs`
  ADD PRIMARY KEY (`id`,`centre`),
  ADD KEY `dvrs_ibfk_1` (`centre`);

--
-- Indexes for table `streams`
--
ALTER TABLE `streams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `centre` (`centre`);

--
-- Indexes for table `viewers`
--
ALTER TABLE `viewers`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `streams`
--
ALTER TABLE `streams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `dvrs`
--
ALTER TABLE `dvrs`
  ADD CONSTRAINT `dvrs_ibfk_1` FOREIGN KEY (`centre`) REFERENCES `centres` (`username`);

--
-- Constraints for table `streams`
--
ALTER TABLE `streams`
  ADD CONSTRAINT `streams_ibfk_1` FOREIGN KEY (`centre`) REFERENCES `centres` (`username`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
