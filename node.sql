-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2021 at 08:34 AM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `node`
--

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `email` varchar(500) NOT NULL,
  `password` varchar(500) NOT NULL,
  `role` varchar(600) DEFAULT NULL,
  `img` varchar(600) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `email`, `password`, `role`, `img`) VALUES
(1, 'kushalkumar@gmialm.com', 'ewrwerw', 'user', 'Untitled.png'),
(2, 'kushalkumar@gmiaddlm.com', 'sdfsdfasdfa', 'user', 'Untitled.png'),
(3, 'kushalkumar@gmialm.comdd', '34534534534', 'user', 'Screenshot_1.png'),
(4, 'kushalk4444umar@gmialm.com', '45645645645', 'user', 'Screenshot_1.png'),
(5, 'kushalkumar@gmialm.comtrytrytry', '45645645645645', 'user', 'Untitled.png'),
(6, 'kushalsharma660@gmail.com', 'Kushal@123', 'user', 'Untitled.png'),
(7, 'parshant@gmial.com', '345345345', 'user', 'Screenshot_1.png'),
(8, 'Naveen@123.com', '123123', 'user', 'Screenshot_1.png'),
(9, 'deepak@21.com', '1231231', 'user', 'Untitled.png'),
(10, 'kushal@1232.com', '123123', 'user', 'Screenshot_1.png'),
(11, 'kushal@1232.comgfhg', 'fhgfh', 'user', 'Untitled.png'),
(12, 'kushalkumar@gmffialm.com', 'dfgdfg', 'user', 'Screenshot_1.png'),
(13, 'chec@123.com', '123123', 'user', 'Screenshot_1.png'),
(14, 'kjasdhdjk@ewrf.comsf', 'sdfsdfsd', 'user', 'Untitled.png'),
(15, 'fghgfhg@rgtdf.xcvhgf', 'gfhgfhgfhgf', 'user', 'Untitled.png'),
(16, 'jasiwiner@1234.com', '324234234', 'user', 'Untitled.png'),
(17, 'Amitsharma@123.com', 'Amit@123', 'admin', 'Screenshot_1.png'),
(18, 'kushalkusadddmar@gmialm.com', '12345678', 'user', 'Screenshot_1.png'),
(19, 'Naveen@123.cowm', '1231232', 'user', 'Untitled.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
