-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2024 at 03:36 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `try`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_list`
--

CREATE TABLE `academic_list` (
  `id` int(30) NOT NULL,
  `year` text NOT NULL,
  `semester` int(30) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `status` int(1) NOT NULL DEFAULT 0 COMMENT '0=Pending,1=Start,2=Closed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `academic_list`
--

INSERT INTO `academic_list` (`id`, `year`, `semester`, `is_default`, `status`) VALUES
(6, '2023-2024', 0, 1, 1),
(7, '2022-2023', 0, 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `class_list`
--

CREATE TABLE `class_list` (
  `id` int(30) NOT NULL,
  `curriculum` text NOT NULL,
  `level` text NOT NULL,
  `section` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `class_list`
--

INSERT INTO `class_list` (`id`, `curriculum`, `level`, `section`) VALUES
(6, '', 'Grade 9', 'A'),
(7, '', 'Grade 9', 'B');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(10) UNSIGNED NOT NULL,
  `evaluation_id` int(11) DEFAULT NULL,
  `comment` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `evaluation_id`, `comment`) VALUES
(1, 14, 'he is great'),
(2, 15, 'Testing 2022-2023'),
(3, 16, 'testing'),
(4, 17, 'testing2'),
(5, 18, 'Very great!'),
(6, 19, 'This is for Juan Dela Cruz'),
(7, 20, 'This is from jonathan to Javer Borngo'),
(8, 21, 'This is from jonathan to Juan Dela Cruz'),
(9, 23, 'thanks');

-- --------------------------------------------------------

--
-- Table structure for table `criteria_list`
--

CREATE TABLE `criteria_list` (
  `id` int(30) NOT NULL,
  `criteria` text NOT NULL,
  `order_by` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `criteria_list`
--

INSERT INTO `criteria_list` (`id`, `criteria`, `order_by`) VALUES
(1, 'Criteria 101', 0),
(2, 'Criteria 102', 1);

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_answers`
--

