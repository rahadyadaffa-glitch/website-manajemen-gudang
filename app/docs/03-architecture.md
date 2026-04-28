# Architecture & Technical Design
# Warehouse Management System

## Status Dokumen

**Versi Dokumen:** 0.1.0  
**Tanggal Update:** 2026-04-28  
**Status:** Baseline arsitektur implementasi MVP

## Design Pattern

**Pattern:** Service Layer + Repository Pattern

**Why:**
- **Service Layer:** Business logic terpisah dari controller (fat models, thin controllers)
- **Repository:** Data access abstraction, mudah testing & swap implementation
- **Benefit:** Code reusability, easier testing, cleaner controllers

**Flow:**
```
Request → Controller → Service → Repository → Model → Database
                ↓
Response ← Controller ← Service ← Repository ← Model ← Database
```

---

## Architecture Guardrails (Acuan Implementasi)

Guardrails ini wajib konsisten dengan `06-ai-rule.md`:

- Controller hanya orchestration (request in, service call, response out)
- Validasi input via Form Request, bukan di controller
- Business logic hanya di Service layer
- Repository hanya data access (query/CRUD), tanpa business orchestration
- Multi-step operations wajib dibungkus DB transaction di Service
- View/Blade hanya presentasi, tidak melakukan query database
- Akses data Admin/User wajib terfilter by `minimarket_id`

---

## Folder Structure Detail

### Controllers
**Responsibility:** Orchestration only, tidak ada business logic

```
app/Http/Controllers/
├── Auth/                        # Laravel Breeze controllers
├── Superadmin/
│   ├── DashboardController.php
│   ├── MinimarketController.php
│   ├── AdminController.php
│   ├── ReportController.php
│   └── AuditLogController.php
├── Admin/
│   ├── DashboardController.php
│   ├── ProductController.php
│   ├── UserController.php       # Manage user/gudang
│   ├── InventoryController.php
│   └── ReportController.php
└── User/
    ├── DashboardController.php
    ├── InventoryInputController.php
    └── HistoryController.php
```

### Services
**Responsibility:** Business logic, validation, coordination antar repository

```
app/Services/
├── MinimarketService.php
│   - createMinimarket()
│   - updateMinimarket()
│   - deleteMinimarket()
│   - getMinimarketWithStats()
│
├── InventoryService.php
│   - addStock()
│   - reduceStock()
│   - adjustStock()
│   - getStockByMinimarket()
│   - getLowStockItems()
│
├── ProductService.php
│   - createProduct()
│   - updateProduct()
│   - uploadProductImage()
│
├── ReportService.php
│   - generateInventoryReport()
│   - generateComparisonReport()
│   - exportToExcel()
│
└── AuditService.php
    - logActivity()
    - getAuditTrail()
```

### Repositories
**Responsibility:** Data access, query builder, database operations

```
app/Repositories/
├── MinimarketRepository.php
│   - all()
│   - find($id)
│   - create(array $data)
│   - update($id, array $data)
│   - delete($id)
│   - getWithLowStock()
│
├── InventoryRepository.php
│   - getByMinimarket($minimarketId)
│   - getByProduct($productId)
│   - getTotalStock()
│   - getLowStockItems($minimarketId)
│
├── ProductRepository.php
│   - all()
│   - findBySKU($sku)
│   - getByCategory($categoryId)
│
└── UserRepository.php
    - getByMinimarket($minimarketId)
    - getByRole($roleId)
```

---

## Data Flow Diagram

### Flow 1: User Input Barang Masuk

```
1. User submit form input barang masuk
   ↓
2. InventoryInputController@store
   ↓
3. Validation via StoreInventoryTransactionRequest
   ↓
4. InventoryService->addStock($data)
   ↓
5. Service melakukan:
   - InventoryRepository->findProduct($sku)
   - InventoryRepository->createTransaction($data)
   - InventoryRepository->updateStock($productId, $quantity)
   - AuditService->logActivity('stock_in', $data)
   ↓
6. Return success response
   ↓
7. Redirect ke history page dengan flash message
```

### Flow 2: Superadmin Lihat Dashboard

