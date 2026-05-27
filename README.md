# Invoice Management System

Sistem manajemen invoice yang dibangun dengan Laravel 11 untuk mengelola invoice, pelanggan, dan laporan omset.

## Fitur Utama

✅ **Dashboard**
- Statistik total invoice, revenue, invoices yang dibayar dan pending
- Grafik revenue bulanan
- Export data ke Excel

✅ **Invoice Management**
- Buat, edit, lihat, dan hapus invoice
- Tambah/hapus/edit item dalam invoice
- Kalkulasi otomatis subtotal, pajak (10%), dan total
- Status invoice (draft, sent, paid, cancelled)
- Catatan/notes untuk setiap invoice

✅ **Cetak & Export**
- Print invoice langsung dari browser
- Export invoice ke PDF
- Export semua data invoice ke Excel

✅ **Manajemen Pelanggan**
- Buat, edit, dan hapus pelanggan
- Simpan informasi lengkap pelanggan (nama, email, telepon, alamat, kota, negara)
- Lihat jumlah invoice per pelanggan

## Requirements

- PHP 8.2 atau lebih tinggi
- Composer
- Laravel 11
- SQLite (atau database lainnya)

## Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/dennisyuliants/invoice.git
cd invoice
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup
```bash
# Jalankan migrasi
php artisan migrate
```

### 5. Jalankan Server
```bash
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

## Struktur Folder

```
invoice/
├── app/
│   ├── Models/              # Model: Customer, Invoice, InvoiceItem
│   ├── Http/Controllers/    # Controller: InvoiceController, CustomerController
│   └── Exports/             # Export class untuk Excel
├── database/
│   ├── migrations/          # Database migrations
│   └── invoice.sqlite       # SQLite database
├── resources/
│   ├── views/
│   │   ├── layouts/         # Layout template
│   │   ├── invoices/        # Invoice views (create, edit, show, print, pdf)
│   │   ├── customers/       # Customer views (create, edit, index)
│   │   └── dashboard.blade.php
│   └── css/
├── routes/
│   └── web.php              # Web routes
├── .env.example             # Environment template
└── composer.json            # Dependencies
```

## Routes

| Method | Route | Action |
|--------|-------|--------|
| GET | `/` | Dashboard |
| GET | `/invoices` | List invoices |
| GET | `/invoices/create` | Create invoice form |
| POST | `/invoices` | Store invoice |
| GET | `/invoices/{id}` | View invoice |
| GET | `/invoices/{id}/edit` | Edit invoice form |
| PUT | `/invoices/{id}` | Update invoice |
| DELETE | `/invoices/{id}` | Delete invoice |
| GET | `/invoices/{id}/print` | Print invoice |
| GET | `/invoices/{id}/export-pdf` | Export PDF |
| GET | `/invoices/export-excel` | Export Excel |
| GET | `/customers` | List customers |
| GET | `/customers/create` | Create customer form |
| POST | `/customers` | Store customer |
| GET | `/customers/{id}/edit` | Edit customer form |
| PUT | `/customers/{id}` | Update customer |
| DELETE | `/customers/{id}` | Delete customer |

## Database Schema

### Customers Table
```sql
- id (Primary Key)
- name (String)
- email (String, nullable)
- phone (String, nullable)
- address (Text, nullable)
- city (String, nullable)
- country (String, nullable)
- timestamps
```

### Invoices Table
```sql
- id (Primary Key)
- invoice_number (String, unique)
- customer_id (Foreign Key)
- invoice_date (Date)
- due_date (Date, nullable)
- subtotal (Decimal)
- tax (Decimal)
- total (Decimal)
- status (String: draft, sent, paid, cancelled)
- notes (Text, nullable)
- timestamps
```

### Invoice Items Table
```sql
- id (Primary Key)
- invoice_id (Foreign Key)
- item_name (String)
- description (Text, nullable)
- quantity (Integer)
- unit_price (Decimal)
- total (Decimal)
- timestamps
```

## Penggunaan

### Membuat Invoice Baru
1. Masuk ke menu **Invoices**
2. Klik tombol **New Invoice**
3. Pilih customer
4. Atur tanggal invoice dan due date
5. Tambahkan item-item invoice
6. Sistem akan otomatis menghitung subtotal, pajak, dan total
7. Klik **Save Invoice**

### Edit Invoice
1. Buka invoice yang ingin diedit
2. Klik tombol **Edit**
3. Ubah data yang diperlukan
4. Klik **Update Invoice**

### Print & Export
- **Print**: Klik tombol **Print** untuk membuka dialog print
- **PDF**: Klik tombol **Export PDF** untuk download PDF
- **Excel**: Di dashboard atau list invoices, klik **Export Excel**

### Manajemen Pelanggan
1. Masuk ke menu **Customers**
2. Klik **New Customer** untuk menambah pelanggan baru
3. Isi data lengkap pelanggan
4. Klik tombol **Edit** atau **Delete** untuk ubah/hapus

## Fitur Lanjutan

### Kalkulasi Otomatis
- Setiap kali quantity atau unit price diubah, total item akan otomatis terhitung
- Subtotal, pajak (10%), dan total invoice akan otomatis dihitung
- Format currency Rupiah (Rp)

### Pajak
- Pajak otomatis dihitung 10% dari subtotal
- Dapat dimodifikasi di `InvoiceController@calculateTotals()`

### Export Data
- Export invoice ke file Excel (.xlsx) dengan format yang rapih
- Berisi kolom: Invoice Number, Customer Name, Invoice Date, Due Date, Subtotal, Tax, Total, Status

## Troubleshooting

### Error: "Target [laravel.log] does not exist"
```bash
php artisan storage:link
```

### Database error
```bash
php artisan migrate:fresh
```

### Hapus semua data dan reset
```bash
rm database/invoice.sqlite
php artisan migrate
```

## Development

### Menambah Field Baru
1. Buat migration: `php artisan make:migration add_field_to_invoices`
2. Edit migration file
3. Jalankan: `php artisan migrate`
4. Update Model di `app/Models/`
5. Update View sesuai kebutuhan

### Custom Validation
Edit `InvoiceController@store()` atau `InvoiceController@update()` untuk menambah validasi custom.

## License

MIT License - Open Source

## Author

Dennis Yuliants

## Support

Untuk pertanyaan atau issue, silakan buat issue di repository ini.
