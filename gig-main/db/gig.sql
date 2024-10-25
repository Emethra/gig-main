-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 08, 2024 at 02:07 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gig`
--

-- --------------------------------------------------------

--
-- Table structure for table `ads`
--

DROP TABLE IF EXISTS `ads`;
CREATE TABLE IF NOT EXISTS `ads` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `description` text,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `ads`
--

INSERT INTO `ads` (`id`, `title`, `category`, `location`, `description`, `date`, `image`) VALUES
(4, 'Wall Collapse', 'masonry', 'chilaw', 'Hurry Up!!', '2024-10-06 17:53:05', 'uploads/rebuildwalls.jpg'),
(5, 'Cleaning Jobs', 'Cleaning', 'Panadura', 'Limited Vacancies!', '2024-10-06 17:54:21', 'uploads/windowcleaning.jpg'),
(6, 'Heavy Machine Components Repair', 'Electrician', 'Puttlam', 'Hiring Ends Soon!', '2024-10-06 17:56:11', 'uploads/powersupplydamage.jpg'),
(7, 'qwerty', 'Mechanics', 'Jaffna', 'Mechanical Support Required!', '2024-10-06 18:37:04', 'uploads/cardamage.jpg'),
(8, 'Builders Required', 'Builder', 'Hambantota', 'Low Vacancy', '2024-10-07 09:20:38', 'uploads/powersupplydamage.jpg'),
(9, '123123', '123123', '123123', '123123', '2024-10-08 09:39:30', 'uploads/windowcleaning.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`) VALUES
(1, 'Plumber'),
(2, 'Mechanic'),
(3, 'Electrician'),
(4, 'Masonry'),
(5, 'Cleaning'),
(7, 'Technician'),
(8, 'Devoloper'),
(9, 'Painter'),
(14, 'Asus'),
(16, 'Builder');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `image` varchar(255) NOT NULL,
  `user_id` int NOT NULL,
  `category_id` int NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `due_date` date NOT NULL,
  `posted_date` datetime NOT NULL,
  `status` enum('active','ongoing','done','') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `title`, `description`, `image`, `user_id`, `category_id`, `location`, `phone`, `due_date`, `posted_date`, `status`) VALUES
(11, 'Car Engine Repair', 'Gasket Fix', 'uploads/enginedamage.jpg', 8, 2, 'no. 128, negombo rd, natthandiya', '0717598546', '2024-10-07', '2024-10-07 09:05:34', 'ongoing');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender_id` int DEFAULT NULL,
  `receiver_id` int DEFAULT NULL,
  `job_id` int DEFAULT NULL,
  `content` text,
  `timestamp` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `receiver_id` (`receiver_id`),
  KEY `sender_id` (`sender_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `job_id`, `content`, `timestamp`) VALUES
(30, 8, 8, 14, 'hi I am a plumber', '2024-10-08 16:12:21');

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

DROP TABLE IF EXISTS `quotations`;
CREATE TABLE IF NOT EXISTS `quotations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `job_id` int DEFAULT NULL,
  `contractor_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `job_id` (`job_id`),
  KEY `contractor_id` (`contractor_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `quotations`
--

INSERT INTO `quotations` (`id`, `job_id`, `contractor_id`, `user_id`, `file_path`, `sent_at`) VALUES
(5, 11, 9, 8, 'uploads/quotations/GigConnect.pdf', '2024-10-07 09:08:11');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int NOT NULL AUTO_INCREMENT,
  `contractor_id` int DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `job_id` int NOT NULL,
  `rating` int DEFAULT NULL,
  `review` text NOT NULL,
  `review_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `contractor_id` (`contractor_id`),
  KEY `customer_id` (`customer_id`),
  KEY `job_id` (`job_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `contractor_id`, `customer_id`, `job_id`, `rating`, `review`, `review_date`) VALUES
(13, 8, 8, 11, 3, 'well payed', '2024-10-08 09:20:47');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `usertype` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `degrees` varchar(255) NOT NULL,
  `profile_picture` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `phone`, `password`, `usertype`, `created_at`, `updated_at`, `degrees`, `profile_picture`) VALUES
(8, 'Gavindu Pradhap', 'Prathap', 'crqzyprqdhqp@gmail.com', '0766574092', '$2y$10$XwdwcMPX.eczoDQEjN4WBeXHreSExqoqTHnGIq.YRkqwgmQG9aWXa', 'Builder', '2024-10-07 09:02:41', '2024-10-08 10:31:57', 'uploads/degrees/cse5013-writ1 (1).pdf', 'uploads/profile_pics/111.jpg'),
(11, 'sadeesha', 'sadee', 'pradhapgavindu@gmail.com', '0766574092', '$2y$10$7OrdyphkAfxoU/1CU6cyNeESWKICMulqWifbSgsVuB1xbQBAE/d7K', 'customer', '2024-10-08 03:26:31', '2024-10-08 03:26:31', '', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
