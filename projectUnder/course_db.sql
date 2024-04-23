-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Apr 02, 2024 at 03:59 AM
-- Server version: 8.0.18
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `course_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

DROP TABLE IF EXISTS `exams`;
CREATE TABLE IF NOT EXISTS `exams` (
  `exam_id` int(11) NOT NULL AUTO_INCREMENT,
  `tutor_id` varchar(50) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `time` varchar(10) DEFAULT NULL,
  `description` text,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`exam_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`exam_id`, `tutor_id`, `title`, `time`, `description`, `password`, `created_at`) VALUES
(19, 'CyxmkayVnjSvyQu5p4s1', 'Semester 02 Final Paper', '2 hour', 'Answer all the questions in the paper.', '456', '2024-03-20 10:18:22'),
(20, 'CyxmkayVnjSvyQu5p4s1', 'Semester 01 mid exam', '1 hour', 'Answer all questions in paper.', '789', '2024-03-20 10:24:03');

-- --------------------------------------------------------

--
-- Table structure for table `question_papers`
--

DROP TABLE IF EXISTS `question_papers`;
CREATE TABLE IF NOT EXISTS `question_papers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tutor_id` varchar(50) DEFAULT NULL,
  `exam_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `question_number` int(11) NOT NULL,
  `question` text,
  `answer` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=262 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `question_papers`
--

INSERT INTO `question_papers` (`id`, `tutor_id`, `exam_id`, `title`, `question_number`, `question`, `answer`, `created_at`) VALUES
(261, 'CyxmkayVnjSvyQu5p4s1', 20, 'Semester 01 mid exam', 2, 'What is CSS use for?', '\r\nCSS is used to define styles for your web pages, including the design, layout and variations in display for different devices and screen sizes.', '2024-03-20 10:25:23'),
(260, 'CyxmkayVnjSvyQu5p4s1', 20, 'Semester 01 mid exam', 1, 'What is HTML?', 'HTML (HyperText Markup Language) is the code that is used to structure a web page and its content.', '2024-03-20 10:24:47'),
(259, 'CyxmkayVnjSvyQu5p4s1', 19, 'Semester 02 Final Paper', 3, 'What Python means?', '\r\nPython is an interpreted, object-oriented, high-level programming language with dynamic semantics ', '2024-03-20 10:21:28'),
(257, 'CyxmkayVnjSvyQu5p4s1', 19, 'Semester 02 Final Paper', 1, 'what is database?', 'A  database is information that is set up for easy access, management and updating.', '2024-03-20 10:19:34'),
(258, 'CyxmkayVnjSvyQu5p4s1', 19, 'Semester 02 Final Paper', 2, 'What is Inernet?', 'The Internet is a vast network that connects computers all over the world. ', '2024-03-20 10:20:10');

-- --------------------------------------------------------

--
-- Table structure for table `tutors`
--

DROP TABLE IF EXISTS `tutors`;
CREATE TABLE IF NOT EXISTS `tutors` (
  `id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `profession` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `country` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tutors`
--

INSERT INTO `tutors` (`id`, `name`, `profession`, `email`, `country`, `city`, `password`, `image`) VALUES
('CyxmkayVnjSvyQu5p4s1', 'admin1', '', 'admin1@gmail.com', 'Sri Lanka', 'colombo', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'F3ttpbU2ULGP63oeSbZq.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `studentNo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `year` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(50) NOT NULL,
  `image` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `studentNo`, `year`, `password`, `image`) VALUES
(1, 'user1', 'user1@gmail.com', 'SN001', '2nd year', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'qHaqN06773l9Bk0IuZr2.png'),
(2, 'user2', 'user2@gmail.com', 'SN002', '2nd year', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '4NQI6sJHcRZ0TUwEZ4uy.jpg'),
(3, 'user3', 'user3@gmail.com', 'SN003', '2nd year', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '2rIvyFwY7HmSXKvab59X.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user_answers`
--

DROP TABLE IF EXISTS `user_answers`;
CREATE TABLE IF NOT EXISTS `user_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `exam_id` int(11) DEFAULT NULL,
  `question_number` int(11) DEFAULT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `exam_id` (`exam_id`),
  KEY `question_number` (`question_number`)
) ENGINE=MyISAM AUTO_INCREMENT=138 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_answers`
--

INSERT INTO `user_answers` (`id`, `user_id`, `exam_id`, `question_number`, `question`, `answer`, `created_at`) VALUES
(137, 1, 20, 1, 'What is HTML?', 'HTML is hypertxt marcup language.', '2024-03-23 15:54:40'),
(136, 1, 20, 2, 'What is CSS use for?', 'style the HTML pages.', '2024-03-23 15:54:40'),
(135, 1, 19, 2, 'What is Inernet?', 'internet is WWW.', '2024-03-20 10:22:37'),
(134, 1, 19, 1, 'what is database?', 'database is table that is storing data.', '2024-03-20 10:22:37'),
(133, 1, 19, 3, 'What Python means?', 'python is a framework.;', '2024-03-20 10:22:37');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
