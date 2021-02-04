-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 04, 2021 at 07:58 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `delta`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shortDesc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumb` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `difficulty` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Principiantes',
  `category` varchar(35) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `rating` float NOT NULL DEFAULT 0,
  `ratings` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `featured` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `lastUpdated` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `students` int(255) NOT NULL DEFAULT 0,
  `lessons` int(255) NOT NULL DEFAULT 0,
  `tutor` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses_lessons`
--

CREATE TABLE `courses_lessons` (
  `id` int(11) NOT NULL,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `video` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `showOrder` int(255) NOT NULL,
  `sectionId` int(255) NOT NULL,
  `courseId` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses_reviews`
--

CREATE TABLE `courses_reviews` (
  `id` int(11) NOT NULL,
  `student` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `stars` int(1) NOT NULL DEFAULT 1,
  `featured` varchar(3) NOT NULL DEFAULT 'no',
  `course` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `courses_sections`
--

CREATE TABLE `courses_sections` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `courseId` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses_youlearn`
--

CREATE TABLE `courses_youlearn` (
  `id` int(11) NOT NULL,
  `course` int(255) NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `type` int(2) NOT NULL DEFAULT -1 COMMENT 'Type of the notification',
  `receiver` varchar(255) NOT NULL COMMENT 'Name of the user who received the notification',
  `sender` varchar(255) NOT NULL COMMENT 'Name of the user who is creating the notification',
  `date` varchar(50) NOT NULL,
  `dataInt` int(255) NOT NULL DEFAULT -1 COMMENT 'If the notification needs to store an int value, it should be here.',
  `hasRead` varchar(3) NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `student_progress`
--

CREATE TABLE `student_progress` (
  `id` int(11) NOT NULL,
  `student` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `courseId` int(255) NOT NULL,
  `lessonId` int(255) NOT NULL,
  `sectionId` int(255) NOT NULL,
  `completedLessons` int(10) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tutorInfo`
--

CREATE TABLE `tutorInfo` (
  `id` int(11) NOT NULL,
  `tutor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shortDesc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unreadNotifications` int(255) NOT NULL DEFAULT 0,
  `registrationDate` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wantlearn` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ',',
  `finished` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `isTutor` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses_lessons`
--
ALTER TABLE `courses_lessons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses_reviews`
--
ALTER TABLE `courses_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses_sections`
--
ALTER TABLE `courses_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses_youlearn`
--
ALTER TABLE `courses_youlearn`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_progress`
--
ALTER TABLE `student_progress`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tutorInfo`
--
ALTER TABLE `tutorInfo`
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
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courses_lessons`
--
ALTER TABLE `courses_lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courses_reviews`
--
ALTER TABLE `courses_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courses_sections`
--
ALTER TABLE `courses_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courses_youlearn`
--
ALTER TABLE `courses_youlearn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_progress`
--
ALTER TABLE `student_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tutorInfo`
--
ALTER TABLE `tutorInfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
