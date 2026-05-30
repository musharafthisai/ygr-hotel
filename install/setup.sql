-- Briyani Shop Manager DB Setup

CREATE DATABASE IF NOT EXISTS briyani_shop;
USE briyani_shop;

-- Branches
CREATE TABLE IF NOT EXISTS branches (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  location VARCHAR(200),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Users
CREATE TABLE IF NOT EXISTS users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  branch_id INT,
  name VARCHAR(100) NOT NULL,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('owner','branch_admin','staff') NOT NULL,
  is_active TINYINT DEFAULT 1,
  allowed_categories VARCHAR(50) DEFAULT 'all',
  totp_secret VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (branch_id) REFERENCES branches(id)
);

-- Daily entries (one row per item per day)
CREATE TABLE IF NOT EXISTS daily_entries (
  id INT PRIMARY KEY AUTO_INCREMENT,
  branch_id INT NOT NULL,
  entry_date DATE NOT NULL,
  item_name VARCHAR(100) NOT NULL,
  quantity DECIMAL(10,2) DEFAULT 0,
  unit_price DECIMAL(10,2) DEFAULT 0,
  amount DECIMAL(10,2) DEFAULT 0,
  payment_mode ENUM('CASH','GPAY') NOT NULL DEFAULT 'CASH',
  entered_by INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (branch_id) REFERENCES branches(id),
  FOREIGN KEY (entered_by) REFERENCES users(id),
  UNIQUE KEY unique_entry (branch_id, entry_date, item_name)
);

-- Daily payment summary (EOD manual Cash/GPay entry)
CREATE TABLE IF NOT EXISTS daily_payments (
  id INT PRIMARY KEY AUTO_INCREMENT,
  branch_id INT NOT NULL,
  entry_date DATE NOT NULL,
  cash_amount DECIMAL(10,2) DEFAULT 0,
  gpay_amount DECIMAL(10,2) DEFAULT 0,
  entered_by INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (branch_id) REFERENCES branches(id),
  FOREIGN KEY (entered_by) REFERENCES users(id),
  UNIQUE KEY unique_daily_payment (branch_id, entry_date)
);

-- Online sales (Swiggy / Zomato)
CREATE TABLE IF NOT EXISTS online_sales (
  id INT PRIMARY KEY AUTO_INCREMENT,
  branch_id INT NOT NULL,
  sale_date DATE NOT NULL,
  platform ENUM('Swiggy','Zomato') NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  entered_by INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (branch_id) REFERENCES branches(id)
);

-- Item rates (editable by owner)
CREATE TABLE IF NOT EXISTS item_rates (
  id INT PRIMARY KEY AUTO_INCREMENT,
  branch_id INT NOT NULL,
  item_name VARCHAR(100) NOT NULL,
  rate DECIMAL(10,2) DEFAULT 0,
  category ENUM('sales','expense') NOT NULL,
  sort_order INT DEFAULT 0,
  UNIQUE KEY unique_rate (branch_id, item_name)
);

-- SEED DATA

-- Default Branch
INSERT INTO branches (id, name, location) VALUES (1, 'Main Branch', 'Karaikudi')
ON DUPLICATE KEY UPDATE name=name;

-- Default Users (passwords are 'admin123' and 'staff123' hashed using bcrypt)
-- using real bcrypt hashes generated from PHP
INSERT INTO users (branch_id, name, username, password, role) VALUES 
(1, 'Owner Admin', 'admin', '$2y$10$LUgO8CWA05gc4QMnfE0vD.oV474wjzOK.qZAU78cSkoHCq8uC.hkG', 'owner'),
(1, 'Staff One', 'staff1', '$2y$10$raf0bvH4wrK6NXCfyhVnZukqxdVsbYGwgt/mDqNcHXjvTx3VhKSYa', 'staff')
ON DUPLICATE KEY UPDATE name=name;

-- Standard valid bcrypt hashes generated via PHP password_hash():
-- 'admin' with password 'admin123' -> $2y$10$LUgO8CWA05gc4QMnfE0vD.oV474wjzOK.qZAU78cSkoHCq8uC.hkG
-- 'staff1' with password 'staff123' -> $2y$10$raf0bvH4wrK6NXCfyhVnZukqxdVsbYGwgt/mDqNcHXjvTx3VhKSYa
UPDATE users SET password = '$2y$10$LUgO8CWA05gc4QMnfE0vD.oV474wjzOK.qZAU78cSkoHCq8uC.hkG' WHERE username = 'admin';
UPDATE users SET password = '$2y$10$raf0bvH4wrK6NXCfyhVnZukqxdVsbYGwgt/mDqNcHXjvTx3VhKSYa' WHERE username = 'staff1';
-- Just in case, we will provide a way to reset password if hash fails.


-- Insert Items
INSERT IGNORE INTO item_rates (branch_id, item_name, rate, category, sort_order) VALUES
(1, 'Chicken Briyani (Day)', 180, 'sales', 1),
(1, 'Chicken Briyani (Eve)', 180, 'sales', 2),
(1, 'Chicken Briyani (Nig)', 180, 'sales', 3),
(1, 'Mutton Briyani', 280, 'sales', 4),
(1, 'Meat', 0, 'expense', 5),
(1, 'Vegetable', 0, 'expense', 6),
(1, 'Stock', 0, 'expense', 7),
(1, 'Cylinder', 0, 'expense', 8),
(1, 'Fish/Prawn', 0, 'expense', 9),
(1, 'Curd', 0, 'expense', 10),
(1, 'Kubbus', 0, 'expense', 11),
(1, 'Gas', 0, 'expense', 12),
(1, 'Beverages', 0, 'expense', 13),
(1, 'Tea / Others', 0, 'expense', 14),
(1, 'Salary', 0, 'expense', 15),
(1, 'RENT / EB', 0, 'expense', 16);

-- Sample Data for today and past days
INSERT IGNORE INTO daily_entries (branch_id, entry_date, item_name, quantity, unit_price, amount, payment_mode, entered_by) VALUES
(1, CURRENT_DATE, 'Chicken Briyani (Day)', 50, 180, 9000, 'CASH', 2),
(1, CURRENT_DATE, 'Mutton Briyani', 20, 280, 5600, 'GPAY', 2),
(1, CURRENT_DATE, 'Meat', 0, 0, 4000, 'CASH', 2),
(1, DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY), 'Chicken Briyani (Day)', 45, 180, 8100, 'CASH', 2),
(1, DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY), 'Meat', 0, 0, 3500, 'CASH', 2);

-- Sample Online Sales
INSERT IGNORE INTO online_sales (branch_id, sale_date, platform, amount, entered_by) VALUES
(1, CURRENT_DATE, 'Swiggy', 2500, 1),
(1, DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY), 'Zomato', 1800, 1);
