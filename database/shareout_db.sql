-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Aug 16, 2023 at 09:04 PM
-- Server version: 5.7.24
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shareout_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `ID` varchar(8) NOT NULL,
  `file_size` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `ID_user` int(8) UNSIGNED NOT NULL,
  `upload_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`ID`, `file_size`, `file_name`, `file_type`, `path`, `ID_user`, `upload_date`, `last_update`) VALUES
('1104A4Lm', '78135', 'bootstrap.min.js', 'text/javascript', 'files/bootstrap.min.js', 8804700, '2023-07-16 14:56:28', '2023-07-16 14:56:28'),
('JfcPM47F', '78135', 'bootstrap.min.js', 'text/javascript', 'files/bootstrap.min.js', 54001724, '2023-07-16 14:43:32', '2023-07-16 14:43:32'),
('kT5PZEZO', '242', 'become_admin.php', 'application/octet-stream', 'files/become_admin.php', 8804700, '2023-07-16 15:03:32', '2023-07-16 15:03:32'),
('xrvsYkb6', '163878', 'bootstrap.min.css', 'text/css', 'files/bootstrap.min.css', 54001724, '2023-07-16 10:21:01', '2023-07-16 10:21:01'),
('z7vlh7pw', '83945', 'page3.png', 'image/png', 'files/page3.png', 54001724, '2023-07-15 23:52:58', '2023-07-16 14:24:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(8) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_` varchar(255) NOT NULL,
  `user_storage` bigint(70) NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isAdmin` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `username`, `email`, `password_`, `user_storage`, `creation_date`, `last_login`, `isAdmin`) VALUES
(8804700, 'amaazou', 'a@outlook.fr', 'bfe1afeca2672199da69c04bdf87f48a', 10000000000, '2023-07-16 14:53:51', '2023-07-16 15:18:31', 1),
(54001724, 'amazou', 'amazou@outlook.fr', 'bfe1afeca2672199da69c04bdf87f48a', 1000000000, '2023-07-15 23:44:37', '2023-07-16 14:56:42', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_file_user` (`ID_user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `fk_file_user` FOREIGN KEY (`ID_user`) REFERENCES `users` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
