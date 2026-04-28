# Project Context
# Warehouse Management System - Technical Details

## Status Dokumen

**Versi Dokumen:** 0.1.0  
**Tanggal Update:** 2026-04-28  
**Status:** Baseline technical context (aktif dipakai saat implementasi)

## Tech Stack

**Framework:** Laravel 11.x  
**PHP Version:** 8.2+  
**Database:** MySQL 8.0+  
**Frontend:** Blade Templates + Tailwind CSS 3.x  
**Authentication:** Laravel Breeze (with role-based middleware)  
**Primary Key Strategy:** UUID (for better security & distributed systems)

---

## Environment Setup

**Local Development:**
- Laravel Herd / Laragon / Docker (pilih salah satu)
- Composer 2.x
- Node.js 18+ & NPM (untuk Tailwind build)
- MySQL 8.0+

**Deploy Target:**
- Shared hosting / VPS with PHP 8.2
- MySQL database
- HTTPS enabled

---

## Laravel 11 Specific Notes

Laravel 11 memiliki beberapa perubahan dari versi sebelumnya:

1. **No more `app/Http/Kernel.php`**
   - Middleware registration di `bootstrap/app.php`
   - Route middleware groups juga di sini

2. **Streamlined directory structure**
   - `app/Providers/RouteServiceProvider.php` tidak ada lagi
   - Routes di `routes/web.php` langsung, tanpa service provider

3. **Model casts simplified**
   - Menggunakan `protected function casts(): array` bukan `$casts`

4. **Breeze Installation**
   ```bash
   composer require laravel/breeze --dev
   php artisan breeze:install blade
   npm install && npm run dev
   ```

---

## Installed Packages

**Core:**
- `laravel/framework: ^11.0`
- `laravel/breeze: ^2.0` (authentication scaffolding)

**UUID:**
- `ramsey/uuid: ^4.7` (built-in Laravel 11)

**Excel Export:**
- `maatwebsite/excel: ^3.1` (for reporting)

**Image Handling:**
- Default: Laravel built-in Storage (`Storage` facade + filesystem `public`)
- Intervention Image tidak diwajibkan untuk MVP
- Jika butuh package tambahan untuk manipulasi gambar, **harus minta izin dulu** (mengacu `06-ai-rule.md`)

**Development:**
- `laravel/pint: ^1.0` (code style)
- `pestphp/pest: ^2.0` (testing, optional)

---

## Directory Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/              # Laravel Breeze auth controllers
│   │   ├── Superadmin/        # Superadmin controllers
│   │   ├── Admin/             # Admin controllers
│   │   ├── User/              # User/Gudang controllers
│   │   └── DashboardController.php
│   ├── Middleware/
│   │   ├── RoleMiddleware.php
│   │   └── CheckMinimarketAccess.php
│   └── Requests/
│       ├── StoreMinimarketRequest.php
│       ├── StoreProductRequest.php
│       └── StoreInventoryTransactionRequest.php
├── Models/
│   ├── User.php
│   ├── Role.php
│   ├── Minimarket.php
│   ├── Product.php
│   ├── Category.php
│   ├── InventoryItem.php
│   ├── InventoryTransaction.php
│   └── AuditLog.php
├── Services/                   # Business logic layer
│   ├── MinimarketService.php
│   ├── InventoryService.php
│   ├── ReportService.php
│   └── AuditService.php
├── Repositories/               # Data access layer
│   ├── MinimarketRepository.php
│   ├── InventoryRepository.php
│   ├── ProductRepository.php
│   └── UserRepository.php
└── Traits/
    ├── HasUuid.php
    └── LogsActivity.php

resources/
├── views/
│   ├── layouts/
│   │   ├── app.blade.php       # Main layout
│   │   ├── navigation.blade.php
│   │   └── sidebar.blade.php
│   ├── components/             # Blade components
│   │   ├── alert.blade.php
│   │   ├── button.blade.php
│   │   └── card.blade.php
│   ├── superadmin/
│   │   ├── dashboard.blade.php
│   │   ├── minimarkets/
│   │   ├── admins/
│   │   └── reports/
│   ├── admin/
│   │   ├── dashboard.blade.php
│   │   ├── products/
│   │   ├── users/
│   │   └── inventory/
│   ├── user/
│   │   ├── dashboard.blade.php
│   │   ├── input-barang.blade.php
│   │   └── history.blade.php
│   └── auth/                   # Breeze auth views
└── css/
    └── app.css                 # Tailwind imports

