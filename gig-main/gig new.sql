-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:4306
-- Generation Time: Oct 14, 2024 at 04:44 PM
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
-- Database: `gig`
--

-- --------------------------------------------------------

--
-- Table structure for table `ads`
--

CREATE TABLE `ads` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `due_date` date NOT NULL,
  `posted_date` datetime NOT NULL,
  `status` enum('active','ongoing','done','') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `title`, `description`, `image`, `user_id`, `category_id`, `location`, `phone`, `due_date`, `posted_date`, `status`) VALUES
(11, 'Car Engine Repair', 'Gasket Fix', 'uploads/enginedamage.jpg', 8, 2, 'no. 128, negombo rd, natthandiya', '0717598546', '2024-10-07', '2024-10-07 09:05:34', 'ongoing'),
(15, 'dhushy0401@gmail.com', 'dhushy0401@gmail.com', 'uploads/Screenshot 2024-09-23 161448.png', 15, 1, 'dhushy0401@gmail.com', '121212121', '2024-10-02', '2024-10-13 07:27:57', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `job_id` int(11) DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `job_id`, `timestamp`) VALUES
(53, 14, 15, 15, '2024-10-13 15:12:00');

-- --------------------------------------------------------

--
-- Table structure for table `message_content`
--

CREATE TABLE `message_content` (
  `id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp(),
  `sendBy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message_content`
--

INSERT INTO `message_content` (`id`, `message_id`, `content`, `timestamp`, `sendBy`) VALUES
(50, 53, 'hi', '2024-10-13 15:13:45', 14),
(52, 53, 'hi', '2024-10-13 15:14:30', 14),
(53, 53, 'hihp', '2024-10-13 15:14:36', 14),
(54, 53, 'hi', '2024-10-13 15:16:11', 14),
(56, 53, 'f', '2024-10-13 15:19:28', 14),
(66, 53, 'dad', '2024-10-13 15:39:30', 14),
(67, 53, 'adad', '2024-10-13 15:39:45', 14),
(68, 53, 'dad', '2024-10-13 15:42:09', 14),
(69, 53, 'dadade2qe', '2024-10-13 15:42:20', 14),
(70, 53, 'da13', '2024-10-13 15:45:47', 14),
(71, 53, '131', '2024-10-13 15:45:53', 14),
(72, 53, 'dad', '2024-10-13 15:48:38', 14),
(73, 53, 'dad', '2024-10-13 15:48:42', 14),
(74, 53, 'dadaddad', '2024-10-13 16:09:09', 14),
(75, 53, 'jo', '2024-10-13 21:52:02', 14),
(76, 53, 'no', '2024-10-13 21:58:21', 14),
(78, 53, 'ajofafnpaf', '2024-10-13 23:56:43', 14),
(79, 53, 'ajofafnpaf', '2024-10-14 00:05:20', 14),
(80, 53, 'faohfafafbafoa', '2024-10-14 00:12:44', 15),
(84, 53, 'hi', '2024-10-14 00:23:11', 15),
(85, 53, 'hi', '2024-10-14 00:24:08', 15),
(86, 53, 'hi', '2024-10-14 00:24:36', 14),
(87, 53, 'daf', '2024-10-14 00:27:22', 15),
(88, 53, 'fuck you', '2024-10-14 00:40:04', 15),
(89, 53, 'daojpfa\r\n', '2024-10-14 00:43:28', 14),
(90, 53, 'dahoa\r\n', '2024-10-14 00:49:43', 14),
(91, 53, 'hi', '2024-10-14 01:01:20', 15),
(92, 53, 'hi', '2024-10-14 01:02:46', 15);

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `id` int(11) NOT NULL,
  `job_id` int(11) DEFAULT NULL,
  `contractor_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quotations`
--

INSERT INTO `quotations` (`id`, `job_id`, `contractor_id`, `user_id`, `file_path`, `sent_at`) VALUES
(5, 11, 9, 8, 'uploads/quotations/GigConnect.pdf', '2024-10-07 09:08:11');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `contractor_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `job_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `review` text NOT NULL,
  `review_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `contractor_id`, `customer_id`, `job_id`, `rating`, `review`, `review_date`) VALUES
(13, 8, 8, 11, 3, 'well payed', '2024-10-08 09:20:47');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `usertype` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `degrees` varchar(255) NOT NULL,
  `profile_picture` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `phone`, `password`, `usertype`, `created_at`, `updated_at`, `degrees`, `profile_picture`) VALUES
(8, 'Gavindu Pradhap', 'Prathap', 'crqzyprqdhqp@gmail.com', '0766574092', '$2y$10$XwdwcMPX.eczoDQEjN4WBeXHreSExqoqTHnGIq.YRkqwgmQG9aWXa', 'Builder', '2024-10-07 09:02:41', '2024-10-08 10:31:57', 'uploads/degrees/cse5013-writ1 (1).pdf', 'uploads/profile_pics/111.jpg'),
(11, 'sadeesha', 'sadee', 'pradhapgavindu@gmail.com', '0766574092', '$2y$10$7OrdyphkAfxoU/1CU6cyNeESWKICMulqWifbSgsVuB1xbQBAE/d7K', 'customer', '2024-10-08 03:26:31', '2024-10-08 03:26:31', '', ''),
(14, 'prabud0401@gmail.com', 'prabud0401@gmail.com', 'prabud0401@gmail.com', '0760704491', '$2y$10$x0lXsoTx62yP9/Fcjph88ORMze/87dQjjZRer7qAh8uzSRcE52ZTe', 'customer', '2024-10-13 05:25:05', '2024-10-13 05:25:05', '', ''),
(15, 'dhushy0401@gmail.com', 'dhushy0401@gmail.com', 'dhushy0401@gmail.com', '0760704491', '$2y$10$3oXQwC3oGVPgoBC0itHPy.wtDKWbyhbVcKkgy826pHIq0PDKxK6xW', 'gig_worker', '2024-10-13 05:27:01', '2024-10-13 05:27:01', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ads`
--
ALTER TABLE `ads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receiver_id` (`receiver_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indexes for table `message_content`
--
ALTER TABLE `message_content`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_id` (`message_id`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `contractor_id` (`contractor_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contractor_id` (`contractor_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ads`
--
ALTER TABLE `ads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `message_content`
--
ALTER TABLE `message_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `message_content`
--
ALTER TABLE `message_content`
  ADD CONSTRAINT `message_content_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
