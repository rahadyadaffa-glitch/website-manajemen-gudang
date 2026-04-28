# AI Coding Rules
# Warehouse Management System

## General Rules

### CRITICAL: Always Follow These Rules

1. **DO NOT install packages without permission**
   - Stick to packages listed in `02-project-context.md`
   - Jika butuh package baru, ASK FIRST, jangan install langsung

2. **DO NOT modify database schema without updating docs**
   - Setiap perubahan migration harus update `04-database-schema.md`
   - Setiap perubahan model harus update relasi & casts

3. **DO NOT skip validation**
   - Semua form input harus pakai Form Request
   - Jangan validasi di controller

4. **DO NOT hardcode values**
   - Pakai config files atau constants
   - Jangan hardcode status string, role names, etc.

5. **ALWAYS use transactions for multi-step operations**
   - Inventory updates harus wrapped dalam DB::transaction
   - Rollback jika ada error

---

## Laravel 11 Specific Rules

### ⚠️ IMPORTANT: Laravel 11 Differences

**1. No more `app/Http/Kernel.php`**
```php
// ❌ WRONG (Laravel 10 style)
// Modifying app/Http/Kernel.php

// ✅ CORRECT (Laravel 11 style)
// Middleware registration di bootstrap/app.php
return Application::configure(basePath: dirname(__DIR__))
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
```

**2. No more `RouteServiceProvider.php`**
```php
// ❌ WRONG
// Editing app/Providers/RouteServiceProvider.php (file ini tidak ada)

// ✅ CORRECT
// Routes definition langsung di routes/web.php
```

**3. Model Casts Method (bukan property)**
```php
// ❌ WRONG (Laravel 10 style)
class User extends Model
{
    protected $casts = [
        'is_active' => 'boolean',
    ];
}

// ✅ CORRECT (Laravel 11 style)
class User extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
```

**4. Breeze Installation**
```bash
# CORRECT sequence
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run dev
```

---

## Model Rules

### UUID Trait

**ALWAYS use HasUuid trait for all models:**
```php
use App\Traits\HasUuid;

class Minimarket extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;
}
```

### Fillable vs Guarded

**ALWAYS use $fillable (not $guarded):**
```php
// ✅ CORRECT
protected $fillable = [
    'name',
    'code',
    'address',
];

// ❌ WRONG
protected $guarded = []; // Too permissive
```

### Relationships

**ALWAYS define inverse relationships:**
```php
// In Minimarket model
public function users()
{
    return $this->hasMany(User::class);
}

// In User model (inverse)
public function minimarket()
{
    return $this->belongsTo(Minimarket::class);
}
```

### Scopes

**ALWAYS use scopes for reusable queries:**
```php
// ✅ CORRECT
public function scopeActive($query)
{
    return $query->where('status', 'active');
}

// Usage: Minimarket::active()->get();

// ❌ WRONG
// Menulis where('status', 'active') di banyak tempat
```

### Accessors

**Use accessors for computed attributes:**
```php
// ✅ CORRECT
public function getImageUrlAttribute()
{
    return $this->image_path 
        ? Storage::url($this->image_path) 
        : asset('images/placeholder.png');
}

// Usage: $product->image_url

// ❌ WRONG
// Computing URL di view atau controller berulang kali
```

---

## Controller Rules

### Controllers are THIN (Orchestration Only)

**Controllers only:**
- Validate request (via Form Request)
- Call service methods
- Return view or redirect

**Controllers DO NOT:**
- Contain business logic
- Query database directly (use repository)
- Handle file uploads (delegate to service)

**Example:**

```php
// ✅ CORRECT
class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {}

    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->createProduct($request->validated());
        
        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }
}

// ❌ WRONG
class ProductController extends Controller
{
    public function store(Request $request)
    {
        // Validation di controller ❌
        $request->validate([...]);
        
        // Business logic di controller ❌
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
        }
        
        // Direct database query ❌
        $product = Product::create([
            'name' => $request->name,
            'image_path' => $path ?? null,
        ]);
        
        return redirect()->back();
    }
}
```

---

## Service Layer Rules

### Services contain business logic

**Services handle:**
- Complex operations
- Multi-model operations
- File uploads
- External API calls
- Calculations & transformations

**Example:**

```php
// ✅ CORRECT
class InventoryService
{
    public function __construct(
        private InventoryRepository $inventoryRepository,
        private AuditService $auditService
    ) {}

    public function addStock(array $data): InventoryTransaction
    {
        try {
            DB::beginTransaction();
            
            // Create transaction record
            $transaction = $this->inventoryRepository->createTransaction($data);
            
            // Update inventory item
            $this->inventoryRepository->updateStock(
                $data['product_id'], 
                $data['minimarket_id'],
                $data['quantity']
            );
            
            // Log activity
            $this->auditService->logActivity('stock_in', $transaction);
            
            DB::commit();
            return $transaction;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Failed to add stock: " . $e->getMessage());
        }
    }
}
```

