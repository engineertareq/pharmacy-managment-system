-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 04, 2026 at 06:45 AM
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
-- Database: `pharmacy_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `description`) VALUES
(4, 'Pain/ Fever', 'Pain relief & fever reducer'),
(5, 'Acid reflux/ Ulcer', 'Reduces stomach acid for heartburn/ulcers.'),
(6, 'Antibiotic', 'Broad-spectrum antibiotic for infections'),
(7, 'Antihistamine', 'Allergy symptoms relief (runny nose, itching).'),
(8, 'Cholesterol', 'Lowers cholesterol to protect heart.'),
(9, 'Anti-parasitic', 'Treats worm/parasite infections.'),
(10, 'Diabetes', 'Oral tablet for type 2 diabetes.'),
(11, 'Blood pressure', 'Helps lower high blood pressure.');

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `medicine_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `generic_name` varchar(100) DEFAULT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `buy_price` decimal(10,2) NOT NULL,
  `sell_price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `batch_number` varchar(50) DEFAULT NULL,
  `expiry_date` date NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`medicine_id`, `name`, `generic_name`, `sku`, `category_id`, `supplier_id`, `buy_price`, `sell_price`, `stock_quantity`, `batch_number`, `expiry_date`, `image_url`, `status`) VALUES
(1, 'Napa Extra', NULL, 'NAPA01', NULL, 1, 1.50, 2.50, 361, NULL, '2026-12-31', NULL, 'active'),
(3, 'Napa 500', 'Paracetamol 500mg', '', 4, 1, 2.00, 3.00, 16, '1', '2027-01-04', 'uploads/6959ded9e71a5_napa-500-mg-tablet-95324450053-i1-jtmMTho0xPmKADM1kMTq.jpg', 'active'),
(9, 'Omeprazole 20mg', 'Omeprazole', '002', 5, 1, 5.00, 7.00, 20, '02', '2027-02-04', 'uploads/6959e083d8657_download (2).jpg', 'active'),
(10, 'Amoxicillin 500mg', 'Amoxicillin', '003', 6, 1, 10.00, 15.00, 20, '5', '2028-02-04', 'uploads/6959e0ebcd8d5_download (3).jpg', 'active'),
(11, 'Fexofenadine 120mg', 'Fexofenadine', '004', 7, 1, 0.00, 0.00, 0, '6', '2027-07-04', 'uploads/6959e14c52ba7_download (4).jpg', 'active'),
(12, 'Amlodipine 5mg', 'Amlodipine', '005', 11, 1, 7.00, 10.00, 30, '8', '2028-02-04', 'uploads/6959e1dd8117e_download (5).jpg', 'active'),
(13, 'Atorvastatin 20mg', 'Atorvastatin', '006', 8, 2, 10.00, 15.00, 30, '9', '2027-07-04', 'uploads/6959e2361f807_download (6).jpg', 'active'),
(14, 'Informet/ Glucomet 500mg', 'Informet/ Glucomet', '007', 10, 1, 10.00, 12.00, 25, '10', '2028-10-04', 'uploads/6959e2a76e4db_download (7).jpg', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `sub_total` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT 0.00,
  `grand_total` decimal(10,2) NOT NULL,
  `payment_status` enum('paid','due','partial') DEFAULT 'due',
  `payment_method` enum('cash','card','mobile_banking') DEFAULT 'cash',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `invoice_number`, `client_id`, `staff_id`, `sub_total`, `discount`, `grand_total`, `payment_status`, `payment_method`, `order_date`) VALUES
(1, 'INV-2025-001', 3, 2, 25.00, 0.00, 25.00, 'paid', 'cash', '2025-12-28 15:46:05'),
(2, 'INV-20251229-690', 11, 1, 20.00, 79.00, -59.00, '', 'cash', '2019-09-30 18:00:00'),
(3, 'INV-20251229-911', 11, 5, 5745.00, 42.00, 5703.00, '', 'cash', '1987-07-23 18:00:00'),
(4, 'INV-20251229-343', 3, 1, 402.50, 79.00, 323.50, '', '', '2025-10-19 18:00:00'),
(5, 'INV-20251229-355', 11, 5, 827.50, 83.00, 744.50, 'paid', 'cash', '1991-02-24 18:00:00'),
(7, 'INV-20251229-659', 3, 5, 730.00, 87.00, 643.00, 'paid', 'card', '1977-09-20 18:00:00'),
(8, 'INV-1767498833', 13, NULL, 30.00, 0.00, 30.00, '', 'cash', '2026-01-04 03:53:53'),
(9, 'INV-1767498950', 14, NULL, 25.00, 0.00, 25.00, 'paid', 'card', '2026-01-04 03:55:50'),
(10, 'INV-1767499138', 15, NULL, 25.00, 0.00, 25.00, 'paid', 'card', '2026-01-04 03:58:58'),
(11, 'INV-1767501224', 13, NULL, 28.00, 0.00, 28.00, 'paid', 'card', '2026-01-04 04:33:44'),
(12, 'INV-1767501547', NULL, NULL, 25.00, 0.00, 25.00, '', 'card', '2026-01-04 04:39:07'),
(13, 'INV-1767503866', 13, NULL, 10.00, 0.00, 10.00, 'paid', 'card', '2026-01-04 05:17:46'),
(14, 'INV-1767504120', 13, NULL, 22.00, 0.00, 22.00, '', 'cash', '2026-01-04 05:22:00'),
(15, 'INV-1767504312', 17, NULL, 25.00, 0.00, 25.00, 'paid', 'mobile_banking', '2026-01-04 05:25:12'),
(16, 'INV-1767504995', 13, NULL, 25.00, 0.00, 25.00, 'paid', 'card', '2026-01-04 05:36:35');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_per_unit` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`item_id`, `order_id`, `medicine_id`, `quantity`, `price_per_unit`, `total_price`) VALUES
(1, 1, 1, 10, 2.50, 25.00),
(2, 1, 1, 1, 2.50, 2.50),
(3, 1, 1, 527, 2.50, 1317.50),
(4, 1, 1, 738, 2.50, 1845.00),
(5, 3, 1, 316, 2.50, 790.00),
(6, 3, 1, 332, 2.50, 830.00),
(7, 3, 1, 365, 2.50, 912.50),
(8, 3, 1, 575, 2.50, 1437.50),
(9, 3, 1, 710, 2.50, 1775.00),
(10, 4, 1, 141, 2.50, 352.50),
(11, 4, 1, 20, 2.50, 50.00),
(12, 5, 1, 331, 2.50, 827.50),
(13, 7, 1, 292, 2.50, 730.00),
(14, 8, 3, 3, 3.00, 9.00),
(15, 8, 9, 3, 7.00, 21.00),
(16, 9, 3, 1, 3.00, 3.00),
(17, 9, 9, 1, 7.00, 7.00),
(18, 9, 10, 1, 15.00, 15.00),
(19, 10, 3, 1, 3.00, 3.00),
(20, 10, 9, 1, 7.00, 7.00),
(21, 10, 10, 1, 15.00, 15.00),
(22, 11, 3, 2, 3.00, 6.00),
(23, 11, 9, 1, 7.00, 7.00),
(24, 11, 10, 1, 15.00, 15.00),
(25, 11, 11, 1, 0.00, 0.00),
(26, 12, 3, 1, 3.00, 3.00),
(27, 12, 9, 1, 7.00, 7.00),
(28, 12, 10, 1, 15.00, 15.00),
(29, 13, 3, 1, 3.00, 3.00),
(30, 13, 9, 1, 7.00, 7.00),
(31, 14, 9, 1, 7.00, 7.00),
(32, 14, 10, 1, 15.00, 15.00),
(33, 15, 3, 1, 3.00, 3.00),
(34, 15, 9, 1, 7.00, 7.00),
(35, 15, 10, 1, 15.00, 15.00),
(36, 16, 3, 1, 3.00, 3.00),
(37, 16, 9, 1, 7.00, 7.00),
(38, 16, 10, 1, 15.00, 15.00);

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `prescription_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `doctor_name` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `purchase_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `invoice_no` varchar(50) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `purchase_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`purchase_id`, `supplier_id`, `invoice_no`, `total_amount`, `purchase_date`) VALUES
(1, 1, 'SUP-INV-999', 150.00, '2025-12-28 15:46:05'),
(2, 1, 'INV-2025-9677', 50000.00, '2025-12-22 18:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_items`
--

