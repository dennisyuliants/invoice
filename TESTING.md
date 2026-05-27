# Invoice Management System - Quick Start Guide

## Setup Cepat (5 Menit)

### Step 1: Clone & Install
```bash
git clone https://github.com/dennisyuliants/invoice.git
cd invoice
composer install
```

### Step 2: Setup Database
```bash
cp .env.example .env
php artisan key:generate
php artisan migrate
```

### Step 3: Jalankan
```bash
php artisan serve
```
Buka browser: `http://localhost:8000`

---

## Fitur & Testing

### 1. Dashboard
- Statistik invoice, revenue, status
- Grafik revenue bulanan
- Export ke Excel

**Testing:**
```
URL: http://localhost:8000/
Harapan: Melihat dashboard dengan statistik
```

### 2. Buat Invoice
**Testing:**
```
1. Klik "New Invoice"
2. Pilih customer (buat customer baru jika perlu)
3. Tambahkan items (minimal 1)
4. Verifikasi kalkulasi otomatis
5. Klik "Save"
```

### 3. Edit Invoice
**Testing:**
```
1. Pilih invoice dari list
2. Klik "Edit"
3. Ubah data/items
4. Verifikasi kalkulasi ulang
5. Klik "Update"
```

### 4. Print & Export
**Testing Print:**
```
1. Buka invoice
2. Klik "Print"
3. Ctrl+P untuk print ke PDF
```

**Testing Export PDF:**
```
1. Buka invoice
2. Klik "Export PDF"
3. File akan download
```

**Testing Export Excel:**
```
1. Dashboard atau Invoice List
2. Klik "Export Excel"
3. File akan download dengan semua invoice
```

### 5. Manajemen Pelanggan
**Testing:**
```
1. Klik menu "Customers"
2. Klik "New Customer"
3. Isi data lengkap
4. Edit/Delete customer
```

---

## Validation Testing

### Create/Edit Invoice
- Coba submit tanpa customer → error required
- Coba submit tanpa items → error required
- Coba submit dengan quantity 0 → error min:1
- Coba submit dengan unit price negatif → error min:0

### Create/Edit Customer
- Coba submit tanpa nama → error required
- Coba submit email duplikat → error unique
- Coba submit email format salah → error email

---

## Database Testing

### View Database
```bash
# Buka file SQLite
open database/invoice.sqlite
# Atau gunakan tools: SQLite Browser, DBeaver, dsb
```

### Check Tables
```bash
php artisan tinker
>>> \DB::table('invoices')->get();
>>> \DB::table('customers')->get();
>>> \DB::table('invoice_items')->get();
```

---

## Troubleshooting

| Error | Solusi |
|-------|--------|
| "No application encryption key has been generated" | Jalankan: `php artisan key:generate` |
| Database not found | Jalankan: `php artisan migrate` |
| CSRF token mismatch | Refresh page, jangan submit form dua kali |
| Storage permission denied | Jalankan: `chmod -R 777 storage bootstrap/cache` |

---

## Development Commands

```bash
# Generate Model + Migration + Controller
php artisan make:model ModelName -mcr

# Refresh Database (hapus semua data)
php artisan migrate:fresh

# Seed dummy data
php artisan make:seeder InvoiceSeeder
php artisan db:seed

# Clear cache
php artisan cache:clear
php artisan config:cache
```

---

## Environment Variables (.env)

```
APP_NAME="Invoice System"
APP_ENV=local
APP_DEBUG=true
APP_KEY=base64:...
DB_CONNECTION=sqlite
DB_DATABASE=database/invoice.sqlite
```

---

## Deployment

Untuk deploy ke production:

1. Upload ke server
2. Install dependencies: `composer install --no-dev`
3. Set environment: `APP_ENV=production APP_DEBUG=false`
4. Generate key: `php artisan key:generate`
5. Run migrations: `php artisan migrate`
6. Set permissions: `chmod -R 755 storage bootstrap/cache`
7. Configure web server (nginx/apache) dengan document root ke `public/`

---

## API Endpoints (untuk integration)

Meskipun saat ini menggunakan Web routes, berikut untuk future REST API:

```
GET    /api/invoices          - List all invoices
POST   /api/invoices          - Create invoice
GET    /api/invoices/{id}     - Get invoice detail
PUT    /api/invoices/{id}     - Update invoice
DELETE /api/invoices/{id}     - Delete invoice

GET    /api/customers         - List all customers
POST   /api/customers         - Create customer
GET    /api/customers/{id}    - Get customer detail
PUT    /api/customers/{id}    - Update customer
DELETE /api/customers/{id}    - Delete customer
```

---

## Support & Issues

Jika ada masalah:
1. Baca error message dengan teliti
2. Check `storage/logs/laravel.log` untuk detail error
3. Coba `php artisan migrate:fresh` untuk reset database
4. Coba clear cache: `php artisan cache:clear`

Good luck! 🚀
