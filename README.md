<div align="center">

# 🚗 DriveEase — Vehicle Rental Management System

### A full-stack PHP + MySQL web application for managing vehicle rentals with Razorpay payments, email notifications, PDF invoices, and a real-time admin analytics dashboard.

[![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?logo=mysql&logoColor=white)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![Razorpay](https://img.shields.io/badge/Razorpay-Payment-02042B?logo=razorpay&logoColor=white)](https://razorpay.com)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)
[![Status](https://img.shields.io/badge/Status-Live-brightgreen)](#)

**[📸 Screenshots](#-screenshots)** &nbsp;|&nbsp;
 **[🚀 Deploy Guide](#-deployment)** &nbsp;|&nbsp;
  **[👤 Author](#-author)**

---

![DriveEase Banner](assets/images/hero-car.jpg)

</div>

---

## 📋 Table of Contents

- [About the Project](#-about-the-project)
- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Screenshots](#-screenshots)
- [Getting Started](#-getting-started-local-setup)
- [Environment Variables](#-environment-variables)
- [Database Setup](#-database-setup)
- [Deployment](#-deployment)
- [Project Structure](#-project-structure)
- [Demo Credentials](#-demo-credentials)
- [Author](#-author)
- [License](#-license)

---

## 🎯 About the Project

**DriveEase** is a complete vehicle rental management system built from scratch using core PHP and MySQL — no heavy frameworks. It features a public-facing booking portal, a full admin control panel, Razorpay payment integration, automated email confirmations via PHPMailer, printable PDF invoices, and a private analytics dashboard with real-time visitor tracking.

> Built as an academic mini project demonstrating full-stack web development skills including database design, payment gateway integration, session management, and responsive UI design.

---

## ✨ Features

### 👤 User Panel
- 🔐 Register / Login with session-based auth (bcrypt password hashing)
- 🚗 Browse vehicles with search, brand filter & price sort
- 📅 Real-time date picker with booked-date blocking (Flatpickr)
- 💳 Instant booking with Razorpay checkout (UPI, cards, netbanking)
- 📄 Downloadable / printable invoice for every booking
- 📋 My Bookings page with status tracking (Pending / Approved / Rejected)
- ⭐ Submit feedback & star ratings
- 👤 Edit profile + change password

### ⚙️ Admin Panel
- 📊 Dashboard with revenue charts (Chart.js), booking status doughnut, 6-month trends
- 👁️ **Private visitor analytics** — total, today, this week, this month + daily chart + top pages
- 🚗 Add / Edit / Delete vehicles with live image preview
- ✅ Approve or Reject bookings with confirmation dialogs
- 👥 Manage users (view + delete with cascade)
- 💬 Manage feedback (view + delete)
- 🔍 Search & filter on all management pages

### 🛠️ Technical
- `.env` based configuration (no hardcoded credentials)
- Bcrypt password hashing with auto-upgrade on first login
- Bot-filtered visitor tracking (ignores crawlers + admin logins)
- Responsive dark cyberpunk UI (custom CSS, no external UI kit)
- Animated aurora backgrounds, glassmorphism cards, neon glow effects
- Print-ready invoice layout

---

## 🔧 Tech Stack

| Layer | Technology |
|---|---|
| **Backend** | PHP 8.0+ (core, no framework) |
| **Database** | MySQL 5.7+ / MariaDB |
| **Frontend** | HTML5, CSS3, Bootstrap 5.3, vanilla JS |
| **Payments** | Razorpay PHP SDK |
| **Email** | PHPMailer 6 via Gmail SMTP |
| **PDF** | FPDF 1.86 |
| **Charts** | Chart.js |
| **Date Picker** | Flatpickr |
| **Icons** | Font Awesome 6 |
| **Server** | Apache (XAMPP locally / InfinityFree / Hostinger / Railway) |

---

## 📸 Screenshots

| Page | Preview |
|---|---|
| Homepage | _Add screenshot_ |
| User Dashboard | _Add screenshot_ |
| Admin Dashboard | _Add screenshot_ |
| Vehicle Booking | _Add screenshot_ |
| Invoice | _Add screenshot_ |

---

## 🚀 Getting Started (Local Setup)

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) (PHP 8.0+ + MySQL + Apache)
- A Gmail account with [App Password](https://myaccount.google.com/apppasswords) enabled

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/avadhutjoshi/vehicle-rental-system.git

# 2. Move to XAMPP's web root
#    Windows: C:\xampp\htdocs\vehicle-rental-system
#    macOS:   /Applications/XAMPP/htdocs/vehicle-rental-system

# 3. Copy environment config
cp .env.example .env
# Edit .env with your DB + email + Razorpay credentials

# 4. Import the database
#    Open http://localhost/phpmyadmin → Create DB: vehicle_rental
#    Import: sql/vehicle_rental.sql

# 5. Start XAMPP → Apache + MySQL

# 6. Visit http://localhost/vehicle-rental-system/
```

---

## 🔐 Environment Variables

Copy `.env.example` to `.env` and fill in your values:

```env
# Database
DB_HOST=localhost
DB_USERNAME=root
DB_PASSWORD=
DB_DATABASE=vehicle_rental

# Razorpay (https://dashboard.razorpay.com/app/keys)
RAZORPAY_KEY_ID=rzp_test_XXXXXXXXXXXX
RAZORPAY_KEY_SECRET=XXXXXXXXXXXXXXXXXXXXXXXX

# Email (Gmail App Password — NOT your regular Gmail password)
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=xxxx xxxx xxxx xxxx
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME=DriveEase
```

> **Never commit `.env` to GitHub.** It is already excluded in `.gitignore`.

> **For better email deliverability:** Use [Brevo](https://brevo.com) free SMTP (`smtp-relay.brevo.com` port 587) instead of Gmail on shared hosting — many hosts block Gmail's SMTP ports.

---

## 🗄️ Database Setup

Import `sql/vehicle_rental.sql` into phpMyAdmin or run:

```bash
mysql -u root -p vehicle_rental < sql/vehicle_rental.sql
```

**Tables:**

| Table | Description |
|---|---|
| `users` | Registered users (admin + customers) |
| `vehicles` | Vehicle listings with price and image |
| `bookings` | All bookings with status + payment info |
| `feedback` | Customer reviews and star ratings |
| `visitors` | Anonymous visitor tracking (admin-only) |

---

## 📁 Project Structure

```
vehicle-rental-system/
├── index.php                    ← Public homepage
├── mail_helper.php              ← HTML email sender (PHPMailer)
├── .env                         ← YOUR credentials (not in git)
├── .env.example                 ← Safe template for others
│
├── admin/
│   ├── dashboard.php            ← Stats, charts + visitor analytics
│   ├── bookings.php             ← Approve/reject bookings
│   ├── manage-vehicles.php      ← Vehicle CRUD
│   ├── add-vehicle.php / edit-vehicle.php
│   ├── users.php / feedbacks.php
│
├── user/
│   ├── dashboard.php / vehicles.php / my-bookings.php
│   ├── payment.php / payment_verify.php / payment_result.php
│   ├── invoice.php / profile.php / feedback.php
│
├── auth/    login.php / register.php / logout.php
├── pages/   about.php / contact.php
├── config/  db.php / razorpay.php
│
├── includes/
│   ├── track_visit.php          ← 👁️ Visitor tracking helper
│   ├── head.php / footer.php / scripts.php
│   └── navbar_admin.php / navbar_user.php / navbar_public.php
│
├── assets/  style.css / main.js / images/
├── sql/     vehicle_rental.sql
├── razorpay/src/ / phpmailer/src/ / fpdf/ / vendor/
```

---

## 👁️ Visitor Tracking (Admin Only)

Private visitor counter built into the database — no third-party service.

**Tracks:** Total · Today · This Week · This Month · Daily chart (7 days) · Top 5 pages

**Privacy:** Bot filtering, admin visits excluded, 30-min deduplication per IP+page, no personal data stored.

**Where to view:** Admin → Dashboard → "Website Visitors" section.

---

## 🔑 Demo Credentials

> For testing purposes only. Change the admin password after deploying your own instance.

| Role | Email | Password |
|---|---|---|
| **Admin** | admin@gmail.com | admin |
| **User** | Register a new account | — |

> The admin password auto-upgrades to bcrypt on first login.

---

## 👤 Author

<div align="center">

**Vasant Joshi**

[![GitHub](https://img.shields.io/badge/GitHub-avadhutjoshi-181717?logo=github)](https://github.com/avadhutjoshi)
[![Email](https://img.shields.io/badge/Email-avadhutjoshi2580@gmail.com-EA4335?logo=gmail)](mailto:avadhutjoshi2580@gmail.com)

</div>

---

## 📄 License

This project is licensed under the **MIT License** — see the [LICENSE](LICENSE) file for details.

---

<div align="center">

**⭐ Star this repo if you found it helpful!**

Made with ❤️ using PHP, MySQL & lots of ☕

</div>
