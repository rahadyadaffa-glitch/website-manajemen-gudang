# Database Schema
# Warehouse Management System

## Status Dokumen

**Versi Dokumen:** 0.1.0  
**Tanggal Update:** 2026-04-28  
**Status:** Baseline schema referensi implementasi MVP

## Overview

**Database:** MySQL 8.0+  
**Primary Key Strategy:** UUID  
**Character Set:** utf8mb4_unicode_ci  
**Engine:** InnoDB

---

## Schema Guardrails (Sinkron Dengan AI Rule)

- Dokumen ini adalah single source of truth untuk struktur tabel, relasi, dan indeks.
- Setiap perubahan migration/kolom/constraint wajib update dokumen ini di sesi yang sama.
- Setiap perubahan model yang memengaruhi relasi/casts/scopes wajib disinkronkan dengan bagian model pada dokumen ini.
- Tidak mengubah strategi primary key UUID tanpa perubahan eksplisit lintas dokumen (`02-project-context.md`, `03-architecture.md`, `06-ai-rule.md`).
- Penamaan tabel/kolom harus tetap konsisten dengan konvensi Laravel dan relasi yang sudah didefinisikan.

---

## Tables & Relationships

### Relational Diagram

```
roles ──< users >── minimarkets
                ↓
categories ──< products
                ↓
minimarkets ──< inventory_items >── products
                ↓
minimarkets ──< inventory_transactions >── products
                                        >── users
                ↓
audit_logs >── users
```

---

## Table: `roles`

**Purpose:** Master role untuk RBAC (Role-Based Access Control)

| Column       | Type         | Constraint           | Description                |
|--------------|--------------|----------------------|----------------------------|
| id           | BIGINT       | PK, AUTO_INCREMENT   | Role ID                    |
| name         | VARCHAR(50)  | UNIQUE, NOT NULL     | Role name (superadmin, admin, user) |
| display_name | VARCHAR(100) | NOT NULL             | Human-readable name        |
| created_at   | TIMESTAMP    | NULL                 |                            |
| updated_at   | TIMESTAMP    | NULL                 |                            |

**Indexes:**
- PRIMARY KEY (`id`)
- UNIQUE KEY (`name`)

**Seeder Data:**
```php
[
    ['id' => 1, 'name' => 'superadmin', 'display_name' => 'Super Administrator'],
    ['id' => 2, 'name' => 'admin', 'display_name' => 'Admin Minimarket'],
    ['id' => 3, 'name' => 'user', 'display_name' => 'User Gudang'],
]
```

---

## Table: `minimarkets`

**Purpose:** Daftar semua cabang minimarket

| Column       | Type         | Constraint           | Description                |
|--------------|--------------|----------------------|----------------------------|
| id           | CHAR(36)     | PK, UUID             | Minimarket ID              |
| name         | VARCHAR(200) | NOT NULL             | Nama minimarket            |
| code         | VARCHAR(20)  | UNIQUE, NOT NULL     | Kode minimarket (MM001, MM002) |
| address      | TEXT         | NOT NULL             | Alamat lengkap             |
| city         | VARCHAR(100) | NOT NULL             | Kota                       |
| province     | VARCHAR(100) | NOT NULL             | Provinsi                   |
| phone        | VARCHAR(20)  | NULLABLE             | Nomor telepon              |
| status       | ENUM         | NOT NULL, DEFAULT 'active' | active / archived   |
| created_at   | TIMESTAMP    | NULL                 |                            |
| updated_at   | TIMESTAMP    | NULL                 |                            |
| deleted_at   | TIMESTAMP    | NULL                 | Soft delete                |

**Indexes:**
- PRIMARY KEY (`id`)
- UNIQUE KEY (`code`)
- INDEX (`status`)

**Model: `app/Models/Minimarket.php`**
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasUuid;

