-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2024 at 07:02 AM
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
-- Database: `lab`
--

-- --------------------------------------------------------

--
-- Table structure for table `cancelled_request`
--

CREATE TABLE `cancelled_request` (
  `request_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `student_id` int(11) DEFAULT NULL,
  `item_name` varchar(255) NOT NULL,
  `cancelled_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cancelled_request`
--

INSERT INTO `cancelled_request` (`request_id`, `timestamp`, `student_id`, `item_name`, `cancelled_timestamp`) VALUES
(228, '2024-06-21 11:34:46', 64, 'Waveform Generator', '2024-06-21 11:36:04'),
(231, '2024-06-21 11:54:52', 64, '9V Batteries', '2024-06-21 11:55:27'),
(232, '2024-06-21 11:54:52', 64, '9V Batteries', '2024-06-21 11:55:28'),
(236, '2024-06-21 11:55:00', 64, 'Wire - TT', '2024-06-21 11:55:34');

-- --------------------------------------------------------

--
-- Table structure for table `fine_table`
--

CREATE TABLE `fine_table` (
  `student_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `days` int(11) NOT NULL,
  `fine_amount` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fine_table`
--

INSERT INTO `fine_table` (`student_id`, `item_id`, `days`, `fine_amount`) VALUES
(4, 8, -1, 1000),
(4, 14, 137, 500),
(4, 81, 137, 500),
(4, 16, 16, 500),
(4, 79, -1, 1000),
(4, 11, 4, 500),
(60, 84, 16, 500),
(60, 5, -1, 1000),
(4, 6, 6, 500),
(61, 7, -1, 1000);

-- --------------------------------------------------------

--
-- Table structure for table `issued`
--

CREATE TABLE `issued` (
  `issue_id` int(11) NOT NULL,
  `issue_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `due_date` timestamp NULL DEFAULT NULL,
  `return_date` timestamp NULL DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `officer_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issued`
--

INSERT INTO `issued` (`issue_id`, `issue_date`, `due_date`, `return_date`, `item_id`, `officer_id`, `student_id`) VALUES
(1, '2024-06-20 17:11:55', '2024-07-04 13:41:55', '2024-06-20 18:56:55', 5, 1, 4),
(2, '2024-06-04 17:12:35', '2024-06-16 13:42:35', '2024-06-21 00:33:00', 11, 1, 4),
(3, '2024-06-20 17:12:37', '2024-06-04 13:42:37', NULL, 8, 1, 4),
(4, '2024-06-20 17:12:38', '2024-02-04 13:42:38', '2024-06-20 17:15:02', 14, 1, 4),
(7, '2024-06-20 18:36:26', '2024-06-04 15:06:26', '2024-06-20 18:37:42', 16, 1, 4),
(8, '2024-05-20 18:36:27', '2024-04-04 15:06:27', NULL, 79, 1, 4),
(9, '2024-06-05 18:55:57', '2024-06-14 15:25:57', '2024-06-21 11:58:05', 6, 1, 4),
(10, '2024-06-20 18:56:23', '2024-07-03 15:26:23', '2024-06-21 11:57:31', 12, 1, 4),
(11, '2024-05-21 01:36:40', '2024-06-04 22:06:40', NULL, 7, 5, 61),
(12, '2024-06-21 01:36:43', '2024-07-04 22:06:43', NULL, 82, 5, 61),
(13, '2024-05-06 01:36:44', '2024-06-04 22:06:44', NULL, 83, 5, 61),
(14, '2024-05-21 01:36:46', '2024-06-10 22:06:46', NULL, 93, 5, 4),
(15, '2024-06-21 01:36:48', '2024-07-04 22:06:48', NULL, 9, 5, 4),
(16, '2024-06-21 01:36:49', '2024-07-04 22:06:49', NULL, 80, 5, 61),
(17, '2024-05-22 01:36:50', '2024-06-14 22:06:50', NULL, 13, 5, 61),
(18, '2024-05-21 01:36:50', '2024-06-04 22:06:50', '2024-06-21 01:48:11', 84, 5, 60),
(19, '2024-05-21 01:36:52', '2024-06-14 22:06:52', NULL, 94, 5, 60),
(20, '2024-06-10 01:36:54', '2024-06-18 22:06:54', NULL, 5, 5, 60),
(21, '2024-06-21 01:37:04', '2024-07-04 22:07:04', NULL, 15, 5, 57),
(22, '2024-06-02 01:37:06', '2024-06-17 22:07:06', NULL, 95, 5, 57),
(23, '2024-06-21 01:37:11', '2024-07-04 22:07:11', NULL, 85, 5, 56),
(24, '2024-06-21 01:37:15', '2024-07-04 22:07:15', NULL, 11, 5, 57),
(25, '2024-06-21 01:37:25', '2024-07-04 22:07:25', NULL, 86, 5, 56),
(26, '2024-06-21 01:37:35', '2024-07-04 22:07:35', NULL, 87, 5, 56),
(29, '2024-06-21 11:36:02', '2024-07-11 08:06:02', NULL, 129, 1, 64),
(30, '2024-06-21 11:36:05', '2024-07-05 08:06:05', NULL, 119, 1, 64),
(31, '2024-06-21 11:55:22', '2024-07-11 08:25:22', NULL, 84, 1, 64),
(32, '2024-06-21 11:55:31', '2024-07-05 08:25:31', NULL, 88, 1, 64),
(33, '2024-06-21 11:55:32', '2024-07-05 08:25:32', NULL, 130, 1, 64),
(34, '2024-06-21 11:55:33', '2024-07-05 08:25:33', NULL, 131, 1, 64);

-- --------------------------------------------------------

--
-- Table structure for table `lab_item`
--

CREATE TABLE `lab_item` (
  `item_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_item`
--

INSERT INTO `lab_item` (`item_id`, `name`, `price`) VALUES
(5, 'Arduino Uno', 3700.00),
(6, 'LED Diode', 75.00),
(7, 'Resistor Kit', 1538.00),
(8, 'Breadboard', 648.00),
(9, 'Jumper Wires (Male to Male)', 269.00),
(11, 'Arduino Uno', 3700.00),
(12, 'LED Diode', 73.00),
(13, 'Resistor Kit', 1538.00),
(14, 'Breadboard', 648.00),
(15, 'Jumper Wires (Male to Male)', 269.00),
(16, 'Jumper Wires (Male to Female)', 269.00),
(17, 'Wire - TT', 5.00),
(79, 'Jumper Wires (Male to Female)', 270.00),
(80, 'B', 100.00),
(82, 'kfknso', 20.00),
(83, '9V Batteries', 200.00),
(84, '9V Batteries', 200.00),
(85, '9V Batteries', 200.00),
(86, '9V Batteries', 200.00),
(87, '9V Batteries', 200.00),
(88, '9V Batteries', 200.00),
(89, '9V Batteries', 200.00),
(90, '9V Batteries', 200.00),
(91, '9V Batteries', 200.00),
(92, '9V Batteries', 200.00),
(93, 'Apple', 50.00),
(94, 'Apple', 50.00),
(95, 'Apple', 50.00),
(97, 'Logic Analyzer', 2500.00),
(98, 'Logic Analyzer', 2500.00),
(99, 'Logic Analyzer', 2500.00),
(100, 'Logic Analyzer', 2500.00),
(101, 'Logic Analyzer', 2500.00),
(102, 'Logic Analyzer', 2500.00),
(103, 'Logic Analyzer', 2500.00),
(104, 'Logic Analyzer', 2500.00),
(105, 'Logic Analyzer', 2500.00),
(106, 'Logic Analyzer', 2500.00),
(107, 'Waveform Generator', 20000.00),
(108, 'Waveform Generator', 20000.00),
(109, 'Waveform Generator', 20000.00),
(110, 'Waveform Generator', 20000.00),
(111, 'Waveform Generator', 20000.00),
(112, 'Waveform Generator', 20000.00),
(113, 'Waveform Generator', 20000.00),
(114, 'Waveform Generator', 20000.00),
(115, 'Waveform Generator', 20000.00),
(116, 'Waveform Generator', 20000.00),
(117, 'Waveform Generator', 20000.00),
(118, 'Waveform Generator', 20000.00),
(119, 'Digital Multimeter', 15000.00),
(120, 'Digital Multimeter', 15000.00),
(121, 'Digital Multimeter', 15000.00),
(122, 'Digital Multimeter', 15000.00),
(123, 'Digital Multimeter', 15000.00),
(124, 'Digital Multimeter', 15000.00),
(125, 'Digital Multimeter', 15000.00),
(126, 'Digital Multimeter', 15000.00),
(127, 'Digital Multimeter', 15000.00),
(128, 'Digital Multimeter', 15000.00),
(129, 'Microcontroller Board', 1000.00),
(130, 'Microcontroller Board', 1000.00),
(131, 'Microcontroller Board', 1000.00),
(132, 'Microcontroller Board', 1000.00),
(133, 'Microcontroller Board', 1000.00),
(145, 'Rasberry Pi', 5000.00),
(146, 'Rasberry Pi', 5000.00),
(147, 'Rasberry Pi', 5000.00),
(148, 'Rasberry Pi', 5000.00),
(149, 'Rasberry Pi', 5000.00);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `student_id`, `message`, `created_at`) VALUES
(1, 64, 'Requested item: Microcontroller Board, Count: 2', '2024-06-21 11:34:40'),
(2, 64, 'Requested item: Waveform Generator, Count: 1', '2024-06-21 11:34:46'),
(3, 64, 'Requested item: Digital Multimeter, Count: 1', '2024-06-21 11:34:52'),
(4, 64, 'Cancelled request for item: Microcontroller Board', '2024-06-21 11:35:12'),
(5, 64, 'Issued item: Microcontroller Board', '2024-06-21 11:36:02'),
(6, 64, 'Rejected request for item: Waveform Generator', '2024-06-21 11:36:04'),
(7, 64, 'Issued item: Digital Multimeter', '2024-06-21 11:36:05'),
(8, 64, 'Requested item: 9V Batteries, Count: 4', '2024-06-21 11:54:52'),
(9, 64, 'Requested item: Microcontroller Board, Count: 2', '2024-06-21 11:54:56'),
(10, 64, 'Requested item: Wire - TT, Count: 1', '2024-06-21 11:55:00'),
(11, 64, 'Issued item: 9V Batteries', '2024-06-21 11:55:22'),
(12, 64, 'Rejected request for item: 9V Batteries', '2024-06-21 11:55:27'),
(13, 64, 'Rejected request for item: 9V Batteries', '2024-06-21 11:55:28'),
(14, 64, 'Issued item: 9V Batteries', '2024-06-21 11:55:31'),
(15, 64, 'Issued item: Microcontroller Board', '2024-06-21 11:55:32'),
(16, 64, 'Issued item: Microcontroller Board', '2024-06-21 11:55:33'),
(17, 64, 'Rejected request for item: Wire - TT', '2024-06-21 11:55:34'),
(18, 4, 'Issued item LED Diode returned successfully, Issue ID: 10', '2024-06-21 11:57:31'),
(19, 4, 'Late return fine imposed and returned successfully for item , Issue ID: 9', '2024-06-21 11:58:05'),
(20, 61, 'Lost item fine imposed successfully for item , Issue ID: 11', '2024-06-21 11:58:17');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `request_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `student_id` int(11) DEFAULT NULL,
  `item_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `email`, `password`, `name`) VALUES