```
1. Superadmin access /superadmin/dashboard
   ↓
2. DashboardController@index (Superadmin)
   ↓
3. Call MinimarketService->getAllWithStats()
   ↓
4. Service call:
   - MinimarketRepository->all()
   - InventoryRepository->getTotalStockAllStores()
   - InventoryRepository->getCriticalStockStores()
   - InventoryRepository->getRecentTransactions(limit: 10)
   ↓
5. Service aggregate data
   ↓
6. Return to view with data
   ↓
7. Render superadmin.dashboard view
```

### Flow 3: Admin Approve Stock Adjustment

```
1. Admin klik "Approve" di pending adjustment
   ↓
2. InventoryController@approveAdjustment (Admin)
   ↓
3. Middleware CheckMinimarketAccess → pastikan adjustment belongs to admin's minimarket
   ↓
4. InventoryService->approveAdjustment($transactionId, $adminId)
   ↓
5. Service:
   - InventoryRepository->findTransaction($transactionId)
   - Check status == 'pending'
   - Update status → 'approved'
   - Update approved_by → $adminId
   - Update approved_at → now()
   - AuditService->logActivity('adjustment_approved', $data)
   ↓
6. Return success
   ↓
7. Redirect back dengan flash message
```

---

## Route Structure

**Middleware Groups:**
```php
// bootstrap/app.php

->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
        'minimarket.access' => \App\Http\Middleware\CheckMinimarketAccess::class,
    ]);
})
```

**Route Definition (routes/web.php):**
```php
// Public
Route::get('/', function () {
    return redirect('/login');
});

// Auth routes (Breeze)
require __DIR__.'/auth.php';

// Superadmin routes
Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperadminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('minimarkets', MinimarketController::class);
    Route::resource('admins', AdminController::class);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit.index');
});

// Admin routes
Route::middleware(['auth', 'role:admin', 'minimarket.access'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', ProductController::class);
    Route::resource('users', UserController::class);
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory/approve/{id}', [InventoryController::class, 'approve'])->name('inventory.approve');
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
});

// User/Gudang routes
Route::middleware(['auth', 'role:user', 'minimarket.access'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/input-barang-masuk', [InventoryInputController::class, 'create'])->name('input.masuk.create');
    Route::post('/input-barang-masuk', [InventoryInputController::class, 'store'])->name('input.masuk.store');
    Route::get('/input-barang-keluar', [InventoryInputController::class, 'createKeluar'])->name('input.keluar.create');
    Route::post('/input-barang-keluar', [InventoryInputController::class, 'storeKeluar'])->name('input.keluar.store');
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
});
```

---

## Primary Key Convention

**All tables use UUID as primary key.**

**Trait Implementation:**
```php
// app/Traits/HasUuid.php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    protected static function bootHasUuid()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }
}
```

**Usage in Models:**
```php
use App\Traits\HasUuid;

class Minimarket extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;
}
```

---

## Status & Workflow

### Inventory Transaction Status

```
pending → approved → completed
   ↓
rejected
```

**Status Values:**
- `pending`: Waiting admin approval (untuk adjustment)
- `approved`: Approved by admin
- `rejected`: Rejected by admin
- `completed`: Direct transactions (barang masuk/keluar tanpa approval)

**Workflow:**
1. User input barang masuk/keluar → status: `completed` (langsung masuk)
2. User input stock adjustment → status: `pending`
3. Admin approve → status: `approved`
4. Admin reject → status: `rejected`

### User Status

```
active ←→ inactive
```

**Values:**
- `active`: User bisa login
- `inactive`: User tidak bisa login (soft ban)

### Minimarket Status

```
active ←→ archived
```

**Values:**
- `active`: Minimarket aktif
- `archived`: Minimarket tidak aktif (soft delete)

---

## Upload & Storage Strategy

### Product Images

**Path:** `storage/app/public/products/{uuid}.{ext}`  
**Access:** `Storage::url('products/{uuid}.jpg')`  
**Max Size:** 2MB  
**Format:** JPG, PNG, WEBP

**Upload Flow:**
```php
// In ProductService
public function uploadImage($file)
{
    $filename = Str::uuid() . '.' . $file->extension();
    $path = $file->storeAs('products', $filename, 'public');
    return $path;
}
```

