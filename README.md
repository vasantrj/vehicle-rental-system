# 🚗 Vehicle Rental Management System 

A full-stack Vehicle Rental Management System built using PHP, MySQL, Bootstrap, and JavaScript.

This project implements real-world backend logic, booking lifecycle management, secure authentication, analytics dashboard, and invoice generation.

---

## 🔥 Key Features

### 🔐 Authentication & Security
- Password hashing using `password_hash()`
- Login verification using `password_verify()`
- SQL Injection protection with Prepared Statements
- Session regeneration after login

### 🚘 Vehicle Management
- Admin can add vehicles with image upload
- Manage and delete vehicles
- Vehicle availability tracking

### 📅 Smart Booking System
- Rental cost calculation (days × price)
- Date conflict prevention (no overlapping bookings)
- Booking approval / rejection system
- Cancel booking option (user side)

### 📊 Advanced Admin Dashboard
- Total Revenue calculation
- Total bookings overview
- Approved vs Rejected tracking
- Most rented vehicle
- Monthly revenue chart (Chart.js)

### 🧾 Invoice Generation
- PDF invoice generation using FPDF
- Downloadable invoice for approved bookings

---

## 🛠 Tech Stack

- **Backend:** PHP (Procedural + Prepared Statements)
- **Database:** MySQL
- **Frontend:** HTML, CSS, Bootstrap 5
- **Charts:** Chart.js
- **PDF:** FPDF
- **Server:** XAMPP (Apache + MySQL)

---

## 📸 Screenshots

### Admin Dashboard
![Admin Dashboard](Screenshots/admin-dashboard.png)

### Vehicles Page
![Vehicles](Screenshots/vehicles.png)

### Invoice PDF
![Invoice](Screenshots/invoice.png)

---

## 🏗 Database Structure

### Users Table
- id
- name
- email (unique)
- password (hashed)
- role (admin/user)

### Vehicles Table
- id
- name
- brand
- price_per_day
- image
- status

### Bookings Table
- id
- user_id
- vehicle_id
- start_date
- end_date
- total_price
- status

---

## 🚀 How to Run Locally

1. Clone repository: git clone https://github.com/vasantrj/vehicle-rental-system.git

2. Move project to: C:\xampp\htdocs\

3. Start XAMPP:
- Apache
- MySQL

4. Import SQL file into phpMyAdmin

5. Open:

http://localhost/vehicle-rental-system

---

## 👤 Admin Credentials

Register with:

Email: admin@gmail.com  
Password: admin  

System automatically assigns admin role.

---

## 📌 Future Improvements

- Payment gateway integration
- Email notifications
- Booking history filtering
- Full MVC refactor
- Deployment on cloud

---

## 🎯 Project Level

✔ Placement Ready  
✔ Industry Concepts Applied  
✔ Secure Authentication  
✔ Advanced Backend Logic  
✔ Professional Analytics  

---

## 👨‍💻 Author

**Vasant Joshi**