(1, 'student1@example.com', 'password1', 'Nathiskar12'),
(4, 'jena@gmail.com', '1234', 'Akilan Jenarththan'),
(40, 'James@gmail.com', 'james', 'James'),
(50, 'officer1@example.com', '1234', 'Harsini'),
(52, 'john.smith@gmail.com', '1234', 'John Smith'),
(53, 'emma.johnson@outlook.com', '1234', 'Emma Johnson'),
(54, 'michael.brown@yahoo.com', '1234', 'Michael Brown'),
(55, 'olivia.dias@gmail.com', '1234', 'Olivia Davis'),
(56, 'william.garcia@gmail.com', '1234', 'william Garcia'),
(57, 'surya@gamil.com', '1234', 'Surya'),
(58, 'vijay@gamil.com', '12345', 'Vijay'),
(59, 'sivakumar@gamil.com', '12345', 'Sivakumaran Perarasan'),
(60, 'vennila@gmail.com', '12345', 'Vennila Paranjothi '),
(61, 'Pennarasi@gmail.com', '1234', 'Pennarasi Kanthasaami'),
(64, 'jena2001@gmail.com', '1234', 'Jenarththan A');

-- --------------------------------------------------------

--
-- Table structure for table `technical_officer`
--

CREATE TABLE `technical_officer` (
  `officer_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `technical_officer`
--

INSERT INTO `technical_officer` (`officer_id`, `email`, `password`, `name`) VALUES
(1, 'officer1@example.com', '1234', 'Jenarthan'),
(2, 'officer2@example.com', 'password2', 'Emily Wilson'),
(5, 'nathis@gmail.com', '12', 'Shriganeshan Nathiskar');

-- --------------------------------------------------------

--
-- Table structure for table `technical_officer_notifications`
--

CREATE TABLE `technical_officer_notifications` (
  `id` int(11) NOT NULL,
  `officer_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `technical_officer_notifications`
--

INSERT INTO `technical_officer_notifications` (`id`, `officer_id`, `student_id`, `message`, `created_at`) VALUES
(1, NULL, 64, 'Requested item: Microcontroller Board, Count: 2, Student ID: 64', '2024-06-21 11:34:40'),
(2, NULL, 64, 'Requested item: Waveform Generator, Count: 1, Student ID: 64', '2024-06-21 11:34:46'),
(3, NULL, 64, 'Requested item: Digital Multimeter, Count: 1, Student ID: 64', '2024-06-21 11:34:52'),
(4, NULL, 64, 'Cancelled request for item: Microcontroller Board, Student ID: 64', '2024-06-21 11:35:12'),
(5, 1, 64, 'Issued item: Microcontroller Board ,Student ID: 64 , Officer ID: 1', '2024-06-21 11:36:02'),
(6, 1, 64, 'Rejected request for item: Waveform Generator ,Student ID: 64 , Officer ID: 1', '2024-06-21 11:36:04'),
(7, 1, 64, 'Issued item: Digital Multimeter ,Student ID: 64 , Officer ID: 1', '2024-06-21 11:36:05'),
(8, 1, NULL, 'New item added successfully by Officer ID: 1 ,Item Name: Arduino Uno ,Count:10', '2024-06-21 11:50:16'),
(9, 1, NULL, 'Item deleted successfully by Officer ID: 1 ,Item ID: 144.', '2024-06-21 11:50:52'),
(10, 1, NULL, 'Item deleted successfully by Officer ID: 1 ,Item ID: 143.', '2024-06-21 11:50:57'),
(11, 1, NULL, 'Item deleted successfully by Officer ID: 1 ,Item ID: 142.', '2024-06-21 11:51:00'),
(12, 1, NULL, 'Item deleted successfully by Officer ID: 1 ,Item ID: 141.', '2024-06-21 11:51:03'),
(13, 1, NULL, 'Item deleted successfully by Officer ID: 1 ,Item ID: 140.', '2024-06-21 11:51:06'),
(14, 1, NULL, 'Item deleted successfully by Officer ID: 1 ,Item ID: 139.', '2024-06-21 11:51:08'),
(15, 1, NULL, 'Item deleted successfully by Officer ID: 1 ,Item ID: 138.', '2024-06-21 11:51:11'),
(16, 1, NULL, 'Item deleted successfully by Officer ID: 1 ,Item ID: 137.', '2024-06-21 11:51:14'),
(17, 1, NULL, 'Item deleted successfully by Officer ID: 1 ,Item ID: 136.', '2024-06-21 11:51:17'),
(18, 1, NULL, 'Item deleted successfully by Officer ID: 1 ,Item ID: 135.', '2024-06-21 11:51:20'),
(19, 1, NULL, 'Item edited successfully by Officer ID: 1 ,Item ID: 6.', '2024-06-21 11:52:05'),
(20, 1, NULL, 'Item edited successfully by Officer ID: 1 ,Item ID: 6.', '2024-06-21 11:53:08'),
(21, 1, NULL, 'Item deleted successfully by Officer ID: 1 ,Item ID: 134.', '2024-06-21 11:53:13'),
(22, 1, NULL, 'New item added successfully by Officer ID: 1 ,Item Name: Rasberry Pi ,Count:5', '2024-06-21 11:53:29'),
(23, NULL, 64, 'Requested item: 9V Batteries, Count: 4, Student ID: 64', '2024-06-21 11:54:52'),
(24, NULL, 64, 'Requested item: Microcontroller Board, Count: 2, Student ID: 64', '2024-06-21 11:54:56'),
(25, NULL, 64, 'Requested item: Wire - TT, Count: 1, Student ID: 64', '2024-06-21 11:55:00'),
(26, 1, 64, 'Issued item: 9V Batteries ,Student ID: 64 , Officer ID: 1', '2024-06-21 11:55:22'),
(27, 1, 64, 'Rejected request for item: 9V Batteries ,Student ID: 64 , Officer ID: 1', '2024-06-21 11:55:27'),
(28, 1, 64, 'Rejected request for item: 9V Batteries ,Student ID: 64 , Officer ID: 1', '2024-06-21 11:55:28'),
(29, 1, 64, 'Issued item: 9V Batteries ,Student ID: 64 , Officer ID: 1', '2024-06-21 11:55:31'),
(30, 1, 64, 'Issued item: Microcontroller Board ,Student ID: 64 , Officer ID: 1', '2024-06-21 11:55:32'),
(31, 1, 64, 'Issued item: Microcontroller Board ,Student ID: 64 , Officer ID: 1', '2024-06-21 11:55:33'),
(32, 1, 64, 'Rejected request for item: Wire - TT ,Student ID: 64 , Officer ID: 1', '2024-06-21 11:55:34'),
(33, 1, NULL, 'New student added successfully by Officer ID: 1 ,Student Email: sam@gmail.com .', '2024-06-21 11:56:35'),
(34, 1, NULL, 'Student deleted successfully by Officer ID: 1 ,Student ID : 65.', '2024-06-21 11:56:46'),
(35, 1, 4, 'Issued item LED Diode returned successfully , Issue ID: 10 ,Student ID: 4 ,Officer ID: 1', '2024-06-21 11:57:31'),
(36, 1, 4, 'Late return fine imposed for item , Issue ID: 9, Student ID: 4, Officer ID: 1', '2024-06-21 11:58:05'),
(37, 1, 61, 'Lost item fine imposed for item , Issue ID: 11, Student ID: 61, Officer ID: 1', '2024-06-21 11:58:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cancelled_request`
--
ALTER TABLE `cancelled_request`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `issued`
--
ALTER TABLE `issued`
  ADD PRIMARY KEY (`issue_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `officer_id` (`officer_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `idx_item_id_return_date` (`item_id`,`return_date`);

--
-- Indexes for table `lab_item`
--
ALTER TABLE `lab_item`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `idx_student_email` (`email`);

--
-- Indexes for table `technical_officer`
--
ALTER TABLE `technical_officer`
  ADD PRIMARY KEY (`officer_id`),
  ADD KEY `idx_to_email` (`email`);

--
-- Indexes for table `technical_officer_notifications`
--
ALTER TABLE `technical_officer_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `officer_id` (`officer_id`),
  ADD KEY `student_id` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `issued`
--
ALTER TABLE `issued`
  MODIFY `issue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `lab_item`
--
ALTER TABLE `lab_item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=237;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `technical_officer`
--
ALTER TABLE `technical_officer`
  MODIFY `officer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `technical_officer_notifications`
--
ALTER TABLE `technical_officer_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cancelled_request`
--
ALTER TABLE `cancelled_request`
  ADD CONSTRAINT `cancelled_request_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);

--
-- Constraints for table `issued`
--
ALTER TABLE `issued`
  ADD CONSTRAINT `issued_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `lab_item` (`item_id`),
  ADD CONSTRAINT `issued_ibfk_2` FOREIGN KEY (`officer_id`) REFERENCES `technical_officer` (`officer_id`),
  ADD CONSTRAINT `issued_ibfk_3` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);

--
-- Constraints for table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);

--
-- Constraints for table `technical_officer_notifications`
--
ALTER TABLE `technical_officer_notifications`
  ADD CONSTRAINT `technical_officer_notifications_ibfk_1` FOREIGN KEY (`officer_id`) REFERENCES `technical_officer` (`officer_id`),
  ADD CONSTRAINT `technical_officer_notifications_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
