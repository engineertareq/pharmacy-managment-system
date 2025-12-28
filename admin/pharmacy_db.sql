-- 1. Setup Database
DROP DATABASE IF EXISTS pharmacy_db;
CREATE DATABASE pharmacy_db;
USE pharmacy_db;

-- ==========================================
-- A. USER MANAGEMENT
-- ==========================================

-- 2. USERS (Admin, Staff, Clients)
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    role ENUM('admin', 'staff', 'client') NOT NULL DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ==========================================
-- B. INVENTORY & STOCK MANAGEMENT
-- ==========================================

-- 3. CATEGORIES
CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT
);

-- 4. SUPPLIERS (Who you buy medicine from)
CREATE TABLE suppliers (
    supplier_id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(100) NOT NULL,
    contact_person VARCHAR(100),
    phone VARCHAR(20),
    email VARCHAR(100),
    address TEXT
);

-- 5. MEDICINES (Live Inventory Status)
CREATE TABLE medicines (
    medicine_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    generic_name VARCHAR(100),
    sku VARCHAR(50) UNIQUE, -- Barcode / Stock ID
    category_id INT,
    supplier_id INT,
    buy_price DECIMAL(10, 2) NOT NULL, -- Cost
    sell_price DECIMAL(10, 2) NOT NULL, -- MRP
    stock_quantity INT NOT NULL DEFAULT 0, -- Current Stock Level
    batch_number VARCHAR(50),
    expiry_date DATE NOT NULL,
    image_url VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(supplier_id) ON DELETE SET NULL
);

-- 6. PURCHASES (History of Restocking Inventory)
CREATE TABLE purchases (
    purchase_id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_id INT NOT NULL,
    invoice_no VARCHAR(50), -- The Supplier's Invoice Number
    total_amount DECIMAL(10, 2) NOT NULL,
    purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(supplier_id)
);

-- 7. PURCHASE ITEMS (Details of Restock)
CREATE TABLE purchase_items (
    p_item_id INT AUTO_INCREMENT PRIMARY KEY,
    purchase_id INT NOT NULL,
    medicine_id INT NOT NULL,
    quantity INT NOT NULL, -- Quantity Added
    cost_price DECIMAL(10, 2) NOT NULL,
    batch_no VARCHAR(50),
    expiry_date DATE,
    FOREIGN KEY (purchase_id) REFERENCES purchases(purchase_id) ON DELETE CASCADE,
    FOREIGN KEY (medicine_id) REFERENCES medicines(medicine_id)
);

-- ==========================================
-- C. SALES & INVOICING
-- ==========================================

-- 8. ORDERS (This is the Invoice Header)
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_number VARCHAR(50) UNIQUE NOT NULL, -- E.g., INV-2025-001
    client_id INT, -- Null if Walk-in Customer
    staff_id INT,  -- Staff who created the invoice
    sub_total DECIMAL(10, 2) NOT NULL,
    discount DECIMAL(10, 2) DEFAULT 0.00,
    grand_total DECIMAL(10, 2) NOT NULL,
    payment_status ENUM('paid', 'due', 'partial') DEFAULT 'due',
    payment_method ENUM('cash', 'card', 'mobile_banking') DEFAULT 'cash',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES users(user_id) ON DELETE SET NULL,
    FOREIGN KEY (staff_id) REFERENCES users(user_id) ON DELETE SET NULL
);

-- 9. ORDER ITEMS (This is the Invoice Content)
CREATE TABLE order_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    medicine_id INT NOT NULL,
    quantity INT NOT NULL,
    price_per_unit DECIMAL(10, 2) NOT NULL, -- Price at moment of sale
    total_price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (medicine_id) REFERENCES medicines(medicine_id)
);

-- ==========================================
-- D. CLIENT SERVICES
-- ==========================================

-- 10. PRESCRIPTIONS
CREATE TABLE prescriptions (
    prescription_id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    doctor_name VARCHAR(100),
    notes TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- ==========================================
-- E. DUMMY DATA (For Testing)
-- ==========================================

-- Insert Users
INSERT INTO users (full_name, email, password_hash, role) VALUES 
('Super Admin', 'admin@pharma.com', '123456', 'admin'),
('Rahim Staff', 'staff@pharma.com', '123456', 'staff'),
('Karim Client', 'client@pharma.com', '123456', 'client');

-- Insert Supplier
INSERT INTO suppliers (company_name, phone) VALUES ('Square Pharma', '01711111111');

-- Insert Category
INSERT INTO categories (name) VALUES ('Antibiotics'), ('Painkillers');

-- Insert Medicine (Initial Stock)
INSERT INTO medicines (name, sku, category_id, supplier_id, buy_price, sell_price, stock_quantity, expiry_date) 
VALUES ('Napa Extra', 'NAPA01', 2, 1, 1.50, 2.50, 500, '2026-12-31');

-- Insert Purchase (Restock History)
INSERT INTO purchases (supplier_id, invoice_no, total_amount) VALUES (1, 'SUP-INV-999', 150.00);
INSERT INTO purchase_items (purchase_id, medicine_id, quantity, cost_price) VALUES (1, 1, 100, 1.50);

-- Insert Sales Invoice
INSERT INTO orders (invoice_number, client_id, staff_id, sub_total, grand_total, payment_status) 
VALUES ('INV-2025-001', 3, 2, 25.00, 25.00, 'paid');

-- Insert Sales Items
INSERT INTO order_items (order_id, medicine_id, quantity, price_per_unit, total_price) 
VALUES (1, 1, 10, 2.50, 25.00);