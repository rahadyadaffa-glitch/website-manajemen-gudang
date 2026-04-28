# UI/UX Design Guideline
# Warehouse Management System

## Status Dokumen

**Versi Dokumen:** 0.1.0  
**Tanggal Update:** 2026-04-28  
**Status:** Baseline UI guideline untuk MVP (Blade + Tailwind)

## UI Implementation Guardrails

- Komponen UI harus mengikuti aturan arsitektur: view hanya presentasi, tanpa query database.
- Semua form UI harus dipasangkan dengan Form Request validation di backend.
- Jangan menambahkan library UI/package frontend baru tanpa persetujuan.
- Tetap gunakan stack yang sudah ditetapkan (`Blade` + `Tailwind CSS`) untuk MVP.
- Konsistensi aksesibilitas wajib dipertahankan (focus state, label, kontras warna, alt text).

---

## Brand Identity

**Project Name:** Warehouse Management System  
**Target User:**
- Corporate managers (Superadmin)
- Store managers (Admin)
- Warehouse staff (User/Gudang)

**Brand Personality:**
- Professional & trustworthy
- Clean & efficient
- Data-focused, not flashy
- Serious business tool (bukan e-commerce)

**Visual Mood:**
- Corporate blue (trust, stability)
- Clean white workspace
- Subtle accents (tidak terlalu colorful)
- Focus on readability & data clarity

---

## Color Palette

### Primary Colors

**Primary Blue:** `#2563EB` (blue-600)
- Buttons, links, primary actions
- Navigation active state
- Primary badges

**Dark Blue:** `#1E40AF` (blue-700)
- Button hover state
- Headings, emphasis text

### Neutral Colors

**White:** `#FFFFFF`
- Page background
- Card backgrounds
- Input backgrounds

**Gray Scale:**
- `#F9FAFB` (gray-50) - Body background
- `#F3F4F6` (gray-100) - Secondary backgrounds
- `#E5E7EB` (gray-200) - Borders, dividers
- `#9CA3AF` (gray-400) - Disabled text, placeholders
- `#6B7280` (gray-500) - Secondary text
- `#374151` (gray-700) - Primary text
- `#111827` (gray-900) - Headings, emphasis

### Accent Colors

**Success Green:** `#10B981` (green-500)
- Success messages
- Approved status
- Positive metrics

**Warning Yellow:** `#F59E0B` (amber-500)
- Warning alerts
- Low stock warnings
- Pending status

**Danger Red:** `#EF4444` (red-500)
- Error messages
- Delete actions
- Rejected status
- Negative metrics

**Info Blue:** `#3B82F6` (blue-500)
- Info messages
- Neutral badges

---

## Typography

### Font Family

**Primary Font:** `Inter` (sans-serif)
- Clean, modern, professional
- Excellent readability di berbagai ukuran
- Fallback: `system-ui, -apple-system, sans-serif`

**Monospace (optional, untuk kode/SKU):** `JetBrains Mono`
- Untuk display SKU, barcode, UUID
- Fallback: `monospace`

### Font Sizes (Tailwind Scale)

**Headings:**
- H1: `text-3xl` (30px) - Page titles
- H2: `text-2xl` (24px) - Section titles
- H3: `text-xl` (20px) - Subsection titles
- H4: `text-lg` (18px) - Card titles

**Body:**
- Base: `text-base` (16px) - Default text
- Small: `text-sm` (14px) - Table text, labels
- Extra small: `text-xs` (12px) - Captions, helper text

**Font Weights:**
- Regular: `font-normal` (400) - Body text
- Medium: `font-medium` (500) - Labels, table headers
- Semibold: `font-semibold` (600) - Headings
- Bold: `font-bold` (700) - Emphasis (jarang dipakai)

---

## Components

### Buttons

**Primary Button:**
```html
<button class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
    Submit
</button>
```

**Secondary Button:**
```html
<button class="px-4 py-2 bg-white text-gray-700 font-medium border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
    Cancel
</button>
```

**Danger Button:**
```html
<button class="px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
    Delete
</button>
```

**Small Button:**
```html
<button class="px-3 py-1.5 text-sm bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700">
    Action
</button>
```

### Cards

**Standard Card:**
```html
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Card Title</h3>
    <p class="text-gray-600">Card content goes here.</p>
</div>
```

**Stat Card (Dashboard Metrics):**
```html
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500">Total Minimarkets</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">127</p>
        </div>
        <div class="p-3 bg-blue-50 rounded-lg">
            <!-- Icon here -->
            <svg class="w-6 h-6 text-blue-600" />
        </div>
    </div>
    <p class="text-xs text-gray-500 mt-4">+12% from last month</p>
</div>
```

### Forms

**Input Text:**
```html
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">Product Name</label>
    <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter product name">
</div>
```

**Select Dropdown:**
```html
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
    <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        <option>Select category</option>
        <option>Food & Beverage</option>
        <option>Household</option>
    </select>
</div>
```

**Textarea:**
```html
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
    <textarea rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Add notes..."></textarea>
</div>
```

**File Upload:**
```html
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Photo</label>
    <input type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
</div>
```

### Tables

**Standard Table:**
```html
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Product A</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">SKU-001</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">150</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="#" class="text-blue-600 hover:text-blue-900">Edit</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
```

### Badges

**Status Badges:**
```html
<!-- Success -->
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
    Active
</span>

<!-- Warning -->
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
    Pending
</span>

<!-- Danger -->
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
    Rejected
</span>

<!-- Neutral -->
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
    Draft
</span>
```

### Alerts

**Success Alert:**
```html
<div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-400" />
        </div>
        <div class="ml-3">
            <p class="text-sm text-green-700">Successfully saved!</p>
        </div>
    </div>
</div>
```

