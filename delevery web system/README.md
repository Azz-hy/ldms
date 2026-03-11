# 🚚 LDMS — Local Delivery Management System

A complete **Laravel 11** web application for managing local delivery operations, built for small sellers using social media platforms.

---

## 📦 What's in this ZIP

```
ldms-package/
├── src/                  ← All your custom Laravel code
│   ├── app/
│   │   ├── Http/Controllers/   (4 controllers)
│   │   ├── Http/Middleware/    (RoleMiddleware)
│   │   └── Models/             (User, Seller, Driver, Order)
│   ├── database/
│   │   ├── migrations/         (4 migration files)
│   │   └── seeders/            (DatabaseSeeder with demo data)
│   ├── resources/views/        (18 Blade views)
│   ├── routes/web.php
│   └── bootstrap/app.php
├── setup.sh              ← Auto-installer for Linux/macOS
├── setup.bat             ← Auto-installer for Windows
└── README.md             ← This file
```

---

## 🚀 Setup Instructions

### Prerequisites

Make sure you have installed:
- ✅ **PHP 8.2+** (comes with XAMPP)
- ✅ **Composer** — https://getcomposer.org/download/
- ✅ **MySQL** (via XAMPP/WAMP)
- ✅ **Git** (optional)

---

### Option A — Automatic Setup (Recommended)

**Windows:**
1. Start XAMPP (Apache + MySQL)
2. Create database `ldms` in phpMyAdmin
3. Double-click `setup.bat`
4. Follow the prompts

**Linux/macOS:**
```bash
chmod +x setup.sh
./setup.sh
```

---

### Option B — Manual Setup (Step by Step)

#### Step 1: Create Laravel Project
```bash
composer create-project laravel/laravel ldms
cd ldms
```

#### Step 2: Copy LDMS Files
Copy everything from the `src/` folder into your `ldms/` folder:

| From `src/`                            | To `ldms/`                              |
|----------------------------------------|-----------------------------------------|
| `app/Http/Controllers/*.php`           | `app/Http/Controllers/`                 |
| `app/Http/Middleware/*.php`            | `app/Http/Middleware/`                  |
| `app/Models/*.php`                     | `app/Models/`                           |
| `database/migrations/*.php`            | `database/migrations/`                  |
| `database/seeders/DatabaseSeeder.php`  | `database/seeders/`                     |
| `resources/views/` (all folders)       | `resources/views/`                      |
| `routes/web.php`                       | `routes/web.php`                        |
| `bootstrap/app.php`                    | `bootstrap/app.php`                     |

#### Step 3: Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

#### Step 4: Configure Database
Edit `.env`:
```
DB_DATABASE=ldms
DB_USERNAME=root
DB_PASSWORD=your_password
```

#### Step 5: Create Database
In phpMyAdmin, create a database called `ldms`.

Or via MySQL CLI:
```sql
CREATE DATABASE ldms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### Step 6: Run Migrations & Seed
```bash
php artisan migrate --seed
```

#### Step 7: Start Server
```bash
php artisan serve
```

Open: **http://localhost:8000**

---

## 🔑 Login Credentials

| Role   | Email              | Password   |
|--------|--------------------|------------|
| Admin  | admin@ldms.com     | password   |
| Seller | sara@ldms.com      | password   |
| Seller | karwan@ldms.com    | password   |
| Seller | narin@ldms.com     | password   |
| Driver | ali@ldms.com       | password   |
| Driver | hiwa@ldms.com      | password   |
| Driver | dara@ldms.com      | password   |

---

## ✨ Features Implemented

### 👨‍💼 Admin
- Dashboard with live stats, order charts (Chart.js), revenue trends
- Seller management: Create, Edit, Activate/Deactivate, Delete
- Driver management: Create, Edit, Activate/Deactivate, Delete
- View all orders with filtering (status, seller, search)
- Assign/reassign drivers to orders
- Update order status manually
- Reports: Daily, Monthly Revenue, Seller Performance, Driver Performance

### 🛍️ Seller
- Self-registration with business info
- Dashboard with personal stats and financials
- Create delivery orders (full validation)
- Edit orders (pending status only)
- View real-time order tracking with timeline

### 🚗 Driver
- Dashboard with performance stats
- View active and historical deliveries
- Update delivery status through workflow
- Report failed deliveries with reason
- Add delivery notes

---

## 🔄 Order Status Workflow

```
PENDING → ASSIGNED → PICKED UP → ON THE WAY → DELIVERED ✅
    ↓           ↓           ↓            ↓
  FAILED      FAILED      FAILED       FAILED ❌
```

Rules enforced:
- Cannot skip stages
- Cannot revert finalized orders (Delivered/Failed)
- Only admin assigns drivers

---

## 🛡️ Security Features

- bcrypt password hashing
- CSRF protection on all forms
- Role-based middleware (admin/seller/driver routes isolated)
- Data isolation (sellers see only their own orders)
- Account active/inactive status check on login
- SQL injection prevention via Eloquent ORM

---

## 🗄️ Database Schema

| Table    | Key Columns                                              |
|----------|----------------------------------------------------------|
| users    | id, name, email, password, role, phone, is_active        |
| sellers  | id, user_id (FK), business_name, business_address        |
| drivers  | id, user_id (FK), vehicle_type, vehicle_number           |
| orders   | id, order_number, seller_id, driver_id, customer_name, customer_phone, delivery_address, product_description, delivery_fee, status, ... |

---

## 🔧 Tech Stack

| Layer      | Technology                          |
|------------|-------------------------------------|
| Backend    | Laravel 11 / PHP 8.2+               |
| Database   | MySQL 8.0+                          |
| Frontend   | Blade + Bootstrap 5.3               |
| Charts     | Chart.js 4                          |
| Icons      | Bootstrap Icons 1.11                |
| Fonts      | Plus Jakarta Sans (Google Fonts)    |
| Auth       | Laravel Session Auth                |

---

## 🚨 Troubleshooting

**"Class not found" errors:**
```bash
composer dump-autoload
```

**Migration fails:**
- Check database credentials in `.env`
- Make sure the database exists in MySQL

**500 error on first run:**
```bash
php artisan config:clear
php artisan cache:clear
```

**Storage permissions (Linux/Mac):**
```bash
chmod -R 775 storage bootstrap/cache
```