CREATE TABLE `evaluation_answers` (
  `evaluation_id` int(30) NOT NULL,
  `question_id` int(30) NOT NULL,
  `rate` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `evaluation_answers`
--

INSERT INTO `evaluation_answers` (`evaluation_id`, `question_id`, `rate`) VALUES
(1, 1, 5),
(1, 6, 4),
(1, 3, 5),
(2, 1, 5),
(2, 6, 5),
(2, 3, 4),
(3, 1, 5),
(3, 6, 5),
(3, 3, 4),
(4, 1, 5),
(4, 6, 5),
(4, 3, 5),
(5, 1, 1),
(5, 6, 1),
(5, 3, 1),
(6, 7, 3),
(7, 7, 1),
(8, 8, 5),
(8, 9, 5),
(9, 8, 3),
(9, 9, 4),
(10, 8, 3),
(10, 9, 3),
(11, 8, 2),
(11, 9, 1),
(12, 8, 1),
(12, 9, 2),
(13, 8, 1),
(13, 9, 1),
(14, 8, 5),
(14, 9, 5),
(15, 10, 5),
(15, 11, 5),
(16, 8, 4),
(16, 9, 4),
(17, 8, 5),
(17, 9, 1),
(18, 8, 4),
(18, 12, 4),
(18, 9, 3),
(19, 8, 5),
(19, 12, 4),
(19, 9, 3),
(20, 8, 5),
(20, 12, 5),
(20, 9, 3),
(21, 8, 4),
(21, 12, 4),
(21, 9, 5),
(23, 8, 3),
(23, 12, 3),
(23, 9, 3);

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_list`
--

CREATE TABLE `evaluation_list` (
  `evaluation_id` int(30) NOT NULL,
  `academic_id` int(30) NOT NULL,
  `class_id` int(30) NOT NULL,
  `student_id` int(30) NOT NULL,
  `subject_id` int(30) NOT NULL,
  `faculty_id` int(30) NOT NULL,
  `restriction_id` int(30) NOT NULL,
  `date_taken` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `evaluation_list`
--

INSERT INTO `evaluation_list` (`evaluation_id`, `academic_id`, `class_id`, `student_id`, `subject_id`, `faculty_id`, `restriction_id`, `date_taken`) VALUES
(1, 3, 1, 1, 1, 1, 8, '2020-12-15 16:26:51'),
(2, 3, 2, 2, 2, 1, 9, '2020-12-15 16:33:37'),
(3, 3, 1, 3, 1, 1, 8, '2020-12-15 20:18:49'),
(4, 3, 1, 4, 0, 2, 12, '2024-04-26 15:31:30'),
(5, 3, 3, 6, 0, 4, 15, '2024-04-26 16:03:55'),
(6, 4, 4, 7, 0, 6, 16, '2024-04-29 12:15:31'),
(7, 4, 5, 9, 0, 6, 17, '2024-04-29 12:40:11'),
(8, 6, 6, 10, 0, 10, 18, '2024-04-29 14:07:51'),
(9, 6, 7, 11, 0, 10, 19, '2024-04-29 14:08:27'),
(10, 6, 7, 12, 0, 10, 19, '2024-04-29 14:08:56'),
(11, 6, 6, 13, 0, 10, 18, '2024-04-29 17:24:34'),
(14, 6, 6, 14, 0, 10, 18, '2024-04-30 09:41:23'),
(15, 7, 6, 14, 0, 10, 20, '2024-04-30 10:33:02'),
(16, 6, 6, 15, 0, 10, 18, '2024-04-30 15:58:46'),
(17, 6, 6, 16, 0, 10, 18, '2024-04-30 16:00:16'),
(18, 6, 6, 17, 0, 10, 18, '2024-04-30 16:59:49'),
(19, 6, 6, 14, 0, 11, 21, '2024-04-30 19:05:00'),
(20, 6, 6, 18, 0, 10, 18, '2024-04-30 21:08:15'),
(21, 6, 6, 18, 0, 11, 21, '2024-04-30 21:08:32'),
(22, 6, 6, 19, 0, 10, 18, '2024-04-30 21:29:45'),
(23, 6, 6, 19, 0, 11, 21, '2024-04-30 21:29:55');

-- --------------------------------------------------------

--
-- Table structure for table `faculty_list`
--

CREATE TABLE `faculty_list` (
  `id` int(30) NOT NULL,
  `school_id` varchar(100) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `avatar` text NOT NULL DEFAULT 'no-image-available.png',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `faculty_list`
--

INSERT INTO `faculty_list` (`id`, `school_id`, `firstname`, `lastname`, `email`, `password`, `avatar`, `date_created`) VALUES
(10, '', 'Javer', 'Borngo', 'Borngo@javer.com', '202cb962ac59075b964b07152d234b70', 'no-image-available.png', '2024-04-29 14:04:34'),
(11, '', 'Juan', 'Dela Cruz', 'juan@gmail.com', 'c93ccd78b2076528346216b3b2f701e6', '1714474980_dp-man.jpeg', '2024-04-30 19:03:10');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `login_id` int(11) NOT NULL,
  `f_email` varchar(200) NOT NULL,
  `f_pass` text NOT NULL,
  `s_email` varchar(200) NOT NULL,
  `s_pass` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `question_list`
--

CREATE TABLE `question_list` (
  `id` int(30) NOT NULL,
  `academic_id` int(30) NOT NULL,
  `question` text NOT NULL,
  `order_by` int(30) NOT NULL,
  `criteria_id` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `question_list`
--

INSERT INTO `question_list` (`id`, `academic_id`, `question`, `order_by`, `criteria_id`) VALUES
(1, 3, 'Sample Question', 0, 1),
(3, 3, 'Test', 2, 2),
(5, 0, 'Question 101', 0, 1),
(6, 3, 'Sample 101', 4, 1),
(7, 4, 'Performance', 0, 1),
(8, 6, 'Performance', 0, 1),
(9, 6, 'Relationship', 2, 2),
(10, 7, 'Question 1', 0, 1),
(11, 7, 'Question 2', 1, 2),
(12, 6, 'Engagement', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `restriction_list`
--

CREATE TABLE `restriction_list` (
  `id` int(30) NOT NULL,
  `academic_id` int(30) NOT NULL,
  `faculty_id` int(30) NOT NULL,
  `class_id` int(30) NOT NULL,
  `subject_id` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `restriction_list`
--

INSERT INTO `restriction_list` (`id`, `academic_id`, `faculty_id`, `class_id`, `subject_id`) VALUES
(9, 3, 1, 2, 2),
(10, 3, 1, 3, 3),
(11, 3, 1, 1, 0),
(12, 3, 2, 1, 0),
(13, 3, 3, 1, 0),
(14, 3, 3, 2, 0),
(15, 3, 4, 3, 0),
(16, 4, 6, 4, 0),
(17, 4, 6, 5, 0),
(18, 6, 10, 6, 0),
(19, 6, 10, 7, 0),
(20, 7, 10, 6, 0),
(21, 6, 11, 6, 0),
(22, 6, 11, 7, 0);

-- --------------------------------------------------------

--
-- Table structure for table `student_list`
--

CREATE TABLE `student_list` (
  `id` int(30) NOT NULL,
  `school_id` varchar(100) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `class_id` int(30) NOT NULL,
  `avatar` text NOT NULL DEFAULT 'no-image-available.png',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_list`
--

INSERT INTO `student_list` (`id`, `school_id`, `firstname`, `lastname`, `email`, `password`, `class_id`, `avatar`, `date_created`) VALUES
(10, '1010169', 'Rica', 'Aliponga', 'rganolonaliponga@gmail.com', '202cb962ac59075b964b07152d234b70', 6, 'no-image-available.png', '2024-04-29 14:05:20'),
(11, '12345', 'Flor', 'Labrador', 'flor@com', '202cb962ac59075b964b07152d234b70', 7, 'no-image-available.png', '2024-04-29 14:06:03'),
(12, '123456', 'Joy', 'Balagot', 'joyjoy@gmail.com', '202cb962ac59075b964b07152d234b70', 7, 'no-image-available.png', '2024-04-29 14:06:38'),
(14, '12345', 'John', 'Doe', 'johndoe@gmail.com', 'c93ccd78b2076528346216b3b2f701e6', 6, '1714402860_dp-man.jpeg', '2024-04-29 23:01:21'),
(15, '12321', 'Elon', 'Musk', 'elon@gmail.com', 'c93ccd78b2076528346216b3b2f701e6', 6, '1714463880_dp-woman.jpg', '2024-04-30 15:58:30'),
(16, '12345', 'Jennifer', 'Lopez', 'jen@gmail.com', 'c93ccd78b2076528346216b3b2f701e6', 6, '1714464000_5712HSA-solar-panels-1.webp', '2024-04-30 16:00:02'),
(17, '32414', 'Spongebob', 'Squarepants', 'sponge@gmail.com', 'c93ccd78b2076528346216b3b2f701e6', 6, '1714467540_book-cover.jpeg', '2024-04-30 16:59:29'),
(18, '123214', 'Jonathan', 'Dunkit', 'jonathan@gmail.com', 'c93ccd78b2076528346216b3b2f701e6', 6, '1714482420_dp-man.jpeg', '2024-04-30 21:07:11'),
(19, '123435', 'George', 'Gerard', 'george@gmail.com', 'c93ccd78b2076528346216b3b2f701e6', 6, '1714483680_dp-man.jpeg', '2024-04-30 21:28:03');

-- --------------------------------------------------------

--
-- Table structure for table `subject_list`
--

CREATE TABLE `subject_list` (
  `id` int(30) NOT NULL,
  `code` varchar(50) NOT NULL,
  `subject` text NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject_list`
--

INSERT INTO `subject_list` (`id`, `code`, `subject`, `description`) VALUES
(1, '101', 'Sample Subject', 'Test 101'),
(2, 'ENG-101', 'English', 'English'),
(3, 'M-101', 'Math 101', 'Math - Advance Algebra ');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `cover_img` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`, `address`, `cover_img`) VALUES
(1, 'Faculty Evaluation System', 'info@sample.comm', '+6948 8542 623', '2102  Caldwell Road, Rochester, New York, 14608', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `avatar` text NOT NULL DEFAULT 'no-image-available.png',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `avatar`, `date_created`) VALUES
(1, 'Administrator', '', 'admin@admin.com', '0192023a7bbd73250516f069df18b500', '1607135820_avatar.jpg', '2020-11-26 10:57:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_list`
--
ALTER TABLE `academic_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_list`
--
ALTER TABLE `class_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comment_evaluation_list` (`evaluation_id`);

--
-- Indexes for table `criteria_list`
--
ALTER TABLE `criteria_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluation_list`
--
ALTER TABLE `evaluation_list`
  ADD PRIMARY KEY (`evaluation_id`);

--
-- Indexes for table `faculty_list`
--
ALTER TABLE `faculty_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `question_list`
--
ALTER TABLE `question_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `restriction_list`
--
ALTER TABLE `restriction_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_list`
--
ALTER TABLE `student_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subject_list`
--
ALTER TABLE `subject_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
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
-- AUTO_INCREMENT for table `academic_list`
--
ALTER TABLE `academic_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `class_list`
--
ALTER TABLE `class_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `criteria_list`
--
ALTER TABLE `criteria_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `evaluation_list`
--
ALTER TABLE `evaluation_list`
  MODIFY `evaluation_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `faculty_list`
--
ALTER TABLE `faculty_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `question_list`
--
ALTER TABLE `question_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `restriction_list`
--
ALTER TABLE `restriction_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `student_list`
--
ALTER TABLE `student_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `subject_list`
--
ALTER TABLE `subject_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_evaluation_list` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluation_list` (`evaluation_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