**Error Alert:**
```html
<div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-400" />
        </div>
        <div class="ml-3">
            <p class="text-sm text-red-700">Error occurred. Please try again.</p>
        </div>
    </div>
</div>
```

### Navigation

**Sidebar Navigation:**
```html
<nav class="flex-1 px-4 py-6 space-y-1">
    <!-- Active item -->
    <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg">
        <svg class="w-5 h-5 mr-3" />
        Dashboard
    </a>
    
    <!-- Inactive item -->
    <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg">
        <svg class="w-5 h-5 mr-3" />
        Products
    </a>
</nav>
```

---

## Layout & Spacing

### Container Widths

**Dashboard Layout:**
- Sidebar: `w-64` (256px)
- Main content: `flex-1` (remaining width)
- Max content width: `max-w-7xl mx-auto` (1280px)

### Spacing Scale (Tailwind)

**Padding:**
- Card padding: `p-6` (24px)
- Button padding: `px-4 py-2` (horizontal 16px, vertical 8px)
- Input padding: `px-4 py-2`

**Margins:**
- Section spacing: `mb-6` atau `mb-8`
- Element spacing: `mb-4`
- Small spacing: `mb-2`

**Gaps (Flexbox/Grid):**
- Card grid: `gap-6`
- Form elements: `gap-4`
- Button groups: `gap-2`

### Border Radius

- Default: `rounded-lg` (8px) - cards, buttons, inputs
- Small: `rounded-md` (6px) - badges
- Full: `rounded-full` - avatars, pills

### Shadows

- Card shadow: `shadow-sm` - subtle, professional
- Dropdown shadow: `shadow-md` - slightly elevated
- Modal shadow: `shadow-xl` - prominent

---

## Page-Specific Layouts

### Dashboard Page (All Roles)

**Structure:**
1. Page header (breadcrumb + title)
2. Stat cards grid (4 columns on desktop)
3. Charts section (2 columns: line chart + pie chart)
4. Recent activity table

**Example:**
```html
<div class="min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <aside class="fixed left-0 top-0 h-full w-64 bg-white border-r border-gray-200">
        <!-- Navigation -->
    </aside>
    
    <!-- Main Content -->
    <div class="ml-64 p-8">
        <!-- Breadcrumb -->
        <nav class="mb-4">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="#" class="hover:text-gray-700">Home</a></li>
                <li>/</li>
                <li class="text-gray-900">Dashboard</li>
            </ol>
        </nav>
        
        <!-- Page Title -->
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Dashboard</h1>
        
        <!-- Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Stat card components -->
        </div>
        
        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Chart components -->
        </div>
        
        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Recent Activity</h2>
            </div>
            <div class="overflow-x-auto">
                <!-- Table -->
            </div>
        </div>
    </div>
</div>
```

### Form Page (Create/Edit)

**Structure:**
1. Page header (breadcrumb + title)
2. Form card
3. Action buttons (Save + Cancel)

**Example:**
```html
<div class="max-w-3xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Add New Product</h1>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form>
            <!-- Form fields -->
            
            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                <button type="button" class="px-4 py-2 bg-white text-gray-700 font-medium border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700">
                    Save Product
                </button>
            </div>
        </form>
    </div>
</div>
```

### Table/List Page

**Structure:**
1. Page header (breadcrumb + title + action button)
2. Filters (search + category dropdown)
3. Table
4. Pagination

---

## Icons

**Icon Library:** Heroicons (Tailwind's recommended)
- Outline style untuk navigation
- Solid style untuk buttons & status

**Common Icons:**
- Dashboard: `HomeIcon`
- Products: `CubeIcon`
- Users: `UsersIcon`
- Inventory: `ArchiveBoxIcon`
- Reports: `ChartBarIcon`
- Settings: `CogIcon`
- Add: `PlusIcon`
- Edit: `PencilIcon`
- Delete: `TrashIcon`
- Search: `MagnifyingGlassIcon`

---

## Responsive Breakpoints

**Tailwind Breakpoints:**
- `sm`: 640px (mobile landscape)
- `md`: 768px (tablet)
- `lg`: 1024px (desktop)
- `xl`: 1280px (large desktop)

**Guidelines:**
- Sidebar: Hidden di mobile (hamburger menu), visible di `lg:`
- Stat cards: 1 column mobile, 2 columns `md:`, 4 columns `lg:`
- Tables: Horizontal scroll di mobile, full width di desktop

---

## What to AVOID (Anti-Patterns)

❌ **DON'T:**
- Jangan pakai gradient backgrounds (terlalu fancy untuk business app)
- Jangan pakai animasi berlebihan (maksimal transition-colors)
- Jangan pakai font decorative atau script (tetap sans-serif)
- Jangan pakai gambar ilustrasi kartun (terlalu playful)
- Jangan pakai color rainbow (stick to palette)
- Jangan pakai shadow besar/dramatis (maksimal shadow-sm)
- Jangan pakai border yang tebal (maksimal border, bukan border-2)
- Jangan pakai rounded-full untuk card/button (maksimal rounded-lg)

✅ **DO:**
- Pakai whitespace generous (jangan cramped)
- Consistent spacing (stick to Tailwind scale)
- High contrast text (gray-900 on white)
- Clear visual hierarchy (size + weight + color)
- Accessible color contrast (WCAG AA minimum)
- Consistent component styling across pages

---

## Accessibility

- All buttons have focus states (`focus:ring-2`)
- Form labels always present
- Color is not the only indicator (use icons + text)
- Alt text untuk semua images
- ARIA labels untuk icon-only buttons

---

## Dark Mode

**Not in scope for MVP.**  
Jika nanti ada request, bisa ditambahkan via Tailwind's dark mode utilities.