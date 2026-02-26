-- ================================
-- Vehicle Rental Management System
-- Complete Database SQL
-- ================================

CREATE DATABASE IF NOT EXISTS vehicle_rental;
USE vehicle_rental;

-- ----------------
-- Users Table
-- ----------------
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    role ENUM('admin','user') DEFAULT 'user'
);

-- ----------------
-- Vehicles Table
-- ----------------
DROP TABLE IF EXISTS vehicles;
CREATE TABLE vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    brand VARCHAR(100) NOT NULL,
    price_per_day INT NOT NULL,
    image VARCHAR(255),
    status ENUM('available','booked') DEFAULT 'available'
);

-- ----------------
-- Bookings Table
-- ----------------
DROP TABLE IF EXISTS bookings;
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    vehicle_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status ENUM('pending','approved','rejected') DEFAULT 'pending'
);

-- ----------------
-- Optional: Sample Vehicles (for testing UI)
-- ----------------
INSERT INTO vehicles (name, brand, price_per_day, image, status) VALUES
('Swift', 'Maruti', 1200, 'swift.jpg', 'available'),
('Creta', 'Hyundai', 2000, 'creta.jpg', 'available'),
('Royal Enfield', 'RE', 800, 're.jpg', 'available');

-- ----------------
-- Optional: Demo Admin (not required since admin login is hardcoded)
-- ----------------
INSERT INTO users (name, email, password, role) VALUES
('admin', 'admin@gmail.com', 'admin', 'admin');