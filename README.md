# 🚗 Vehicle Rental Management System

A full-stack Vehicle Rental Management System built using **PHP, MySQL, Bootstrap, JavaScript**, and secure backend practices.

This project implements real-world booking lifecycle management including secure authentication, simulated payment gateway integration, automated email notifications, availability management, analytics dashboard, and PDF invoice generation.

---

## 🔥 Key Features

### 🔐 Secure Authentication
- Password hashing using `password_hash()`
- Login verification using `password_verify()`
- SQL Injection protection via Prepared Statements
- Role-based access control (Admin / User)
- Session-based authentication

---

### 🚘 Vehicle Management (Admin)
- Add vehicles with price per day
- View all bookings
- Analytics dashboard
- Revenue tracking
- Booking lifecycle monitoring

---

### 📅 Smart Booking System
- Rental cost auto-calculation (days × price)
- Prevents overlapping bookings
- Disabled booked dates in calendar
- Start date cannot be in past
- End date must be same or after start date
- Real-time availability logic

---

### 💳 Simulated Secure Payment Gateway
- Razorpay-style payment UI
- 80% success / 20% failure simulation
- Backend verification logic
- Auto-approval after successful payment
- Payment status tracking (pending / paid / failed)

> Designed architecturally similar to real payment gateway flow (order → verify → update).

---

### 📧 Automated Email Notifications
- Email sent after successful payment
- Uses PHPMailer with SMTP authentication
- Secure Gmail App Password integration
- Dynamic booking details in email

---

### 🧾 Invoice Generation
- PDF invoice generation
- Downloadable invoice for approved bookings
- Professional invoice structure

---

### 📊 Advanced Admin Dashboard
- Total revenue calculation
- Total bookings
- Approved / Rejected tracking
- Monthly revenue chart (Chart.js)
- Most rented vehicle logic

---

### 🎨 Modern UI Layout
- Global CSS theme
- Gradient navbar
- Reusable layout components
- Professional SaaS-style interface
- Flatpickr calendar integration

---

## 🛠 Tech Stack

| Layer | Technology |
|--------|------------|
| Backend | PHP |
| Database | MySQL |
| Frontend | HTML, CSS, Bootstrap |
| Charts | Chart.js |
| Calendar | Flatpickr |
| Email | PHPMailer |
| Server | XAMPP |
| Payment | Simulated Razorpay Architecture |

---

## 🗂 Project Structure
vehicle-rental-system/
│
├── admin/
├── user/
├── auth/
├── config/
├── includes/
├── assets/
├── phpmailer/
├── invoice.php
├── README.md

---

## 🚀 How To Run Locally

1. Clone the repository: git clone https://github.com/vasantrj/vehicle-rental-system.git

2. Move project into: C:\xampp\htdocs\

3. Start XAMPP:
   - Apache
   - MySQL

4. Import the SQL file into phpMyAdmin

5. Open in browser: http://localhost/vehicle-rental-system

---

## 👤 Admin Access

Register using:
Email: admin@gmail.com
Password: admin

System automatically assigns admin role.

---

## 📌 Future Improvements

- Real Razorpay API integration
- Online deployment
- Payment webhook handling
- Cloud hosting
- Pagination
- REST API version
- MVC architecture refactor

---

## 👨‍💻 Author

**Vasant Joshi**

Full Stack Developer | AIML Enthusiast

