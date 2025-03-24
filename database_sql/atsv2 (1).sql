-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2025 at 03:28 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `atsv2`
--

-- --------------------------------------------------------

--
-- Table structure for table `barcode`
--

CREATE TABLE `barcode` (
  `BarcodeID` int(11) NOT NULL,
  `LedgerID` int(11) NOT NULL,
  `BarcodeData` varchar(255) NOT NULL,
  `BarcodeFormat` enum('QR','Code128','EAN-13','UPC','Code39','PDF417','Other') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barcode`
--

INSERT INTO `barcode` (`BarcodeID`, `LedgerID`, `BarcodeData`, `BarcodeFormat`) VALUES
(1, 5, 'LEDGER-5', 'Code128'),
(2, 6, 'LEDGER-6', 'Code128'),
(3, 7, 'LEDGER-7', 'Code128'),
(4, 8, 'LEDGER-8', 'Code128'),
(5, 9, 'LEDGER-9', 'Code128'),
(6, 10, 'LEDGER-10', 'Code128'),
(7, 11, 'LEDGER-11', 'Code128'),
(8, 12, 'LEDGER-12', 'Code128');

-- --------------------------------------------------------

--
-- Table structure for table `buyer`
--

CREATE TABLE `buyer` (
  `buyer_id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buyer`
--

INSERT INTO `buyer` (`buyer_id`, `first_name`, `last_name`, `email`, `phone_number`) VALUES
(1, 'Joseph', 'Madre', 'JoeMama@gmail.com', '8038038031'),
(2, 'Alice', 'Wells', 'alice@example.com', '1112223333'),
(3, 'Bob', 'Carter', 'bobc@gmail.com', '2223334444'),
(4, 'Jared', 'Roberts', 'silly@silly.gov', '1231231234');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`CategoryID`, `CategoryName`) VALUES
(1, 'Desktop Accessories'),
(2, 'Audio Equipment'),
(3, 'Networking Equipment'),
(4, 'Power Supplies');

-- --------------------------------------------------------

--
-- Table structure for table `commonitem`
--

CREATE TABLE `commonitem` (
  `CommonItemID` int(11) NOT NULL,
  `CommonModel` varchar(255) NOT NULL,
  `CommonItDesc` text DEFAULT NULL,
  `CommonDescofCond` varchar(255) DEFAULT NULL,
  `CategoryID` int(11) DEFAULT NULL,
  `manufacturer_id` int(11) DEFAULT NULL,
  `grade_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commonitem`
--

INSERT INTO `commonitem` (`CommonItemID`, `CommonModel`, `CommonItDesc`, `CommonDescofCond`, `CategoryID`, `manufacturer_id`, `grade_id`) VALUES
(1, 'Silly 10022', 'this thing is awesome', 'some scuffs', 1, 1, 1),
(2, 'Unknown', NULL, NULL, NULL, NULL, NULL),
(3, 'NetGear ProSwitch', '24-Port Gigabit Switch', 'Clean, no damage', 3, 1, 1),
(4, 'Tripp Lite UPS', 'Battery backup power unit', 'Minor scratches', 4, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `grade`
--

CREATE TABLE `grade` (
  `grade_id` int(11) NOT NULL,
  `grade_value` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grade`
--

INSERT INTO `grade` (`grade_id`, `grade_value`) VALUES
(1, 'A'),
(2, 'B');

-- --------------------------------------------------------

--
-- Table structure for table `itemledger`
--

CREATE TABLE `itemledger` (
  `LedgerID` int(11) NOT NULL,
  `CommonItemID` int(11) DEFAULT NULL,
  `LaptopID` int(11) DEFAULT NULL,
  `MonitorID` int(11) DEFAULT NULL,
  `DateReceived` date DEFAULT NULL,
  `StatusID` int(11) NOT NULL,
  `SourceID` int(11) NOT NULL,
  `Price` decimal(10,2) DEFAULT NULL
) ;

--
-- Dumping data for table `itemledger`
--

INSERT INTO `itemledger` (`LedgerID`, `CommonItemID`, `LaptopID`, `MonitorID`, `DateReceived`, `StatusID`, `SourceID`, `Price`) VALUES
(2, NULL, 2, NULL, '2025-03-23', 1, 1, 14000.00),
(3, 1, NULL, NULL, '2025-03-23', 2, 1, 1499.50),
(4, NULL, NULL, 1, '2025-03-23', 1, 1, 100.00),
(5, NULL, 3, NULL, '2025-03-23', 1, 1, NULL),
(6, 2, NULL, NULL, '2025-03-22', 1, 1, NULL),
(7, NULL, NULL, 2, '2025-02-22', 2, 1, NULL),
(8, NULL, 4, NULL, '2025-03-24', 1, 3, 899.99),
(9, NULL, NULL, 3, '2025-03-20', 2, 4, 219.50),
(10, 3, NULL, NULL, '2025-03-21', 1, 3, 74.25),
(11, 4, NULL, NULL, '2025-03-22', 2, 4, 115.00),
(12, NULL, 5, NULL, '2025-03-23', 2, 1, 200.00);

-- --------------------------------------------------------

--
-- Table structure for table `laptopitem`
--

CREATE TABLE `laptopitem` (
  `LaptopID` int(11) NOT NULL,
  `Model` varchar(255) NOT NULL,
  `Processor` varchar(255) DEFAULT NULL,
  `RAM` varchar(50) DEFAULT NULL,
  `StorageType` varchar(50) DEFAULT NULL,
  `StorageSize` varchar(50) DEFAULT NULL,
  `GPUType` varchar(50) DEFAULT NULL,
  `GraphicsCard` varchar(255) DEFAULT NULL,
  `OperatingSystem` varchar(255) DEFAULT NULL,
  `ScreenSize` varchar(50) DEFAULT NULL,
  `ScreenResolution` varchar(250) DEFAULT NULL,
  `LaptopDesc` text DEFAULT NULL,
  `LaptopDescofCond` varchar(255) DEFAULT NULL,
  `Warranty` text DEFAULT NULL,
  `WarrantyDate` date DEFAULT NULL,
  `manufacturer_id` int(11) DEFAULT NULL,
  `grade_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laptopitem`
--

INSERT INTO `laptopitem` (`LaptopID`, `Model`, `Processor`, `RAM`, `StorageType`, `StorageSize`, `GPUType`, `GraphicsCard`, `OperatingSystem`, `ScreenSize`, `ScreenResolution`, `LaptopDesc`, `LaptopDescofCond`, `Warranty`, `WarrantyDate`, `manufacturer_id`, `grade_id`) VALUES
(1, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'latitude 5500', 'Intel i9-14000k', '32GB', 'SSD', '1TB', 'discrete', 'Intel UHD', 'Windows 11', '17\"', '4K', 'awesome', 'perfection', '', '2025-04-12', 1, 1),
(3, 'Unknown', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'ThinkPad X1', 'Intel i7-12700H', '16GB', 'SSD', '512GB', 'Integrated', 'Intel Iris Xe', 'Windows 10 Pro', '14\"', '1920x1080', 'Business-class laptop', 'Very clean', '6 months', '2025-08-01', 3, 1),
(5, 'Latitude 1000', 'AMD IDK', '8GB', 'SSD', '256GB', 'discrete', 'AMD Basic', 'Windows 11', '17\"', '4K', 'Its a decent laptop', 'Some light scratches', '', '2025-03-22', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `manufacturer`
--

CREATE TABLE `manufacturer` (
  `manufacturer_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manufacturer`
--

INSERT INTO `manufacturer` (`manufacturer_id`, `name`) VALUES
(5, 'Apple'),
(4, 'ASUS'),
(1, 'Dell'),
(2, 'HP'),
(3, 'Lenovo');

-- --------------------------------------------------------

--
-- Table structure for table `monitoritem`
--

CREATE TABLE `monitoritem` (
  `MonitorID` int(11) NOT NULL,
  `Model` varchar(255) NOT NULL,
  `ScreenSize` varchar(50) DEFAULT NULL,
  `Resolution` varchar(50) DEFAULT NULL,
  `MonitorDesc` text DEFAULT NULL,
  `MonitorDescofCond` varchar(50) DEFAULT NULL,
  `manufacturer_id` int(11) DEFAULT NULL,
  `grade_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monitoritem`
--

INSERT INTO `monitoritem` (`MonitorID`, `Model`, `ScreenSize`, `Resolution`, `MonitorDesc`, `MonitorDescofCond`, `manufacturer_id`, `grade_id`) VALUES
(1, 'clearview 1000', '24\"', '8K', 'HDMI VGA', 'pretty clean', 1, 1),
(2, 'Unknown', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'HP Z24n G2', '24\"', '1920x1200', 'Professional monitor with HDMI and DP', 'Excellent', 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `salesperson`
--

CREATE TABLE `salesperson` (
  `SalesPersonID` int(11) NOT NULL,
  `SalesPersonName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salesperson`
--

INSERT INTO `salesperson` (`SalesPersonID`, `SalesPersonName`) VALUES
(1, 'Logan Simonis'),
(2, 'Maria Gomez'),
(3, 'Ethan Clark');

-- --------------------------------------------------------

--
-- Table structure for table `salesrecord`
--

CREATE TABLE `salesrecord` (
  `SaleID` int(11) NOT NULL,
  `LedgerID` int(11) NOT NULL,
  `SalesPersonID` int(11) DEFAULT NULL,
  `BuyerName` varchar(255) DEFAULT NULL,
  `SaleDate` date DEFAULT NULL,
  `SalePrice` decimal(10,2) DEFAULT NULL,
  `buyer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salesrecord`
--

INSERT INTO `salesrecord` (`SaleID`, `LedgerID`, `SalesPersonID`, `BuyerName`, `SaleDate`, `SalePrice`, `buyer_id`) VALUES
(1, 2, 1, '', '2025-03-23', 14000.00, 1),
(2, 8, 2, '', '2025-03-24', 899.99, 2),
(3, 9, 3, '', '2025-03-24', 219.50, 1),
(4, 12, 1, '', '2025-03-23', 200.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `source`
--

CREATE TABLE `source` (
  `SourceID` int(11) NOT NULL,
  `SourceName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `source`
--

INSERT INTO `source` (`SourceID`, `SourceName`) VALUES
(1, 'Larry'),
(2, 'Long Term Care'),
(3, 'Government Surplus'),
(4, 'IT Asset Recovery'),
(5, 'Richland 2');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `StatusID` int(11) NOT NULL,
  `StatusName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`StatusID`, `StatusName`) VALUES
(1, 'Ready to be processed'),
(2, 'Ready to be listed'),
(3, 'Listed on eBay'),
(4, 'Listed on FB marketplace'),
(5, 'Sold');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `Role` enum('Admin','Manager','User') NOT NULL DEFAULT 'User',
  `Status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Email`, `PasswordHash`, `Role`, `Status`, `CreatedAt`) VALUES
(1, 'joshua', 'jpldoescoins@gmail.com', '$2y$10$f2FADA30/byHyMyQ/MfVteWsDycxVahrAPCLx.Q0Sl1ewxufbUyt6', 'Admin', 'Active', '2025-03-23 22:19:35'),
(2, 'Jeff', 'Jeff@jeff.jeff', '$2y$10$VorD.fB64aoCCUVy/1cTTee8fUVsiYQxZFW0i4HJZceri0Rv9exjG', 'User', 'Active', '2025-03-23 22:28:25'),
(3, 'jared', 'sillt@silly.gov', '$2y$10$z5sK5h/LGT9A7DdI9okZru4857rrNAmFRrt9s6iE1XC4SQnuZDL2i', 'Manager', 'Active', '2025-03-24 02:02:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barcode`
--
ALTER TABLE `barcode`
  ADD PRIMARY KEY (`BarcodeID`),
  ADD KEY `LedgerID` (`LedgerID`);

--
-- Indexes for table `buyer`
--
ALTER TABLE `buyer`
  ADD PRIMARY KEY (`buyer_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `commonitem`
--
ALTER TABLE `commonitem`
  ADD PRIMARY KEY (`CommonItemID`),
  ADD KEY `CategoryID` (`CategoryID`),
  ADD KEY `manufacturer_id` (`manufacturer_id`),
  ADD KEY `grade_id` (`grade_id`);

--
-- Indexes for table `grade`
--
ALTER TABLE `grade`
  ADD PRIMARY KEY (`grade_id`),
  ADD UNIQUE KEY `grade_value` (`grade_value`);

--
-- Indexes for table `itemledger`
--
ALTER TABLE `itemledger`
  ADD PRIMARY KEY (`LedgerID`),
  ADD KEY `CommonItemID` (`CommonItemID`),
  ADD KEY `LaptopID` (`LaptopID`),
  ADD KEY `MonitorID` (`MonitorID`),
  ADD KEY `StatusID` (`StatusID`),
  ADD KEY `SourceID` (`SourceID`);

--
-- Indexes for table `laptopitem`
--
ALTER TABLE `laptopitem`
  ADD PRIMARY KEY (`LaptopID`),
  ADD KEY `manufacturer_id` (`manufacturer_id`),
  ADD KEY `grade_id` (`grade_id`);

--
-- Indexes for table `manufacturer`
--
ALTER TABLE `manufacturer`
  ADD PRIMARY KEY (`manufacturer_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `monitoritem`
--
ALTER TABLE `monitoritem`
  ADD PRIMARY KEY (`MonitorID`),
  ADD KEY `manufacturer_id` (`manufacturer_id`),
  ADD KEY `grade_id` (`grade_id`);

--
-- Indexes for table `salesperson`
--
ALTER TABLE `salesperson`
  ADD PRIMARY KEY (`SalesPersonID`);

--
-- Indexes for table `salesrecord`
--
ALTER TABLE `salesrecord`
  ADD PRIMARY KEY (`SaleID`),
  ADD KEY `LedgerID` (`LedgerID`),
  ADD KEY `SalesPersonID` (`SalesPersonID`),
  ADD KEY `buyer_id` (`buyer_id`);

--
-- Indexes for table `source`
--
ALTER TABLE `source`
  ADD PRIMARY KEY (`SourceID`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`StatusID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barcode`
--
ALTER TABLE `barcode`
  MODIFY `BarcodeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `buyer`
--
ALTER TABLE `buyer`
  MODIFY `buyer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `commonitem`
--
ALTER TABLE `commonitem`
  MODIFY `CommonItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `grade`
--
ALTER TABLE `grade`
  MODIFY `grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `itemledger`
--
ALTER TABLE `itemledger`
  MODIFY `LedgerID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laptopitem`
--
ALTER TABLE `laptopitem`
  MODIFY `LaptopID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `manufacturer`
--
ALTER TABLE `manufacturer`
  MODIFY `manufacturer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `monitoritem`
--
ALTER TABLE `monitoritem`
  MODIFY `MonitorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `salesperson`
--
ALTER TABLE `salesperson`
  MODIFY `SalesPersonID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `salesrecord`
--
ALTER TABLE `salesrecord`
  MODIFY `SaleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `source`
--
ALTER TABLE `source`
  MODIFY `SourceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `StatusID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barcode`
--
ALTER TABLE `barcode`
  ADD CONSTRAINT `barcode_ibfk_1` FOREIGN KEY (`LedgerID`) REFERENCES `itemledger` (`LedgerID`);

--
-- Constraints for table `commonitem`
--
ALTER TABLE `commonitem`
  ADD CONSTRAINT `commonitem_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`CategoryID`),
  ADD CONSTRAINT `commonitem_ibfk_2` FOREIGN KEY (`manufacturer_id`) REFERENCES `manufacturer` (`manufacturer_id`),
  ADD CONSTRAINT `commonitem_ibfk_3` FOREIGN KEY (`grade_id`) REFERENCES `grade` (`grade_id`);

--
-- Constraints for table `itemledger`
--
ALTER TABLE `itemledger`
  ADD CONSTRAINT `itemledger_ibfk_1` FOREIGN KEY (`CommonItemID`) REFERENCES `commonitem` (`CommonItemID`),
  ADD CONSTRAINT `itemledger_ibfk_2` FOREIGN KEY (`LaptopID`) REFERENCES `laptopitem` (`LaptopID`),
  ADD CONSTRAINT `itemledger_ibfk_3` FOREIGN KEY (`MonitorID`) REFERENCES `monitoritem` (`MonitorID`),
  ADD CONSTRAINT `itemledger_ibfk_4` FOREIGN KEY (`StatusID`) REFERENCES `status` (`StatusID`),
  ADD CONSTRAINT `itemledger_ibfk_5` FOREIGN KEY (`SourceID`) REFERENCES `source` (`SourceID`);

--
-- Constraints for table `laptopitem`
--
ALTER TABLE `laptopitem`
  ADD CONSTRAINT `laptopitem_ibfk_1` FOREIGN KEY (`manufacturer_id`) REFERENCES `manufacturer` (`manufacturer_id`),
  ADD CONSTRAINT `laptopitem_ibfk_2` FOREIGN KEY (`grade_id`) REFERENCES `grade` (`grade_id`);

--
-- Constraints for table `monitoritem`
--
ALTER TABLE `monitoritem`
  ADD CONSTRAINT `monitoritem_ibfk_1` FOREIGN KEY (`manufacturer_id`) REFERENCES `manufacturer` (`manufacturer_id`),
  ADD CONSTRAINT `monitoritem_ibfk_2` FOREIGN KEY (`grade_id`) REFERENCES `grade` (`grade_id`);

--
-- Constraints for table `salesrecord`
--
ALTER TABLE `salesrecord`
  ADD CONSTRAINT `salesrecord_ibfk_1` FOREIGN KEY (`LedgerID`) REFERENCES `itemledger` (`LedgerID`),
  ADD CONSTRAINT `salesrecord_ibfk_2` FOREIGN KEY (`SalesPersonID`) REFERENCES `salesperson` (`SalesPersonID`),
  ADD CONSTRAINT `salesrecord_ibfk_3` FOREIGN KEY (`buyer_id`) REFERENCES `buyer` (`buyer_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
