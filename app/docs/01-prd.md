# Product Requirements Document (PRD)
# Warehouse Management System - Multi Minimarket

## Status Dokumen

**Versi Dokumen:** 0.1.0  
**Tanggal Update:** 2026-04-28  
**Status:** Draft aktif untuk baseline MVP  
**Catatan:** Checklist `[x]` pada dokumen ini merepresentasikan **fitur yang masuk scope/ditargetkan**, bukan konfirmasi implementasi final di codebase.

## Informasi Project

**Nama Project:** Warehouse Management System (WMS)  
**Target User:** 
- Superadmin: Corporate/Head Office yang monitor semua minimarket
- Admin: Manager/PIC per minimarket
- User/Gudang: Staff gudang yang input barang

**Konsep:**
Sistem manajemen gudang untuk jaringan minimarket (seperti Alfamart/Indomaret) yang bisa scale ke ribuan cabang. Superadmin bisa monitoring semua minimarket, Admin kelola satu minimarket, User/Gudang input stok.

**Tujuan Bisnis:**
- Centralized monitoring inventory semua cabang
- Real-time stock visibility
- Audit trail lengkap untuk compliance
- Scalable untuk ribuan minimarket

---

## Fitur yang Masuk Scope

### A. Authentication & Authorization

- [x] Login dengan role-based access (Superadmin, Admin, User)
- [x] Password reset via email
- [x] Session management
- [x] Role-based dashboard redirect setelah login

### B. Superadmin Features

- [x] Dashboard global: overview semua minimarket
  - Total stok across all stores
  - Minimarket dengan stok kritis
  - Grafik trend inventory per region/store
  - Recent activities dari semua minimarket
- [x] Kelola minimarket (CRUD)
  - Tambah minimarket baru
  - Edit info minimarket (nama, alamat, phone, region)
  - Soft delete minimarket (archive, bukan hard delete)
  - Lihat daftar semua minimarket
- [x] Kelola Admin
  - Assign admin ke minimarket tertentu
  - Create/edit/deactivate admin account
  - View admin activity log
- [x] Laporan Konsolidasi
  - Inventory comparison antar minimarket
  - Top products (most stocked, fastest moving)
  - Minimarket performance metrics
  - Export ke Excel/CSV
- [x] Audit Trail
  - View all transactions (semua minimarket)
  - Filter by date, minimarket, user, action type
  - Detect anomaly (negative stock, unusual transfers)

### C. Admin Features (Per Minimarket)

- [x] Dashboard minimarket
  - Stok overview (total items, low stock alerts)
  - Recent transactions
  - Top products di minimarket ini
- [x] Kelola User/Gudang
  - Create/edit/deactivate user gudang
  - View user activity di minimarket ini
- [x] Inventory Management
  - Lihat semua barang di minimarket ini
  - Approve/reject stock adjustments
  - Manual stock adjustment dengan alasan
- [x] Laporan Minimarket
  - Stock report (current, by category)
  - Transaction history
  - Export to Excel/CSV

### D. User/Gudang Features

- [x] Input Barang Masuk
  - Scan/input barcode produk
  - Input quantity
  - Upload foto bukti (optional)
  - Add notes
- [x] Input Barang Keluar
  - Retur supplier
  - Barang rusak/expired
  - Stock opname adjustment
- [x] View Stok Real-time
  - Search produk
  - Filter by category
  - Lihat current stock quantity
- [x] History Transaksi User
  - Lihat transaksi yang user input sendiri
  - Filter by date

### E. Product Management

- [x] Master Produk (dikelola Admin/Superadmin)
  - CRUD produk (nama, SKU, barcode, kategori, satuan)
  - Upload foto produk
  - Set minimum stock threshold
- [x] Kategori Produk
  - CRUD kategori
  - Assign produk ke kategori

### F. Reporting & Analytics

- [x] Stock Movement Report
  - In/out/adjustment per periode
  - Filter by product, category, date range
- [x] Low Stock Alert
  - Auto-detect produk yang stok < minimum threshold
  - Email notification ke admin
- [x] Activity Log Report
  - Who did what, when
  - Export audit trail

---

## Fitur yang TIDAK Masuk Scope

❌ Point of Sale (POS) / Kasir
❌ E-commerce / Customer ordering
❌ Financial/accounting integration
❌ Payroll / HR management
❌ Supplier management & purchase orders (phase 2)
❌ Transfer antar minimarket (phase 2)
❌ Barcode scanning via mobile app (phase 2)
❌ Integration dengan ERP external
❌ Multi-language support
❌ Multi-currency

---

## User Journey

### Journey 1: Superadmin Monitoring Semua Minimarket

1. Login sebagai Superadmin
2. Redirect ke dashboard global
3. Lihat overview: total minimarket, total stok, critical alerts
4. Klik "View All Minimarkets" → lihat tabel semua minimarket
5. Klik salah satu minimarket → lihat detail inventory minimarket itu
6. Klik "Reports" → pilih "Inventory Comparison"
7. Filter by region/date → generate laporan
8. Export ke Excel

