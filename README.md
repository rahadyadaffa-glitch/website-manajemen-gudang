# VOXEL WMS - Warehouse Management System 📦

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![TailwindCSS](https://img.shields.io/badge/Tailwind-v3-38B2AC?style=for-the-badge&logo=tailwind-css)](https://tailwindcss.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

**VOXEL WMS** adalah sistem manajemen pergudangan (Warehouse Management System) modern yang dirancang untuk efisiensi tinggi, keamanan data, dan pengalaman pengguna yang responsif. Dibangun dengan estetika **Voxel/Dark Mode**, sistem ini memfasilitasi pengelolaan stok multi-cabang secara real-time.

---

## ✨ Fitur Unggulan

### 🛡️ Keamanan & Integritas Data
- **Secure Deletion Confirmation**: Mencegah penghapusan data tidak sengaja dengan fitur "Type-to-Confirm". Pengguna harus mengetik kalimat konfirmasi spesifik sebelum menghapus record penting.
- **Audit Trail Terperinci**: Log aktivitas 24 jam yang mencatat setiap pergerakan stok, perubahan data, dan aktivitas pengguna di seluruh cabang.

### ⚡ Performa & UX Modern
- **Real-Time AJAX Search**: Pencarian barang, user, dan log menggunakan teknologi AJAX dengan *Debounce* (400ms) untuk hasil instan tanpa reload halaman.
- **Hierarchical Category Filtering**: Filter inventori dua tingkat (Kategori Utama & Sub-Kategori) untuk navigasi stok yang presisi.
- **Responsive Dashboard**: Antarmuka yang dioptimalkan untuk berbagai perangkat dengan gaya desain pixel/voxel yang unik.

### 📊 Manajemen Stok & Analisis
- **Multi-Branch Support**: Manajemen inventori terpusat untuk banyak cabang minimarket.
- **Unit Conversion (DUS to PCS)**: Kalkulasi otomatis jumlah barang dalam satuan Dus maupun Pcs secara akurat.
- **Trend Analysis Chart**: Visualisasi pergerakan stok masuk dan keluar selama 30 hari terakhir menggunakan Chart.js dengan gaya pixelated.

---

## 🛠️ Tech Stack

- **Backend**: Laravel 11.x
- **Frontend**: Blade Templates, Tailwind CSS (Custom Voxel Theme), Alpine.js
- **Database**: MySQL / PostgreSQL
- **Interactivity**: AJAX, Chart.js, SweetAlert2, Select2
- **Icons**: Google Material Symbols

---

## 🚀 Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/rahadyadaffa-glitch/website-manajemen-gudang.git
   cd website-manajemen-gudang
   ```

2. **Install Dependensi**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Sesuaikan pengaturan database di file `.env`.*

4. **Migrasi & Seed Data**
   ```bash
   php artisan migrate --seed
   ```

5. **Jalankan Server**
   ```bash
   php artisan serve
   ```

---

## 📸 Tampilan Antarmuka
Sistem menggunakan tema **Dark Mode** dengan aksen **Amber-500**, memberikan kesan premium dan profesional yang fokus pada keterbacaan data.

---

## 📄 Lisensi
Proyek ini dilisensikan di bawah [MIT License](LICENSE).

---
<p align="center">
  Dibuat dengan ❤️ untuk efisiensi gudang masa depan.
</p>