### ALWAYS use DB::transaction for multi-step operations

```php
// ✅ CORRECT
DB::beginTransaction();
try {
    // Multiple operations
    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
    throw $e;
}

// ❌ WRONG
// Multiple operations tanpa transaction
```

---

## Repository Pattern Rules

### Repositories handle data access only

**Repository methods:**
- Query builder
- CRUD operations
- Simple filtering

**Repository DO NOT:**
- Contain business logic
- Call other repositories
- Handle transactions

**Example:**

```php
// ✅ CORRECT
class InventoryRepository
{
    public function getByMinimarket(string $minimarketId)
    {
        return InventoryItem::where('minimarket_id', $minimarketId)
            ->with(['product', 'product.category'])
            ->get();
    }
    
    public function updateStock(string $productId, string $minimarketId, int $quantity): void
    {
        InventoryItem::updateOrCreate(
            [
                'product_id' => $productId,
                'minimarket_id' => $minimarketId,
            ],
            [
                'quantity' => DB::raw("quantity + $quantity"),
                'last_updated' => now(),
            ]
        );
    }
}

// ❌ WRONG
class InventoryRepository
{
    public function addStock(array $data) // Business logic ❌
    {
        DB::beginTransaction(); // Transaction di repo ❌
        
        $transaction = InventoryTransaction::create($data);
        $this->updateStock(...); // Complex orchestration ❌
        
        DB::commit();
        return $transaction;
    }
}
```

---

## Form Request Validation

### ALWAYS use Form Request for validation

**Create Form Request:**
```bash
php artisan make:request StoreProductRequest
```

**Example:**
```php
// ✅ CORRECT
class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Or check user permission
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:200',
            'sku' => 'required|string|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'sku.unique' => 'SKU already exists.',
        ];
    }
}
```

**In Controller:**
```php
// ✅ CORRECT
public function store(StoreProductRequest $request)
{
    // Validation already done
    $product = $this->productService->createProduct($request->validated());
}

// ❌ WRONG
public function store(Request $request)
{
    $request->validate([...]); // Validasi di controller ❌
}
```

---

## View/Blade Rules

### Views are presentation only

**Views should:**
- Display data
- Loop through collections
- Show conditional UI

**Views should NOT:**
- Query database (`Product::all()` ❌)
- Contain business logic
- Process data (delegate to controller/service)

**Example:**

```blade
{{-- ✅ CORRECT --}}
@foreach($products as $product)
    <tr>
        <td>{{ $product->name }}</td>
        <td>{{ $product->sku }}</td>
        <td>
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
        </td>
    </tr>
@endforeach

{{-- ❌ WRONG --}}
@foreach(Product::all() as $product) {{-- Query di view ❌ --}}
    <tr>
        <td>{{ $product->name }}</td>
        <td>
            <img src="{{ asset('storage/' . $product->image_path) }}"> {{-- Logic di view ❌ --}}
        </td>
    </tr>
@endforeach
```

### ALWAYS use Storage::url() for uploaded files

```php
// In Model (Accessor)
public function getImageUrlAttribute()
{
    return $this->image_path ? Storage::url($this->image_path) : null;
}

// In Blade
<img src="{{ $product->image_url }}">

// ❌ WRONG
<img src="{{ asset('storage/' . $product->image_path) }}">
```

### Use Blade components for reusable UI

```blade
{{-- ✅ CORRECT --}}
{{-- resources/views/components/alert.blade.php --}}
@props(['type' => 'success', 'message'])

<div class="alert alert-{{ $type }}">
    {{ $message }}
</div>

{{-- Usage --}}
<x-alert type="success" message="Product created!" />

{{-- ❌ WRONG --}}
{{-- Copy-paste alert HTML di banyak file --}}
```

---

## Naming Convention

### Controllers

```php
// ✅ CORRECT
ProductController       // Singular, PascalCase
MinimarketController    // Singular
InventoryInputController // Descriptive

// ❌ WRONG
ProductsController      // Plural ❌
product_controller      // snake_case ❌
```

### Models

```php
// ✅ CORRECT
Product                 // Singular, PascalCase
Minimarket
InventoryTransaction

// ❌ WRONG
Products                // Plural ❌
```

### Tables

```php
// ✅ CORRECT
products                // Plural, snake_case
minimarkets
inventory_transactions

// ❌ WRONG
product                 // Singular ❌
Product                 // PascalCase ❌
```