### Journey 2: Admin Kelola Minimarket

1. Login sebagai Admin (tied to Minimarket ID 5)
2. Redirect ke dashboard minimarket
3. Lihat low stock alerts (3 produk stok menipis)
4. Klik alert → lihat detail produk
5. Klik "Manage Users" → lihat daftar user gudang
6. Klik "Add User" → create user gudang baru
7. Assign username, password, set active
8. User gudang bisa login dan input barang

### Journey 3: User/Gudang Input Barang Masuk

1. Login sebagai User/Gudang
2. Redirect ke halaman Input Barang
3. Klik "Input Barang Masuk"
4. Scan barcode / input manual SKU
5. Sistem auto-populate nama produk, kategori
6. Input quantity: 50 pcs
7. Upload foto (optional)
8. Add notes: "Supplier X, Invoice INV-12345"
9. Submit
10. Transaksi masuk ke history, stok bertambah 50

### Journey 4: Admin Approve Stock Adjustment

1. User/Gudang submit stock adjustment (koreksi stok karena stock opname)
2. Admin login, lihat notification "Pending Approval"
3. Klik notification → lihat detail adjustment
4. Review: produk A seharusnya 100, tercatat 120, user adjust -20
5. Admin approve
6. Stok berkurang 20, history log "Approved by Admin X"

---

## Acceptance Criteria

### Dashboard Superadmin
- ✓ Menampilkan total minimarket yang aktif
- ✓ Menampilkan total produk across all stores
- ✓ Menampilkan minimarket dengan stok kritis (di bawah threshold)
- ✓ Grafik trend inventory (line chart, 30 hari terakhir)
- ✓ Recent activities (10 transaksi terakhir dari semua minimarket)

### Dashboard Admin
- ✓ Hanya tampilkan data minimarket yang di-assign ke admin ini
- ✓ Menampilkan low stock alerts
- ✓ Menampilkan recent transactions (minimarket ini saja)
- ✓ Link ke manage users, manage products

### Input Barang (User/Gudang)
- ✓ Form bisa diisi manual atau via barcode scan
- ✓ Auto-populate product info setelah SKU diinput
- ✓ Validation: quantity harus > 0
- ✓ Upload foto max 2MB, format JPG/PNG
- ✓ Success message setelah submit
- ✓ Redirect ke history page

### Laporan
- ✓ Filter by date range, product, category
- ✓ Export ke Excel dengan format rapi (header, total)
- ✓ Data sesuai dengan permission (Admin hanya lihat minimarket-nya)

### Audit Trail
- ✓ Log setiap create/update/delete action
- ✓ Log user_id, action, timestamp, IP address
- ✓ Immutable (tidak bisa diedit/dihapus)
- ✓ Superadmin bisa search & filter

---

## Success Metrics

- Superadmin bisa monitor 100+ minimarket dalam 1 dashboard
- Stock accuracy > 98% (hasil stock opname vs sistem)
- Average response time halaman < 2 detik
- Audit trail lengkap untuk semua critical actions
- User gudang bisa input barang dalam < 30 detik per item

---

## Tech Constraints

- Harus bisa diakses via browser (desktop & tablet)
- Responsive design (minimal 1024px width)
- Support Chrome, Edge, Safari (latest versions)
- Database scalable untuk 1000+ minimarket
- Export Excel max 10,000 rows per file

---

## Timeline & Phases

**Phase 1 (MVP):**
- Auth & role management
- Minimarket CRUD
- Product CRUD
- Basic inventory in/out
- Dashboard untuk 3 roles
- Simple reporting

**Phase 2 (Future):**
- Transfer antar minimarket
- Purchase order ke supplier
- Mobile app untuk barcode scanning
- Advanced analytics & forecasting
- Integration dengan payment gateway (jika ada transaksi)

---

## Prioritas MVP (Eksekusi Awal)

Urutan implementasi disarankan agar selaras dengan arsitektur, keamanan, dan kontrol data:

1. Authentication + role middleware + minimarket access middleware
2. Master data inti (Minimarket, User/Gudang, Kategori, Produk)
3. Inventory transaction (barang masuk/keluar/adjustment) dengan audit log
4. Dashboard role-based (Superadmin/Admin/User)
5. Reporting dasar + export

---

## Ketergantungan Dokumen Teknis

- Detail stack, struktur folder, command, dan paket rujuk ke `02-project-context.md`.
- Detail arsitektur Service + Repository rujuk ke `03-architecture.md`.
- Detail schema dan relasi database rujuk ke `04-databases-schema.md`.
- Aturan implementasi AI/codegen rujuk ke `06-ai-rule.md`.

---

## Glossary

- **Minimarket:** Cabang toko retail (seperti Alfamart/Indomaret)
- **SKU:** Stock Keeping Unit, kode unik produk
- **Stock Opname:** Penghitungan fisik stok vs sistem
- **Retur:** Pengembalian barang ke supplier
- **Soft Delete:** Data tidak dihapus, hanya ditandai inactive