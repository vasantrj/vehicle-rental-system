-- ============================================
-- DriveEase Vehicle Rental System — Database
-- ============================================

CREATE DATABASE IF NOT EXISTS vehicle_rental;
USE vehicle_rental;

-- USERS
CREATE TABLE IF NOT EXISTS users (
  id       INT AUTO_INCREMENT PRIMARY KEY,
  name     VARCHAR(100) NOT NULL,
  email    VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  phone    VARCHAR(20),
  role     ENUM('admin','user') DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- VEHICLES
CREATE TABLE IF NOT EXISTS vehicles (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  name         VARCHAR(100) NOT NULL,
  brand        VARCHAR(100) NOT NULL,
  price_per_day INT NOT NULL,
  image        VARCHAR(255),
  status       ENUM('available','booked') DEFAULT 'available',
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- BOOKINGS
CREATE TABLE IF NOT EXISTS bookings (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  user_id        INT,
  vehicle_id     INT,
  start_date     DATE,
  end_date       DATE,
  total_price    INT,
  status         ENUM('pending','approved','rejected') DEFAULT 'pending',
  payment_status ENUM('pending','paid','failed') DEFAULT 'pending',
  payment_id     VARCHAR(100),
  payment_method VARCHAR(50),
  created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- FEEDBACK
CREATE TABLE IF NOT EXISTS feedback (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  user_id    INT,
  name       VARCHAR(100),
  email      VARCHAR(100),
  message    TEXT,
  rating     INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- SEED VEHICLES
INSERT INTO vehicles (name,brand,price_per_day,image,status) VALUES
('Swift Dzire','Maruti',1200,'swift.jpg','available'),
('Hyundai Creta','Hyundai',2000,'creta.jpg','available'),
('Royal Enfield Classic 350','RE',800,'re.jpg','available');

-- VISITORS (private analytics — bot-filtered, no personal data)
CREATE TABLE IF NOT EXISTS visitors (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  ip         VARCHAR(45)  NOT NULL,
  page       VARCHAR(255) NOT NULL,
  user_agent VARCHAR(500),
  visited_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_page (page),
  INDEX idx_visited_at (visited_at)
);

-- ADMIN ACCOUNT
-- Demo password: admin  (plain-text seed for local setup)
-- ⚠️  On production: log in → Profile → change your password immediately.
--     New passwords are automatically hashed (bcrypt) via the app.
INSERT INTO users (name,email,password,phone,role) VALUES
('Admin','admin@gmail.com','admin','9999999999','admin');