### Variables

```php
// ✅ CORRECT
$product                // camelCase
$minimarketId
$totalStock

// ❌ WRONG
$Product                // PascalCase ❌
$minimarket_id          // snake_case ❌
```

### Routes

```php
// ✅ CORRECT
Route::get('/products', ...)->name('products.index');
Route::post('/products', ...)->name('products.store');

// ❌ WRONG
Route::get('/product', ...); // Inconsistent singular/plural ❌
Route::post('/create-product', ...); // Tidak RESTful ❌
```

---

## File Upload Strategy

### ALWAYS upload via Service

**In Service:**
```php
// ✅ CORRECT
public function uploadProductImage($file): string
{
    $filename = Str::uuid() . '.' . $file->extension();
    $path = $file->storeAs('products', $filename, 'public');
    return $path;
}
```

**In Controller:**
```php
// ✅ CORRECT
public function store(StoreProductRequest $request)
{
    $data = $request->validated();
    
    if ($request->hasFile('image')) {
        $data['image_path'] = $this->productService->uploadImage($request->file('image'));
    }
    
    $product = $this->productService->createProduct($data);
}

// ❌ WRONG
public function store(Request $request)
{
    // Upload logic di controller ❌
    $path = $request->file('image')->store('products', 'public');
}
```

### File naming

```php
// ✅ CORRECT
$filename = Str::uuid() . '.' . $file->extension();
// Example: 9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d.jpg

// ❌ WRONG
$filename = time() . '_' . $file->getClientOriginalName();
// Predictable, collision-prone, special chars di filename
```

---

## Security & Authorization

### Middleware

**RoleMiddleware:**
```php
// ✅ CORRECT
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
// ✅ CORRECT
public function handle($request, Closure $next)
{
    $user = auth()->user();

    if ($user->isSuperadmin()) {
        return $next($request); // Superadmin bypass
    }

    if (is_null($user->minimarket_id)) {
        abort(403, 'No minimarket assigned.');
    }

    return $next($request);
}
```

### Data Filtering

**ALWAYS filter by minimarket_id for Admin/User:**
```php
// ✅ CORRECT
public function index()
{
    $user = auth()->user();
    
    if ($user->isSuperadmin()) {
        $items = $this->inventoryRepository->all();
    } else {
        $items = $this->inventoryRepository->getByMinimarket($user->minimarket_id);
    }
    
    return view('admin.inventory.index', compact('items'));
}

// ❌ WRONG
public function index()
{
    // Tidak filter by minimarket ❌
    $items = InventoryItem::all(); // Admin bisa lihat semua minimarket ❌
}
```

---

## Testing (Optional but Recommended)

### Feature Tests

```php
// ✅ CORRECT
public function test_admin_can_create_product()
{
    $admin = User::factory()->create(['role_id' => 2]);
    $category = Category::factory()->create();
    
    $this->actingAs($admin)
        ->post('/admin/products', [
            'name' => 'Test Product',
            'sku' => 'TEST-001',
            'category_id' => $category->id,
        ])
        ->assertRedirect('/admin/products')
        ->assertSessionHas('success');
    
    $this->assertDatabaseHas('products', [
        'sku' => 'TEST-001',
    ]);
}
```

---

## What NOT to Do (Anti-Patterns)

❌ **NEVER:**
- Query database di view (`Product::all()`)
- Business logic di controller
- Validation di controller (use Form Request)
- Multiple responsibilities in one class (follow Single Responsibility)
- Install package tanpa izin
- Hardcode values (use config/constants)
- Skip transactions untuk multi-step operations
- Upload files di controller (delegate to service)
- Copy-paste code (extract to service/trait)
- Ignore Laravel 11 conventions

✅ **ALWAYS:**
- Follow Service + Repository pattern
- Use Form Request untuk validation
- Use DB::transaction untuk multi-step
- Use scopes untuk reusable queries
- Use accessors untuk computed attributes
- Filter data by role & minimarket
- Use Storage::url() untuk file access
- Follow naming conventions
- Update documentation saat ada perubahan

---

## Code Review Checklist

Sebelum submit code, check:

- [ ] Controller hanya orchestration (no business logic)
- [ ] Validation pakai Form Request
- [ ] Service method pakai DB::transaction kalau multi-step
- [ ] Repository hanya data access
- [ ] View tidak query database
- [ ] File upload pakai service method
- [ ] Naming convention konsisten
- [ ] Middleware applied correctly (role, minimarket.access)
- [ ] Data filtered by minimarket_id untuk admin/user
- [ ] Documentation updated (jika ada perubahan schema/architecture)