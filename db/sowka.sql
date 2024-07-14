-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Lip 14, 2024 at 12:21 PM
-- Wersja serwera: 10.4.28-MariaDB
-- Wersja PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sowka`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `textbooks`
--

CREATE TABLE `textbooks` (
  `id` bigint(20) NOT NULL,
  `dataname` varchar(100) NOT NULL,
  `series` varchar(100) NOT NULL,
  `grade` varchar(100) NOT NULL,
  `subjectt` varchar(100) NOT NULL,
  `publisher` varchar(100) NOT NULL,
  `img` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `textbooks`
--

INSERT INTO `textbooks` (`id`, `dataname`, `series`, `grade`, `subjectt`, `publisher`, `img`) VALUES
(1, 'matematyka 1 - zakres podstawowy nowa era', 'MATeMAtyka - zakres podstawowy [Nowa Era]', '1', 'matematyka', 'Nowa Era', 'MATeMAtyka 1 - zakres podstawowy.jpg'),
(2, 'matematyka 2 - zakres podstawowy nowa era', 'MATeMAtyka - zakres podstawowy [Nowa Era]', '2', 'matematyka', 'Nowa Era', 'MATeMAtyka 2 - zakres podstawowy.jpg'),
(3, 'matematyka 3 - zakres podstawowy nowa era', 'MATeMAtyka - zakres podstawowy [Nowa Era]', '3', 'matematyka', 'Nowa Era', 'MATeMAtyka 3 - zakres podstawowy.jpg'),
(4, 'matematyka 4 - zakres podstawowy nowa era', 'MATeMAtyka - zakres podstawowy [Nowa Era]', '4, 5', 'matematyka', 'Nowa Era', 'MATeMAtyka 4 - zakres podstawowy.jpg'),
(5, 'matematyka 1 - zakres podstawowy i rozszerzony nowa era', 'MATeMAtyka - zakres podstawowy i rozszerzony [Nowa Era]', '1', 'matematyka', 'Nowa Era', 'MATeMAtyka 1 - zakres podstawowy i rozszerzony.jpg'),
(6, 'matematyka 2 - zakres podstawowy i rozszerzony nowa era', 'MATeMAtyka - zakres podstawowy i rozszerzony [Nowa Era]', '2', 'matematyka', 'Nowa Era', 'MATeMAtyka 2 - zakres podstawowy i rozszerzony.jpg'),
(7, 'matematyka 3 - zakres podstawowy i rozszerzony nowa era', 'MATeMAtyka - zakres podstawowy i rozszerzony [Nowa Era]', '3', 'matematyka', 'Nowa Era', 'MATeMAtyka 3 - zakres podstawowy i rozszerzony.jpg'),
(8, 'matematyka 4 - zakres podstawowy i rozszerzony nowa era', 'MATeMAtyka - zakres podstawowy i rozszerzony [Nowa Era]', '4, 5', 'matematyka', 'Nowa Era', 'MATeMAtyka 4 - zakres podstawowy i rozszerzony.jpg');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `favorites` varchar(255) DEFAULT '',
  `subscription` date NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `favorites`, `subscription`, `reset_token`, `reset_expires`) VALUES
(4, 'kacper2019114@gmail.com', '$2y$10$1Ho3dNINnw72jl/SjxFy8eMiLUDsNIPLX5dp7/vzrQjRImWtQjfuG', ',matematyka 1 - zakres podstawowy i rozszerzony nowa era,matematyka 2 - zakres podstawowy i rozszerzony nowa era,matematyka 1 - zakres podstawowy nowa era,matematyka 2 - zakres podstawowy nowa era,matematyka 3 - zakres podstawowy nowa era', '0000-00-00', 'f286f6d4283cfde96b8b3b2b2fd7bc25cab9383bc0eb35b41f46ec00170d445cdba79f7e4658260b8811cd32b168fb411d4f', 1720358889),
(5, 'Kacper2021114@wp.pl', '$2y$10$Sco3jql34XZkcCigiFKmaeEPCvtNFtcJbCXYcABbOJs5lZN..F.pG', '', '0000-00-00', '73d4b0f67d5fd6ce93fdd04118fc2d63691a04377a8e7c9e165c93923f534f1c2486b6a422b121d31c6410d4c4773155be7d', 1720356539),
(11, 'test123@gmail.com', '$2y$10$kHVZB0Aer2JpHawip0eLeu6yqmV1pF6Mhj2SLPa9fGMh8njfao2ce', '', '2024-07-14', NULL, NULL);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `textbooks`
--
ALTER TABLE `textbooks`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `textbooks`
--
ALTER TABLE `textbooks`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
