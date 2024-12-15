-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 15, 2024 at 08:58 AM
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
-- Database: `TravelAgencyDbFinal`
--

-- --------------------------------------------------------

--
-- Table structure for table `Agent`
--

CREATE TABLE `Agent` (
  `agentID` varchar(4) NOT NULL,
  `agentName` varchar(100) NOT NULL,
  `agentSex` char(1) NOT NULL,
  `agentDOB` date NOT NULL,
  `agentPhone` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Agent`
--

INSERT INTO `Agent` (`agentID`, `agentName`, `agentSex`, `agentDOB`, `agentPhone`) VALUES
('A001', 'Agus Salimss', 'M', '1980-07-15', 812345678),
('A002', 'Michelle Tjandra', 'F', '2002-03-22', 812345679),
('A003', 'Siti Kurniawan', 'F', '1996-11-30', 812345680),
('A004', 'put', 'f', '1992-08-25', 1992),
('A005', 'PUTRI HARTANA', 'M', '1992-08-25', 812345682),
('A009', 'MAARPUTRA GAANS', 'M', '1992-08-25', 812345682);

-- --------------------------------------------------------

--
-- Table structure for table `Booking`
--

CREATE TABLE `Booking` (
  `bookingID` varchar(4) NOT NULL,
  `agentID` varchar(4) DEFAULT NULL,
  `customerID` varchar(4) DEFAULT NULL,
  `packageID` varchar(4) DEFAULT NULL,
  `bookedDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Booking`
--

INSERT INTO `Booking` (`bookingID`, `agentID`, `customerID`, `packageID`, `bookedDate`) VALUES
('B001', 'A001', 'C001', 'PKG1', '2023-12-01'),
('B002', 'A002', 'C002', 'PKG2', '2023-01-10'),
('B003', 'A003', 'C003', 'PKG3', '2023-01-15'),
('B004', 'A004', 'C004', 'PKG4', '2023-02-05'),
('B005', 'A005', 'C005', 'PKG5', '2023-03-20'),
('B006', 'A001', 'C001', 'PKG1', '2024-12-19'),
('B007', 'A001', 'C011', 'PKG1', '2024-12-29');

-- --------------------------------------------------------

--
-- Table structure for table `Customer`
--

CREATE TABLE `Customer` (
  `customerID` varchar(4) NOT NULL,
  `customerFName` varchar(30) NOT NULL,
  `customerLName` varchar(30) NOT NULL,
  `customerSex` char(1) NOT NULL,
  `customerDOB` date DEFAULT NULL,
  `customerEmail` varchar(50) NOT NULL,
  `customerPhone` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Customer`
--

INSERT INTO `Customer` (`customerID`, `customerFName`, `customerLName`, `customerSex`, `customerDOB`, `customerEmail`, `customerPhone`) VALUES
('C001', 'Kevin', 'Hartono', 'M', '1997-02-17', 'kevinhartono123@gmail.com', 812345683),
('C002', 'Sari', 'Nur Salim', 'F', '2001-09-12', 'sar1r0t1@gmail.com', 812345684),
('C003', 'Ahmad', 'Wahyudi', 'M', '1992-07-24', 'ahmad.wahyudi@gmail.com', 812345685),
('C004', 'Ayu', 'Lestari', 'F', '2003-01-05', 'ayutenan@gmail.com', 812345686),
('C005', 'Gregory', 'Saputra', 'M', '1988-06-11', 'saputragregory@gmail.com', 812345687),
('C011', 'John', 'DoeS', 'M', '1985-05-15', 'john.doe@example.com', 1234567890);

-- --------------------------------------------------------

--
-- Table structure for table `Destination`
--

CREATE TABLE `Destination` (
  `destinationID` varchar(4) NOT NULL,
  `destinationContinent` varchar(15) NOT NULL,
  `destinationCountry` varchar(30) NOT NULL,
  `destinationCity` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Destination`
--

INSERT INTO `Destination` (`destinationID`, `destinationContinent`, `destinationCountry`, `destinationCity`) VALUES
('010', 'Europe', 'France', 'Paris'),
('D001', 'Asia', 'Indonesia', 'Yogjakarta'),
('D002', 'Asia', 'Malaysia', 'Kuala Lumpur'),
('D003', 'Asia', 'Indonesia', 'Bali'),
('D004', 'Asia', 'Vietnam', 'Hanoi'),
('D005', 'Asia', 'Thailand', 'Bangkok');

-- --------------------------------------------------------

--
-- Table structure for table `Itinerary`
--

CREATE TABLE `Itinerary` (
  `itineraryID` varchar(4) NOT NULL,
  `itineraryDay` int(11) NOT NULL,
  `itineraryActivity` varchar(40) NOT NULL,
  `itineraryTransport` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Itinerary`
--

INSERT INTO `Itinerary` (`itineraryID`, `itineraryDay`, `itineraryActivity`, `itineraryTransport`) VALUES
('020', 1, 'City Tour', 'Bus'),
('I001', 1, 'Batik Making', 'Shuttle Busss'),
('I002', 1, 'Museum Visit', 'Rental Bike'),
('I003', 1, 'Mountain Hiking', 'Rental Motorcycle'),
('I004', 1, 'Beach Day', 'Rental Van'),
('I005', 1, 'Local Cuisine', 'Rental Car'),
('I090', 6, 'dds', 'dsaasd');

-- --------------------------------------------------------

--
-- Table structure for table `Package`
--

CREATE TABLE `Package` (
  `packageID` varchar(4) NOT NULL,
  `packageName` varchar(100) NOT NULL,
  `destinationID` varchar(4) DEFAULT NULL,
  `packageTransport` varchar(20) DEFAULT NULL,
  `packageSDate` date NOT NULL,
  `packageEDate` date NOT NULL,
  `packageTDays` int(11) NOT NULL,
  `itineraryID` varchar(4) DEFAULT NULL,
  `itineraryDay` int(11) DEFAULT NULL,
  `packageAccommodation` varchar(1) NOT NULL,
  `paymentID` varchar(4) DEFAULT NULL,
  `packagePrice` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Package`
--

INSERT INTO `Package` (`packageID`, `packageName`, `destinationID`, `packageTransport`, `packageSDate`, `packageEDate`, `packageTDays`, `itineraryID`, `itineraryDay`, `packageAccommodation`, `paymentID`, `packagePrice`) VALUES
('PKG1', 'Yogjakarta Culture', 'D001', 'Plane', '2023-12-01', '2023-12-07', 7, 'I001', 1, 'T', 'P001', 5000000),
('PKG2', 'Kuala Lumpur Experience', 'D002', 'Plane', '2023-01-10', '2023-01-17', 8, 'I002', 1, 'T', 'P002', 6000000),
('PKG3', 'Bali Adventure', 'D003', 'Plane', '2023-01-15', '2023-01-21', 7, 'I003', 1, 'T', 'P003', 7500000),
('PKG4', 'Hanoi Highlights', 'D004', 'Plane', '2023-02-05', '2023-02-12', 8, 'I004', 1, 'T', 'P004', 8000000),
('PKG5', 'Bangkok History', 'D005', 'Plane', '2023-03-20', '2023-03-28', 9, 'I005', 1, 'T', 'P005', 7000000),
('PKG8', 'adventure', 'D005', 'bus', '2023-03-20', '2023-03-28', 9, 'I005', 1, 'T', 'P005', 7000000);

-- --------------------------------------------------------

--
-- Table structure for table `Payment`
--

CREATE TABLE `Payment` (
  `paymentID` varchar(4) NOT NULL,
  `paymentType` varchar(20) NOT NULL,
  `paymentPrice` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Payment`
--

INSERT INTO `Payment` (`paymentID`, `paymentType`, `paymentPrice`) VALUES
('P001', 'Credit Card Permata', 5000000),
('P002', 'Cash', 6000000),
('P003', 'Debit Card OCBC', 7500000),
('P004', 'Bank Transfer BCA', 8000000),
('P005', 'QRIS Mandiri', 7000000),
('P006', 'QRIS BCAA', 8000000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Agent`
--
ALTER TABLE `Agent`
  ADD PRIMARY KEY (`agentID`);

--
-- Indexes for table `Booking`
--
ALTER TABLE `Booking`
  ADD PRIMARY KEY (`bookingID`),
  ADD KEY `agentID` (`agentID`),
  ADD KEY `customerID` (`customerID`),
  ADD KEY `packageID` (`packageID`);

--
-- Indexes for table `Customer`
--
ALTER TABLE `Customer`
  ADD PRIMARY KEY (`customerID`),
  ADD UNIQUE KEY `customerEmail` (`customerEmail`);

--
-- Indexes for table `Destination`
--
ALTER TABLE `Destination`
  ADD PRIMARY KEY (`destinationID`);

--
-- Indexes for table `Itinerary`
--
ALTER TABLE `Itinerary`
  ADD PRIMARY KEY (`itineraryID`,`itineraryDay`);

--
-- Indexes for table `Package`
--
ALTER TABLE `Package`
  ADD PRIMARY KEY (`packageID`),
  ADD UNIQUE KEY `packageID` (`packageID`),
  ADD KEY `destinationID` (`destinationID`),
  ADD KEY `itineraryID` (`itineraryID`,`itineraryDay`),
  ADD KEY `paymentID` (`paymentID`);

--
-- Indexes for table `Payment`
--
ALTER TABLE `Payment`
  ADD PRIMARY KEY (`paymentID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Booking`
--
ALTER TABLE `Booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`agentID`) REFERENCES `Agent` (`agentID`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`customerID`) REFERENCES `Customer` (`customerID`),
  ADD CONSTRAINT `booking_ibfk_3` FOREIGN KEY (`packageID`) REFERENCES `Package` (`packageID`);

--
-- Constraints for table `Package`
--
ALTER TABLE `Package`
  ADD CONSTRAINT `package_ibfk_1` FOREIGN KEY (`destinationID`) REFERENCES `Destination` (`destinationID`),
  ADD CONSTRAINT `package_ibfk_2` FOREIGN KEY (`itineraryID`,`itineraryDay`) REFERENCES `Itinerary` (`itineraryID`, `itineraryDay`),
  ADD CONSTRAINT `package_ibfk_3` FOREIGN KEY (`paymentID`) REFERENCES `Payment` (`paymentID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- show customer table sorted by name alphabetically
SELECT * FROM Customer ORDER BY customerFName ASC, customerLName ASC;
-- show agent names who have been born before 2000
SELECT agentName FROM Agent WHERE agentDOB < '2000-01-01';
SELECT Booking.bookingID, Booking.bookedDate, Customer.customerID, Customer.customerFName, 
Customer.customerLName, Package.packageName, Package.packagePrice
FROM Booking
JOIN Customer ON Booking.customerID = Customer.customerID
JOIN Package ON Booking.packageID = Package.packageID
ORDER BY Booking.bookingID;
