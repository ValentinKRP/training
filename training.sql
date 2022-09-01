-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 31, 2022 at 03:11 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `training`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `details` varchar(100) NOT NULL,
  `comments` varchar(1000) NOT NULL,
  `total` int(11) NOT NULL,
  `order_date` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_name`, `details`, `comments`, `total`, `order_date`) VALUES
(115, 'VALENTIN', 'adresa x sssssssssssss', 'ssssssssssssssssssssssssss', 48000, '2022-08-30 12:50:55pm'),
(116, 'VALENTIN', 'adresa x sssssssssssss', 'ssssssssssssssssssssssssss', 48000, '2022-08-30 12:53:36pm'),
(117, 'VALENTIN', 'adresa x sssssssssssss', 'ssssssssssssssssssssssssss', 16000, '2022-08-30 01:05:13pm'),
(118, 'VALENTIN', 'adresa x sssssssssssss', 'ssssssssssssssssssssssssss', 40000, '2022-08-31 08:53:42am'),
(119, 'VALENTIN', 'adresa x sssssssssssss', 'ssssssssssssssssssssssssss', 56000, '2022-08-31 08:54:06am'),
(120, 'Andrei', 'adresa x sssssssssssss', 'ssssssssssssssssssssssssss', 190000, '2022-08-31 09:20:31am'),
(121, 'VALENTIN', 'adresa x sssssssssssss', 'asdasd', 66000, '2022-08-31 09:23:26am'),
(122, 'Andrei', 'adresa x', 'ssssssssssssssssssssssssss', 108000, '2022-08-31 09:25:48am'),
(123, 'VALENTIN', 'adresa x sssssssssssss', 'ssssssssssssssssssssssssss', 124000, '2022-08-31 09:26:54am'),
(124, 'VALENTIN', 'adresa x sssssssssssss', 'ssssssssssssssssssssssssss', 58000, '2022-08-31 09:39:43am'),
(125, 'VALENTIN', 'adresa x sssssssssssss', 'ssssssssssssssssssssssssss', 124000, '2022-08-31 01:35:58pm'),
(126, 'VALENTIN', 'adresa x sssssssssssss', 'ora x ssssss', 166000, '2022-08-31 01:37:07pm'),
(127, 'VALENTIN', 'adresa x sssssssssssss', 'ssssssssssssssssssssssssss', 124000, '2022-08-31 01:45:23pm'),
(128, 'VALENTIN', 'adresa x sssssssssssss', 'ssssssssssssssssssssssssss', 190000, '2022-08-31 02:47:30pm'),
(129, 'VALENTIN', 'adresa x sssssssssssss', 'ssssssssssssssssssssssssss', 190000, '2022-08-31 02:48:03pm'),
(130, 'VALENTIN', 'adresa x sssssssssssss', 'ssssssssssssssssssssssssss', 40000, '2022-08-31 02:49:24pm'),
(131, 'VALENTIN', 'adresa x sssssssssssss', 'ssssssssssssssssssssssssss', 74000, '2022-08-31 02:50:18pm'),
(132, 'VALENTIN', 'adresa x sssssssssssss', 'ssssssssssssssssssssssssss', 82000, '2022-08-31 02:51:29pm'),
(133, 'Andrei', 'adresa x sssssssssssss', 'ssssssssssssssssssssssssss', 90000, '2022-08-31 02:52:21pm'),
(134, 'VALENTIN', 'adresa x sssssssssssss', 'asdasd', 48000, '2022-08-31 02:53:04pm');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL,
  `price` int(11) NOT NULL,
  `product_image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `description`, `price`, `product_image`) VALUES
(16, '', 'a super car and v8 engine', 8000, '62fb420b0a6846.10954088.jpg'),
(19, 'Kawasaki', 'sport motorcycle', 8000, '62f0b8e29c6809.72763003.jpg'),
(22, 'BMWx6', 'a super car and v8 engine', 50000, '630f09b455c222.40291788.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_name`, `password`, `admin`) VALUES
(4, 'Valentin', '1234', 1),
(5, 'Maria', '12234', 0),
(7, 'Valentin123', '1234', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users_orders`
--

CREATE TABLE `users_orders` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `product_price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_orders`
--

INSERT INTO `users_orders` (`order_id`, `product_id`, `quantity`, `product_price`) VALUES
(115, 16, 2, 8000),
(115, 19, 4, 8000),
(116, 16, 2, 8000),
(116, 19, 4, 8000),
(117, 16, 1, 8000),
(117, 19, 1, 8000),
(118, 16, 3, 8000),
(118, 19, 2, 8000),
(119, 19, 2, 8000),
(119, 20, 1, 40000),
(120, 16, 5, 8000),
(120, 22, 3, 50000),
(121, 19, 2, 8000),
(121, 22, 1, 50000),
(122, 19, 1, 8000),
(122, 22, 2, 50000),
(123, 16, 3, 8000),
(123, 22, 2, 50000),
(124, 19, 1, 8000),
(124, 22, 1, 50000),
(125, 16, 3, 8000),
(125, 22, 2, 50000),
(126, 19, 2, 8000),
(126, 22, 3, 50000),
(127, 19, 3, 8000),
(127, 22, 2, 50000),
(128, 16, 2, 8000),
(128, 19, 3, 8000),
(128, 22, 3, 50000),
(129, 16, 2, 8000),
(129, 19, 3, 8000),
(129, 22, 3, 50000),
(130, 16, 3, 8000),
(130, 19, 2, 8000),
(131, 19, 3, 8000),
(131, 22, 1, 50000),
(132, 19, 4, 8000),
(132, 22, 1, 50000),
(133, 16, 3, 8000),
(133, 19, 2, 8000),
(133, 22, 1, 50000),
(134, 16, 4, 8000),
(134, 19, 2, 8000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_name` (`user_name`);

--
-- Indexes for table `users_orders`
--
ALTER TABLE `users_orders`
  ADD KEY `order_id` (`order_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