database/
├── migrations/
│   ├── 0001_create_roles_table.php
│   ├── 0002_create_minimarkets_table.php
│   ├── 0003_create_users_table.php (modified Breeze)
│   ├── 0004_create_categories_table.php
│   ├── 0005_create_products_table.php
│   ├── 0006_create_inventory_items_table.php
│   ├── 0007_create_inventory_transactions_table.php
│   └── 0008_create_audit_logs_table.php
├── seeders/
│   ├── DatabaseSeeder.php
│   ├── RoleSeeder.php
│   ├── SuperadminSeeder.php
│   └── DemoDataSeeder.php (optional)
└── factories/

routes/
├── web.php                     # All routes here (Laravel 11 style)
└── console.php

public/
├── uploads/
│   ├── products/               # Product images
│   └── transactions/           # Transaction proof photos
└── storage -> ../storage/app/public (symlink)

storage/
├── app/
│   └── public/
│       ├── products/
│       └── transactions/
└── logs/
```

---

## Routes Overview

**Public:**
- `GET /` → Landing page atau redirect ke login
- `GET /login` → Login form (Breeze)
- `POST /login` → Handle login
- `POST /logout` → Handle logout

**Superadmin Routes** (prefix: `/superadmin`, middleware: `auth, role:superadmin`)
- `GET /superadmin/dashboard`
- `GET /superadmin/minimarkets`
- `GET /superadmin/minimarkets/create`
- `POST /superadmin/minimarkets`
- `GET /superadmin/minimarkets/{id}/edit`
- `PUT /superadmin/minimarkets/{id}`
- `DELETE /superadmin/minimarkets/{id}`
- `GET /superadmin/admins`
- `GET /superadmin/reports`
- `GET /superadmin/audit-logs`

**Admin Routes** (prefix: `/admin`, middleware: `auth, role:admin`)
- `GET /admin/dashboard`
- `GET /admin/products`
- `GET /admin/products/create`
- `POST /admin/products`
- `GET /admin/users` (manage user/gudang)
- `GET /admin/inventory`
- `GET /admin/reports`

**User/Gudang Routes** (prefix: `/user`, middleware: `auth, role:user`)
- `GET /user/dashboard`
- `GET /user/input-barang-masuk`
- `POST /user/input-barang-masuk`
- `GET /user/input-barang-keluar`
- `POST /user/input-barang-keluar`
- `GET /user/history`

---

## Storage Configuration

**Filesystem Disk:** `public`

**Upload Rules:**
- Product images: max 2MB, jpg/png/webp
- Transaction proof: max 2MB, jpg/png/webp
- Store in: `storage/app/public/products/` dan `storage/app/public/transactions/`
- Access via: `Storage::url('products/filename.jpg')`

**Symlink:**
```bash
php artisan storage:link
```

---

## Authentication & Roles

**Roles:**
- `superadmin` → id: 1
- `admin` → id: 2
- `user` → id: 3

**User Table Columns:**
- `id` (UUID, primary key)
- `username` (unique)
- `email` (unique)
- `password` (hashed)
- `role_id` (foreign key ke roles table)
- `minimarket_id` (nullable, NULL untuk superadmin)
- `is_active` (boolean, default true)
- `created_at`, `updated_at`

**Middleware:**
- `RoleMiddleware` → check user role
- `CheckMinimarketAccess` → pastikan admin/user hanya akses minimarket mereka

---

## Database Connection

**config/database.php:**
```php
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'warehouse_db'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'strict' => true,
    'engine' => 'InnoDB',
],
```

**.env Example:**
```
APP_NAME="Warehouse Management System"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=warehouse_db
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public
```

---

## Key Commands

**Setup:**
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm install && npm run dev
```

**Development:**
```bash
php artisan serve
npm run dev
```

**Testing:**
```bash
php artisan test
```

**Code Style:**
```bash
./vendor/bin/pint
```

---

## Development Guardrails (Wajib Diikuti)

- Jangan install package baru tanpa persetujuan.
- Validasi input wajib lewat Form Request, bukan di controller.
- Multi-step inventory operation wajib memakai DB transaction.
- Business logic ditempatkan di Service, data access di Repository.
- Setiap perubahan schema/arsitektur harus sinkron update docs terkait.

---

## Notes

- Semua Model menggunakan UUID, bukan auto-increment
- Trait `HasUuid` digunakan untuk auto-generate UUID
- Soft deletes untuk Minimarket, Product, User (archive, bukan hard delete)
- Audit log untuk semua critical actions (create/update/delete inventory)
- Image upload menggunakan `Storage::putFileAs()`