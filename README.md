# 🚗 DriveEase — Vehicle Rental System

A full-stack PHP + MySQL vehicle rental web application with Razorpay payment integration, booking management, admin panel, and email confirmation.

## ✨ Features

- **User Panel** — Browse vehicles, book by date range, Razorpay payment, invoice download
- **Admin Panel** — Manage bookings, vehicles, users, and view revenue analytics
- **Email Confirmation** — Automatic booking confirmation email via Gmail SMTP
- **PDF Invoices** — Generated using FPDF
- **Responsive UI** — Dark-themed custom CSS

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8+ |
| Database | MySQL |
| Payments | Razorpay |
| Email | PHPMailer + Gmail SMTP |
| PDF | FPDF |
| Frontend | Bootstrap 5 + Custom CSS |

## ⚡ Setup (Local)

### 1. Clone the repo
```bash
git clone https://github.com/YOUR_USERNAME/driveease.git
cd driveease
```

### 2. Create your `.env` file
```bash
cp .env.example .env
```
Edit `.env` with your actual credentials (DB, Razorpay, Gmail).

### 3. Import the database
Import `sql/vehicle_rental.sql` into MySQL:
```bash
mysql -u root -p < sql/vehicle_rental.sql
```

### 4. Gmail App Password (for email)
1. Go to [Google Account → Security](https://myaccount.google.com/security)
2. Enable **2-Step Verification**
3. Go to **App Passwords** → Generate one for "Mail"
4. Paste that 16-character password into `.env` as `MAIL_PASSWORD`

### 5. Install dependencies
```bash
composer install
```

### 6. Run locally
Use XAMPP/WAMP — place project in `htdocs/` and visit `http://localhost/driveease/`

## 🔒 Security

- All secrets live in `.env` (never committed to Git)
- `.env.example` shows the required keys without real values
- Passwords and API keys are **never** hardcoded in PHP files

## 🚀 Free Deployment Options

| Platform | Notes |
|---|---|
| [InfinityFree](https://infinityfree.net) | Free PHP + MySQL hosting |
| [000webhost](https://000webhost.com) | Free PHP hosting |
| [Railway.app](https://railway.app) | Free tier, supports PHP via Docker |
| [Render.com](https://render.com) | Free web services |

## 👤 Default Admin Login
After importing SQL:
- **Email:** admin@driveease.in
- **Password:** admin123

## 📁 Project Structure

```
driveease/
├── admin/          # Admin panel pages
├── auth/           # Login / Register / Logout
├── assets/         # CSS, JS, Images
├── config/         # DB & Razorpay config (uses .env)
├── fpdf/           # PDF library
├── includes/       # Shared headers, footers, navbars
├── pages/          # Public pages (About, Contact)
├── phpmailer/      # Email library
├── razorpay/       # Razorpay SDK loader
├── user/           # User panel pages
├── sql/            # Database schema
├── .env.example    # Environment template
└── index.php       # Home page
```

---
Built with ❤️ by Vasant Joshi