class Minimarket extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'code',
        'address',
        'city',
        'province',
        'phone',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'string',
        ];
    }

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
```

---

## Table: `users`

**Purpose:** User accounts (Superadmin, Admin, User/Gudang)

| Column         | Type         | Constraint           | Description                |
|----------------|--------------|----------------------|----------------------------|
| id             | CHAR(36)     | PK, UUID             | User ID                    |
| username       | VARCHAR(100) | UNIQUE, NOT NULL     | Username untuk login       |
| email          | VARCHAR(255) | UNIQUE, NOT NULL     | Email                      |
| password       | VARCHAR(255) | NOT NULL             | Hashed password            |
| role_id        | BIGINT       | FK, NOT NULL         | Foreign key ke roles       |
| minimarket_id  | CHAR(36)     | FK, NULLABLE         | NULL untuk superadmin      |
| is_active      | BOOLEAN      | NOT NULL, DEFAULT 1  | Status aktif               |
| email_verified_at | TIMESTAMP | NULL                 | Laravel Breeze             |
| remember_token | VARCHAR(100) | NULL                 | Laravel Breeze             |
| created_at     | TIMESTAMP    | NULL                 |                            |
| updated_at     | TIMESTAMP    | NULL                 |                            |
| deleted_at     | TIMESTAMP    | NULL                 | Soft delete                |

**Indexes:**
- PRIMARY KEY (`id`)
- UNIQUE KEY (`username`)
- UNIQUE KEY (`email`)
- INDEX (`role_id`)
- INDEX (`minimarket_id`)

**Foreign Keys:**
- `role_id` REFERENCES `roles(id)` ON DELETE RESTRICT
- `minimarket_id` REFERENCES `minimarkets(id)` ON DELETE SET NULL

**Model: `app/Models/User.php`** (Modified Breeze)
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasUuid;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuid, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role_id',
        'minimarket_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function minimarket()
    {
        return $this->belongsTo(Minimarket::class);
    }

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    // Accessors
    public function isSuperadmin()
    {
        return $this->role->name === 'superadmin';
    }

    public function isAdmin()
    {
        return $this->role->name === 'admin';
    }

    public function isUser()
    {
        return $this->role->name === 'user';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, $roleName)
    {
        return $query->whereHas('role', function($q) use ($roleName) {
            $q->where('name', $roleName);
        });
    }
}
```

---

## Table: `categories`

**Purpose:** Kategori produk

| Column       | Type         | Constraint           | Description                |
|--------------|--------------|----------------------|----------------------------|
| id           | CHAR(36)     | PK, UUID             | Category ID                |
| name         | VARCHAR(100) | NOT NULL             | Nama kategori              |
| description  | TEXT         | NULLABLE             | Deskripsi kategori         |
| created_at   | TIMESTAMP    | NULL                 |                            |
| updated_at   | TIMESTAMP    | NULL                 |                            |

**Indexes:**
- PRIMARY KEY (`id`)

**Model: `app/Models/Category.php`**
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasUuid;