CREATE TABLE `purchase_items` (
  `p_item_id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `batch_no` varchar(50) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_items`
--

INSERT INTO `purchase_items` (`p_item_id`, `purchase_id`, `medicine_id`, `quantity`, `cost_price`, `batch_no`, `expiry_date`) VALUES
(3, 1, 1, 200, 20.00, '5419', '2025-12-09'),
(4, 1, 1, 278, 3.00, 'Animi earum iusto e', '1976-03-30'),
(5, 2, 1, 461, 573.00, 'Nostrum voluptatum s', '1994-09-15'),
(6, 1, 1, 645, 352.00, 'Eius laborum magna q', '2021-06-08');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `company_name`, `contact_person`, `phone`, `email`, `address`) VALUES
(1, 'Square Pharma', 'Sachinoor Sachi', '01711111111', 'sachinoorbd@gmail.com', 'House 8,9,10/3, 3rd Floor Free School Street'),
(2, 'Wolf Express Limited', 'Tanjimul Islam Tareq', '01568993772', 'engineertareqbd@gmail.com', '93, Bernaiya, Shahrasti, Chandpur');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('admin','staff','client') NOT NULL DEFAULT 'client',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image_url` varchar(255) DEFAULT 'assets/images/user-grid/user-grid-img14.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password_hash`, `phone`, `address`, `role`, `created_at`, `image_url`) VALUES
(1, 'Tareq', 'admin@pharma.com', '123456', '01855457301', 'Dhanmondi', 'admin', '2025-12-28 15:46:05', 'assets/images/users/1766940875_Tanjimul-Islam-Tareq-3.png'),
(2, 'Rahim Staff', 'staff@pharma.com', '123456', NULL, NULL, 'staff', '2025-12-28 15:46:05', 'assets/images/user-grid/user-grid-img14.png'),
(3, 'Karim Client', 'client@pharma.com', '123456', NULL, NULL, 'client', '2025-12-28 15:46:05', 'assets/images/user-grid/user-grid-img14.png'),
(5, 'Herrod Galloway', 'lutu@mailinator.com', '$2y$10$wwBWv8VkO3HK9yTsB6zMqu0vcw/95XcLkjzPbAc3A8EpsBMb5HSci', '+1 (861) 403-9661', 'Omnis duis consequat', 'admin', '2025-12-28 17:14:53', 'assets/images/user-grid/user-grid-img14.png'),
(11, 'Tanjimul Islam Tareq', 'engineertareqbd@gmail.com', '$2y$10$OtiA1B7oL4JQYKJ7uXuykeM9aQ8fUJ4ubukkwBw/UDXGK9Oj3rHqW', '01568993772', '93, Bernaiya, Shahrasti, Chandpur', 'client', '2025-12-28 17:28:22', 'assets/images/users/695168b62ffe9.png'),
(12, 'Hridoy', 'hr@gmail.com', '$2y$10$z8d3oFCFQcURxd6un25GtOIzNQ7yGYRjglCTBL22QpUGAxMh72S1C', '01888996656', 'dsfr', 'client', '2026-01-04 03:49:59', 'assets/images/user-grid/user-grid-img14.png'),
(13, 'Roni', 'roni@gmail.com', '$2y$10$p8mJv4sqGLneSq4tjzOwqO2d.tz4Puu0LCBVTScOXA/sgVijBzsF6', '01788996623', 'Dhaka', 'client', '2026-01-04 03:52:45', 'assets/images/user-grid/user-grid-img14.png'),
(14, 'Tania', 'tania@gmail.com', '$2y$10$QZ/XacHbsia.2ZIhaHmCt.wx/gi1odig/KEWOjkmjJC.FiHxJBuRm', '01788996623', 'Shirajgong', 'client', '2026-01-04 03:55:01', 'assets/images/user-grid/user-grid-img14.png'),
(15, 'Hridoy1', 'hrio@gmail.com', '$2y$10$M2LzbbN.SXVJXPk5kCLt8Ohn.KAgUGCOPU2WdPLSxXp6RxQY3ou3K', '1931884505', 'sdfgf', 'client', '2026-01-04 03:58:32', 'assets/images/user-grid/user-grid-img14.png'),
(16, 'Tabassum', 'ta@gmail.com', '$2y$10$oLdVgBZSoGLwcNGfvK8GaeDeqxm4I.e.UO0SoWxcMicj/Xw6ek2C.', '01888996656', 'serwer', 'client', '2026-01-04 05:23:33', 'assets/images/user-grid/user-grid-img14.png'),
(17, 'Rahim', 'ra@gmail.com', '$2y$10$Gngj8ZCBe3iBh/VyXGWFCeErjZC6k85jOPwpHnaOjEI1Ydl4zbc.C', '01888996656', 'ertwet', 'client', '2026-01-04 05:24:33', 'assets/images/user-grid/user-grid-img14.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`medicine_id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `medicine_id` (`medicine_id`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`prescription_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`purchase_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD PRIMARY KEY (`p_item_id`),
  ADD KEY `purchase_id` (`purchase_id`),
  ADD KEY `medicine_id` (`medicine_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `medicine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `prescription_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `purchase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `purchase_items`
--
ALTER TABLE `purchase_items`
  MODIFY `p_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `medicines`
--
ALTER TABLE `medicines`
  ADD CONSTRAINT `medicines_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `medicines_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`medicine_id`);

--
-- Constraints for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescriptions_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`);

--
-- Constraints for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD CONSTRAINT `purchase_items_ibfk_1` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`purchase_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_items_ibfk_2` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`medicine_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