### Transaction Proof Photos

**Path:** `storage/app/public/transactions/{uuid}.{ext}`  
**Access:** `Storage::url('transactions/{uuid}.jpg')`  
**Max Size:** 2MB  
**Format:** JPG, PNG, WEBP

**Upload Flow:**
```php
// In InventoryService
public function uploadProof($file)
{
    $filename = Str::uuid() . '.' . $file->extension();
    $path = $file->storeAs('transactions', $filename, 'public');
    return $path;
}
```

---

## Query Optimization

### Eager Loading

Always eager load relationships untuk avoid N+1 problem:

```php
// Good
$minimarkets = Minimarket::with(['inventoryItems', 'users'])->get();

// Bad
$minimarkets = Minimarket::all(); // N+1 problem jika loop $minimarket->users
```

### Indexing Strategy

**Indexed Columns:**
- `users.username` (unique index)
- `users.email` (unique index)
- `users.role_id` (index)
- `users.minimarket_id` (index)
- `products.sku` (unique index)
- `products.barcode` (unique index)
- `inventory_items.minimarket_id` (index)
- `inventory_items.product_id` (index)
- `inventory_transactions.minimarket_id` (index)
- `inventory_transactions.status` (index)
- `audit_logs.user_id` (index)
- `audit_logs.created_at` (index)

---

## Security Considerations

### Role-Based Access Control

**RoleMiddleware:**
```php
public function handle($request, Closure $next, $role)
{
    if (!auth()->check()) {
        return redirect('/login');
    }

    if (auth()->user()->role->name !== $role) {
        abort(403, 'Unauthorized action.');
    }

    return $next($request);
}
```

**CheckMinimarketAccess:**
```php
public function handle($request, Closure $next)
{
    $user = auth()->user();

    // Superadmin bypass
    if ($user->role->name === 'superadmin') {
        return $next($request);
    }

    // Admin/User harus punya minimarket_id
    if (is_null($user->minimarket_id)) {
        abort(403, 'No minimarket assigned.');
    }

    return $next($request);
}
```

### Data Filtering by Minimarket

**In Repository:**
```php
public function getByMinimarket($minimarketId)
{
    return InventoryItem::where('minimarket_id', $minimarketId)->get();
}
```

**In Controller:**
```php
public function index()
{
    $user = auth()->user();
    
    if ($user->role->name === 'superadmin') {
        // Superadmin lihat semua
        $items = $this->inventoryRepository->all();
    } else {
        // Admin/User lihat minimarket mereka saja
        $items = $this->inventoryRepository->getByMinimarket($user->minimarket_id);
    }
    
    return view('admin.inventory.index', compact('items'));
}
```

---

## Error Handling

**Global Exception Handler (bootstrap/app.php):**
```php
->withExceptions(function (Exceptions $exceptions) {
    $exceptions->render(function (Throwable $e, Request $request) {
        if ($request->is('api/*')) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    });
})
```

**Service Level:**
```php
public function addStock(array $data)
{
    try {
        DB::beginTransaction();
        
        // Business logic here
        $transaction = $this->inventoryRepository->createTransaction($data);
        $this->inventoryRepository->updateStock($data['product_id'], $data['quantity']);
        $this->auditService->logActivity('stock_in', $data);
        
        DB::commit();
        return $transaction;
        
    } catch (\Exception $e) {
        DB::rollBack();
        throw new \Exception("Failed to add stock: " . $e->getMessage());
    }
}
```

---

## Testing Strategy

**Unit Tests:** Service & Repository methods  
**Feature Tests:** Controller endpoints, auth flow  
**Browser Tests:** Critical user journeys (optional, using Dusk)

**Example:**
```php
// tests/Feature/InventoryTest.php

public function test_user_can_add_stock()
{
    $user = User::factory()->create(['role_id' => 3]); // user role
    $product = Product::factory()->create();
    
    $this->actingAs($user)
        ->post('/user/input-barang-masuk', [
            'product_id' => $product->id,
            'quantity' => 50,
            'notes' => 'Test input'
        ])
        ->assertRedirect('/user/history')
        ->assertSessionHas('success');
}
```