class Category extends Model
{
    use HasFactory, HasUuid;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'description',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
```

---

## Table: `products`

**Purpose:** Master produk

| Column              | Type          | Constraint           | Description                |
|---------------------|---------------|----------------------|----------------------------|
| id                  | CHAR(36)      | PK, UUID             | Product ID                 |
| category_id         | CHAR(36)      | FK, NOT NULL         | Foreign key ke categories  |
| sku                 | VARCHAR(50)   | UNIQUE, NOT NULL     | Stock Keeping Unit         |
| barcode             | VARCHAR(100)  | UNIQUE, NULLABLE     | Barcode produk             |
| name                | VARCHAR(200)  | NOT NULL             | Nama produk                |
| description         | TEXT          | NULLABLE             | Deskripsi produk           |
| unit                | VARCHAR(20)   | NOT NULL             | Satuan (pcs, box, karton)  |
| min_stock_threshold | INT           | NOT NULL, DEFAULT 10 | Minimum stok warning       |
| image_path          | VARCHAR(255)  | NULLABLE             | Path foto produk           |
| created_at          | TIMESTAMP     | NULL                 |                            |
| updated_at          | TIMESTAMP     | NULL                 |                            |
| deleted_at          | TIMESTAMP     | NULL                 | Soft delete                |

**Indexes:**
- PRIMARY KEY (`id`)
- UNIQUE KEY (`sku`)
- UNIQUE KEY (`barcode`)
- INDEX (`category_id`)

**Foreign Keys:**
- `category_id` REFERENCES `categories(id)` ON DELETE RESTRICT

**Model: `app/Models/Product.php`**
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasUuid;

class Product extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'category_id',
        'sku',
        'barcode',
        'name',
        'description',
        'unit',
        'min_stock_threshold',
        'image_path',
    ];

    protected function casts(): array
    {
        return [
            'min_stock_threshold' => 'integer',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->image_path 
            ? \Storage::url($this->image_path) 
            : asset('images/placeholder-product.png');
    }
}
```

---

## Table: `inventory_items`

**Purpose:** Current stock per minimarket per product

| Column         | Type      | Constraint           | Description                |
|----------------|-----------|----------------------|----------------------------|
| id             | CHAR(36)  | PK, UUID             | Inventory item ID          |
| minimarket_id  | CHAR(36)  | FK, NOT NULL         | Foreign key ke minimarkets |
| product_id     | CHAR(36)  | FK, NOT NULL         | Foreign key ke products    |
| quantity       | INT       | NOT NULL, DEFAULT 0  | Current stock quantity     |
| last_updated   | TIMESTAMP | NOT NULL             | Last update time           |
| created_at     | TIMESTAMP | NULL                 |                            |
| updated_at     | TIMESTAMP | NULL                 |                            |

**Indexes:**
- PRIMARY KEY (`id`)
- UNIQUE KEY (`minimarket_id`, `product_id`)
- INDEX (`minimarket_id`)
- INDEX (`product_id`)

**Foreign Keys:**
- `minimarket_id` REFERENCES `minimarkets(id)` ON DELETE CASCADE
- `product_id` REFERENCES `products(id)` ON DELETE CASCADE

**Model: `app/Models/InventoryItem.php`**
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasUuid;

class InventoryItem extends Model
{
    use HasFactory, HasUuid;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'minimarket_id',
        'product_id',
        'quantity',
        'last_updated',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'last_updated' => 'datetime',
        ];
    }

    public function minimarket()
    {
        return $this->belongsTo(Minimarket::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function isLowStock()
    {
        return $this->quantity < $this->product->min_stock_threshold;
    }
}
```

---

## Table: `inventory_transactions`

**Purpose:** History semua transaksi inventory (in/out/adjustment)

| Column         | Type         | Constraint           | Description                      |
|----------------|--------------|----------------------|----------------------------------|
| id             | CHAR(36)     | PK, UUID             | Transaction ID                   |
| minimarket_id  | CHAR(36)     | FK, NOT NULL         | Foreign key ke minimarkets       |
| product_id     | CHAR(36)     | FK, NOT NULL         | Foreign key ke products          |
| user_id        | CHAR(36)     | FK, NOT NULL         | User yang input                  |
| transaction_type | ENUM       | NOT NULL             | in / out / adjustment            |
| quantity       | INT          | NOT NULL             | Quantity (positive/negative)     |
| status         | ENUM         | NOT NULL             | pending / approved / rejected / completed |
| notes          | TEXT         | NULLABLE             | Catatan                          |
| proof_image_path | VARCHAR(255) | NULLABLE           | Path foto bukti                  |
| approved_by    | CHAR(36)     | FK, NULLABLE         | Admin yang approve               |
| approved_at    | TIMESTAMP    | NULL                 | Waktu approve                    |
| created_at     | TIMESTAMP    | NULL                 |                                  |
| updated_at     | TIMESTAMP    | NULL                 |                                  |

**Indexes:**
- PRIMARY KEY (`id`)
- INDEX (`minimarket_id`)
- INDEX (`product_id`)
- INDEX (`user_id`)
- INDEX (`status`)
- INDEX (`created_at`)

**Foreign Keys:**
- `minimarket_id` REFERENCES `minimarkets(id)` ON DELETE CASCADE
- `product_id` REFERENCES `products(id)` ON DELETE CASCADE
- `user_id` REFERENCES `users(id)` ON DELETE CASCADE
- `approved_by` REFERENCES `users(id)` ON DELETE SET NULL

**Model: `app/Models/InventoryTransaction.php`**
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasUuid;

class InventoryTransaction extends Model
{
    use HasFactory, HasUuid;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'minimarket_id',
        'product_id',
        'user_id',
        'transaction_type',
        'quantity',
        'status',
        'notes',
        'proof_image_path',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'approved_at' => 'datetime',
        ];
    }

    public function minimarket()
    {
        return $this->belongsTo(Minimarket::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getProofImageUrlAttribute()
    {
        return $this->proof_image_path ? \Storage::url($this->proof_image_path) : null;
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
```

---

## Table: `audit_logs`

**Purpose:** Audit trail untuk semua critical actions

| Column         | Type         | Constraint           | Description                |
|----------------|--------------|----------------------|----------------------------|
| id             | CHAR(36)     | PK, UUID             | Audit log ID               |
| user_id        | CHAR(36)     | FK, NULLABLE         | User yang melakukan action |
| action         | VARCHAR(100) | NOT NULL             | Action type (created, updated, deleted, etc) |
| model_type     | VARCHAR(100) | NOT NULL             | Model class name           |
| model_id       | CHAR(36)     | NULLABLE             | Model ID yang di-action    |
| old_values     | JSON         | NULLABLE             | Data sebelum update        |
| new_values     | JSON         | NULLABLE             | Data setelah update        |
| ip_address     | VARCHAR(45)  | NULLABLE             | IP address user            |
| user_agent     | TEXT         | NULLABLE             | Browser user agent         |
| created_at     | TIMESTAMP    | NULL                 |                            |
| updated_at     | TIMESTAMP    | NULL                 |                            |

**Indexes:**
- PRIMARY KEY (`id`)
- INDEX (`user_id`)
- INDEX (`model_type`, `model_id`)
- INDEX (`created_at`)

**Foreign Keys:**
- `user_id` REFERENCES `users(id)` ON DELETE SET NULL

**Model: `app/Models/AuditLog.php`**
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasUuid;

class AuditLog extends Model
{
    use HasFactory, HasUuid;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

---

## Migration Execution Order

```bash
php artisan migrate
```

**Order:**
1. `0001_create_roles_table`
2. `0002_create_minimarkets_table`
3. `0003_create_users_table` (modified Breeze)
4. `0004_create_categories_table`
5. `0005_create_products_table`
6. `0006_create_inventory_items_table`
7. `0007_create_inventory_transactions_table`
8. `0008_create_audit_logs_table`

---

## Seeder Data

**RoleSeeder:**
```php
Role::insert([
    ['id' => 1, 'name' => 'superadmin', 'display_name' => 'Super Administrator'],
    ['id' => 2, 'name' => 'admin', 'display_name' => 'Admin Minimarket'],
    ['id' => 3, 'name' => 'user', 'display_name' => 'User Gudang'],
]);
```

**SuperadminSeeder:**
```php
User::create([
    'username' => 'superadmin',
    'email' => 'superadmin@warehouse.com',
    'password' => Hash::make('password'),
    'role_id' => 1,
    'minimarket_id' => null,
    'is_active' => true,
]);
```

**DemoDataSeeder (Optional):**
- 5 minimarket dummy
- 10 kategori
- 50 produk
- 2 admin (masing-masing 1 minimarket)
- 5 user gudang
- Sample inventory items