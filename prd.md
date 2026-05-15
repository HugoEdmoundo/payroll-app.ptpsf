# Product Requirements Document (PRD)
## Payroll Application System (PTPSF)

**Version:** 1.0.0  
**Last Updated:** May 2026  
**Status:** Active Development  

---

## 📋 Daftar Isi
1. [Executive Summary](#executive-summary)
2. [Tujuan Produk](#tujuan-produk)
3. [Gambaran Umum Sistem](#gambaran-umum-sistem)
4. [Fitur-Fitur Utama](#fitur-fitur-utama)
5. [User Personas](#user-personas)
6. [Alur Proses Bisnis](#alur-proses-bisnis)
7. [Struktur Folder Aplikasi](#struktur-folder-aplikasi)
8. [Spesifikasi Teknis](#spesifikasi-teknis)
9. [Data Models](#data-models)
10. [Keamanan & Compliance](#keamanan--compliance)
11. [Roadmap Pengembangan](#roadmap-pengembangan)

---

## 📌 Executive Summary

**Payroll Application System (PTPSF)** adalah solusi manajemen penggajian terintegrasi yang dirancang untuk mengelola seluruh aspek operasional penggajian, mulai dari data karyawan, perhitungan gaji, absensi, potongan kasbon, hingga pembuatan slip gaji dan laporan keuangan.

Sistem ini menyediakan platform terpusat yang memungkinkan:
- Manajemen data karyawan dengan berbagai jenis dan status
- Perhitungan otomatis gaji berdasarkan komponen gaji yang fleksibel
- Pelacakan absensi dan kehadiran
- Manajemen kasbon dan cicilan
- Generasi slip gaji dan laporan payroll
- Import/export data dalam format Excel
- Dashboard analytics dan reporting real-time

---

## 🎯 Tujuan Produk

### Objektif Utama
1. **Efisiensi Operasional**: Mengotomatisasi proses penggajian yang kompleks dan mengurangi kesalahan manual
2. **Transparansi**: Memberikan visibility penuh terhadap struktur gaji dan perhitungan
3. **Scalability**: Mendukung pertumbuhan jumlah karyawan dan kompleksitas payroll
4. **Compliance**: Memastikan kesesuaian dengan regulasi peraturan gaji dan perpajakan lokal
5. **Akuntabilitas**: Mencatat semua transaksi dan perubahan untuk audit trail lengkap

### Target Users
- HR Manager / Admin Payroll
- Finance Manager
- Accounting Staff
- Executive Management (Dashboard access)
- Individual Employees (View personal slip gaji)

---

## 📊 Gambaran Umum Sistem

### Tech Stack
```
Backend Framework:  Laravel 11 (PHP 8.2+)
Frontend:           Vite + Tailwind CSS + Alpine.js
Database:           PostgreSQL / MySQL
File Processing:    Maatwebsite Excel, DOMPDF
Cloud Storage:      Cloudinary
Authentication:     Laravel Auth
Testing:            PHPUnit
```

### Arsitektur Tingkat Tinggi
```
┌─────────────────────────────────────────────┐
│         Presentation Layer (Vite/Tailwind)  │
│    - Dashboard                              │
│    - Forms & Data Tables                    │
│    - Reports & Analytics                    │
└────────────────┬────────────────────────────┘
                 │
┌────────────────▼────────────────────────────┐
│      Application Layer (Laravel Controllers)│
│    - Business Logic                         │
│    - Route Handling                         │
│    - API Endpoints                          │
└────────────────┬────────────────────────────┘
                 │
┌────────────────▼────────────────────────────┐
│       Domain Layer (Models & Services)      │
│    - Entities & Relationships                │
│    - Business Rules                         │
│    - Calculations & Validations             │
└────────────────┬────────────────────────────┘
                 │
┌────────────────▼────────────────────────────┐
│       Data Layer (Eloquent ORM)             │
│    - Database Queries                       │
│    - Migrations & Schema                    │
└─────────────────────────────────────────────┘
```

---

## 🎯 Fitur-Fitur Utama

### 1. Authentication & Access Control
**Deskripsi**: Sistem keamanan berbasis role dan permission

**Sub-fitur**:
- Login/Logout dengan email dan password
- Role-based access control (Admin, HR Manager, Finance, Accounting, Employee)
- Permission management yang granular
- Activity logging untuk semua action pengguna
- Session management & timeout

**Acceptance Criteria**:
- ✓ User dapat login dengan kredensial valid
- ✓ System mencegah akses ke resource yang tidak diizinkan
- ✓ Semua aksi dicatat dalam activity log
- ✓ Session timeout setelah periode inaktif

---

### 2. Dashboard & Analytics
**Deskripsi**: Overview real-time kondisi payroll dan HR metrics

**Sub-fitur**:
- Statistik karyawan (total, per departemen, per status)
- Ringkasan pengeluaran gaji (current month, YTD)
- Kasbon & cicilan overview
- Grafik trend pengeluaran
- Quick actions untuk task umum
- Managed users summary (untuk supervisors)
- API endpoints untuk real-time data

**Key Metrics**:
- Total karyawan aktif
- Total pengeluaran payroll (bulan ini, tahun ini)
- Jumlah kasbon pending
- Absensi rate

**Acceptance Criteria**:
- ✓ Dashboard load dalam < 3 detik
- ✓ Data real-time terupdate setiap akses
- ✓ Responsive design untuk mobile dan desktop
- ✓ Role-based filtering otomatis

---

### 3. Manajemen Karyawan
**Deskripsi**: CRUD lengkap untuk data karyawan

**Sub-fitur**:
- Create karyawan baru dengan form lengkap
- Edit data karyawan (personal, employment, compensation)
- View detail profile karyawan
- Delete karyawan (soft delete untuk audit trail)
- Bulk import dari Excel
- Export ke Excel (dengan template)
- Search & filter karyawan
- Dynamic fields (field custom per tipe karyawan)

**Data yang Dikelola**:
```
Personal Info:
- Nama lengkap, NIK, NPWP, tempat tanggal lahir
- Jenis kelamin, status perkawinan
- Agama, pendidikan terakhir

Employment Info:
- Nomor induk karyawan (NIK internal)
- Jenis karyawan (Permanent, Contract, Outsource, etc)
- Jabatan / Job title
- Departemen / Cost center
- Status pegawai (Active, Leave, Suspended, Resigned)
- Tanggal join, tanggal kontrak berakhir

Compensation Info:
- Gaji pokok
- Tunjangan (Transport, Meal, Health, etc)
- Deduction (Insurance, Tax, etc)
- Bank account untuk transfer gaji

Contact Info:
- Email, phone, address
- Emergency contact
```

**Acceptance Criteria**:
- ✓ Semua field validasi dengan rules yang tepat
- ✓ Import dari template Excel tanpa error
- ✓ Export include custom fields
- ✓ Duplicate NIK prevention
- ✓ Activity log untuk setiap perubahan

---

### 4. Manajemen Gaji (Payroll Configuration)
**Deskripsi**: Setup dan konfigurasi struktur gaji organisasi

#### 4.1 Pengaturan Komponen Gaji
**Sub-fitur**:
- Manage komponen gaji (Base Salary, Allowances, Deductions)
- Komponen types: Fixed, Percentage, Formula-based
- Define rules untuk setiap komponen
- Tax calculation setup
- Effective date management untuk salary changes

**Komponen Standard**:
```
PENGHASILAN (Income):
- Gaji Pokok (Base)
- Tunjangan Komunikasi
- Tunjangan Transportasi
- Tunjangan Makan
- Tunjangan Kesehatan
- Bonus/Insentif
- Overtime pay
- Shift allowance

POTONGAN (Deductions):
- PPh Pasal 21 (Tax)
- Iuran BPJS Kesehatan (Employee portion)
- Iuran BPJS Ketenagakerjaan
- Iuran Koperasi
- Kasbon cicilan
- Utility bills deduction
- Loan repayment
```

#### 4.2 Pengaturan BPJS & Koperasi
**Sub-fitur**:
- Global configuration untuk BPJS contribution rates
- Koperasi membership dan contribution settings
- Employee vs employer portion split
- Effective period management
- Approval workflow untuk perubahan

#### 4.3 Pengaturan Status Pegawai
**Sub-fitur**:
- Define berbagai status pegawai
- Salary calculation rules per status
- Benefit eligibility per status
- Status transition workflows

**Status Standard**:
- Permanent / Tetap
- Contract / Kontrak
- Probation / Percobaan
- Outsource / Outsourcing
- Casual / Kasual
- On Leave / Cuti
- Suspended / Ditangguhkan
- Resigned / Resign

#### 4.4 Perhitungan Gaji Otomatis
**Sub-fitur**:
- Batch calculation untuk semua karyawan
- Formula-based calculation engine
- Tax calculation integration
- Manual adjustment support
- Calculation history & audit trail
- Error handling & validation

**Calculation Logic**:
```
1. Base Salary (dari pengaturan karyawan)
2. + Applicable Allowances
3. + Overtime & Bonuses
4. = Gross Salary
5. - Taxes (PPh Pasal 21)
6. - BPJS Contributions
7. - Other Deductions
8. - Kasbon Cicilan
9. = Net Salary (Take-home)
```

**Acceptance Criteria**:
- ✓ Perhitungan akurat dengan 2 desimal
- ✓ Batch processing untuk 1000+ karyawan < 1 menit
- ✓ Rounding rules sesuai standar akuntansi
- ✓ Tax calculation per peraturan pemerintah terbaru
- ✓ Support untuk multiple salary cycles

---

### 5. Manajemen Absensi
**Deskripsi**: Tracking kehadiran dan perhitungan working days

**Sub-fitur**:
- Record absensi harian (Present, Absent, Late, Early Leave, Sick, PTO)
- Bulk import dari sistem absensi eksternal
- Manual entry & approval workflow
- Absence summary per karyawan
- Holiday & special dates management
- Working days calculation
- Impact calculation ke gaji

**Absensi Types**:
- Hadir (Present)
- Tidak Hadir (Absent) - dengan dokumen
- Terlambat (Late) - dengan deduction
- Pulang Cepat (Early Leave)
- Sakit (Sick Leave) - dengan surat sakit
- Cuti (Paid Time Off) - dari alokasi cuti
- Hari Libur (Holiday)
- Hari Raya (Religious Holiday)

**Acceptance Criteria**:
- ✓ Bulk import dengan validation rules
- ✓ Holiday auto-calculation
- ✓ Salary deduction otomatis untuk absent
- ✓ Late arrival tidak memperngaruhi gaji (configurable)
- ✓ Report absensi per periode

---

### 6. Manajemen Kasbon & Cicilan
**Deskripsi**: Kelola pinjaman kasbon dan cicilan pembayaran

**Sub-fitur**:
- Request kasbon oleh karyawan
- Approval workflow (HR -> Finance)
- Create cicilan payment schedule
- Automatic deduction dari gaji
- Cicilan tracking & reporting
- Early repayment support
- Default handling

**Kasbon Process**:
```
1. Employee request kasbon
   ├─ Amount
   ├─ Reason
   ├─ Proposed duration
   └─ Documentation

2. HR verification
   ├─ Check employee eligibility
   ├─ Verify maximum kasbon limit
   └─ Approve/Reject

3. Finance approval
   ├─ Budget check
   ├─ Create payment schedule
   └─ Disburse funds

4. Automatic deduction
   ├─ Monthly cicilan dari gaji
   ├─ Update balance
   └─ Interest calculation (if applicable)

5. Completion
   ├─ Full repayment
   └─ Archive kasbon record
```

**Acceptance Criteria**:
- ✓ Kasbon tidak exceed maximum limit
- ✓ Cicilan deduction otomatis setiap payroll
- ✓ Balance tracking akurat
- ✓ Report outstanding kasbon
- ✓ Notification untuk cicilan jatuh tempo

---

### 7. NKI (Normalisasi Komposisi Ikatan)
**Deskripsi**: Manajemen data ikatan/binding karyawan dan normalisasi

**Sub-fitur**:
- Record NKI per karyawan
- Document upload & management
- Status tracking & workflow
- Export ke format yang diperlukan
- Historical record maintenance

**Acceptance Criteria**:
- ✓ NKI document terverifikasi
- ✓ Version control untuk document changes
- ✓ Compliance dengan regulasi yang berlaku

---

### 8. Slip Gaji & Reporting
**Deskripsi**: Generate slip gaji dan laporan payroll komprehensif

**Sub-fitur**:
- Generate slip gaji PDF per karyawan
- Batch generate slip untuk semua karyawan
- Slip history & archival
- Email distribution ke karyawan
- Payroll summary report
- Tax report untuk pemerintah
- Kasbon report
- Expense breakdown analysis

**Slip Gaji Components**:
```
HEADER:
- Company name, logo, periode
- Employee ID, name, department
- Pay date, period

EARNINGS SECTION:
- Basic salary
- Allowances (detail per type)
- Bonus/Incentive
- Overtime
- Subtotal gross

DEDUCTIONS SECTION:
- Tax (PPh Pasal 21)
- BPJS Kesehatan
- BPJS Ketenagakerjaan
- Koperasi
- Kasbon cicilan
- Other deductions
- Subtotal deductions

SUMMARY:
- Gross salary
- Total deductions
- Net salary (take-home)

FOOTER:
- Bank account info
- Employee signature area
- HR signature area
```

**Report Types**:
1. Individual Slip (PDF)
2. Payroll Summary (Excel)
3. Tax Report (PPh format)
4. Bank Payment File (untuk transfer gaji)
5. Kasbon Outstanding Report
6. Employee Earnings History

**Acceptance Criteria**:
- ✓ PDF generate dalam < 5 detik per slip
- ✓ Batch generate untuk 1000 slip < 5 menit
- ✓ Email distribution otomatis
- ✓ Historical data permanent storage
- ✓ Tamper-proof generation

---

### 9. Import/Export Data
**Deskripsi**: Bulk data operations dengan Excel

**Supported Operations**:

#### Import:
- Karyawan (dari template Excel)
- Absensi (dari sistem eksternal)
- Kasbon (dari spreadsheet)
- Pengaturan Gaji (bulk update)

#### Export:
- Karyawan list (dengan template)
- Absensi report
- Pengaturan Gaji configuration
- Slip Gaji summary
- All reports & analytics

**Features**:
- Template download dengan contoh
- Validation rules sebelum import
- Error reporting dengan detail
- Partial rollback pada error
- Scheduled/batch export

**Acceptance Criteria**:
- ✓ Template tersedia untuk download
- ✓ Validation error message jelas
- ✓ Max import size 10MB
- ✓ Export maintain formatting & formula
- ✓ Special character handling (UTF-8)

---

### 10. Global Search & Global Settings
**Deskripsi**: Quick search across entities dan system configuration

**Search Capabilities**:
- Search karyawan (by name, NIK, email)
- Search kasbon (by employee, status)
- Search absensi records
- Full-text search
- Search history

**Global Settings**:
- Company info (name, address, tax ID)
- Bank account info
- Tax settings (PPh percentage, rules)
- Holiday calendar
- Payroll cycle configuration
- Document templates
- Email templates
- System parameters

**Acceptance Criteria**:
- ✓ Search response < 500ms
- ✓ Settings persist across sessions
- ✓ Audit trail untuk settings changes

---

### 11. Activity & Audit Logging
**Deskripsi**: Comprehensive audit trail untuk compliance

**Logged Actions**:
- User login/logout
- Data creation, update, delete
- Calculation runs
- Report generation
- File downloads
- Settings changes
- Approval workflow actions

**Log Contents**:
- Timestamp (dengan timezone)
- User ID & name
- Action type
- Entity affected
- Old value vs new value
- IP address
- User agent

**Acceptance Criteria**:
- ✓ Immutable log records
- ✓ Log retention sesuai policy
- ✓ Searchable activity history
- ✓ Export audit report

---

### 12. Profile & User Management
**Deskripsi**: Personal profile dan admin user management

**User Profile Features**:
- View/edit personal info
- Change password
- Profile photo
- Department & reporting structure

**Admin User Management**:
- Create/edit/delete users
- Assign roles & permissions
- Enable/disable users
- Reset password
- Manage access levels

**Acceptance Criteria**:
- ✓ Password strength validation
- ✓ Permission propagation to all actions
- ✓ Inactive user auto-lock

---

## 👥 User Personas

### 1. Admin Payroll
**Profile**: Berpengalaman, teknis
**Goals**: 
- Manage seluruh sistem payroll dengan efisien
- Ensure data accuracy & compliance
- Generate reports yang akurat

**Needs**:
- Easy configuration interface
- Bulk operations support
- Comprehensive validation
- Detailed audit trail

### 2. HR Manager
**Profile**: Non-teknis, user-friendly focused
**Goals**:
- Manage karyawan data dengan mudah
- Approve kasbon & leave requests
- Monitor employee status

**Needs**:
- Intuitive forms
- Clear workflows
- Quick search capability
- Mobile accessibility

### 3. Finance Manager
**Profile**: Detail-oriented, analytical
**Goals**:
- Ensure payroll accuracy
- Monitor cash flow
- Generate financial reports

**Needs**:
- Detailed calculations visibility
- Variance analysis
- Budget tracking
- Export untuk financial system

### 4. Employee
**Profile**: Non-technical, info consumer
**Goals**:
- View personal slip gaji
- Check gaji history
- Understand salary calculation

**Needs**:
- Simple view interface
- PDF download capability
- Clear salary breakdown
- Mobile view

### 5. Executive
**Profile**: High-level decision maker
**Goals**:
- Monitor payroll health
- Understand costs & trends
- Make strategic decisions

**Needs**:
- Executive dashboard
- Key metrics & KPIs
- Trend analysis
- Drill-down capability

---

## 🔄 Alur Proses Bisnis

### Proses 1: Onboarding Karyawan Baru
```
START
  │
  ├─→ HR input karyawan data
  │    │ (Personal, employment, compensation)
  │    └─→ System validate data
  │
  ├─→ Finance approve struktur gaji
  │
  ├─→ System setup salary calculation
  │    │ (Komponen gaji)
  │    └─→ Setup kasbon & deduction
  │
  ├─→ Activate karyawan di sistem
  │
  └─→ Generate welcome slip & setup
```

### Proses 2: Monthly Payroll Processing
```
START (1st of month)
  │
  ├─→ Input/Review Absensi
  │    │ (Manual atau import dari sistem eksternal)
  │    └─→ Approve absensi
  │
  ├─→ Verify Kasbon & Cicilan
  │
  ├─→ Run Payroll Calculation
  │    │ (Batch process untuk semua karyawan)
  │    ├─→ Calculate gross salary
  │    ├─→ Calculate deductions
  │    ├─→ Calculate net salary
  │    └─→ Generate slip gaji
  │
  ├─→ Review & Approve payroll
  │    │ (HR & Finance verification)
  │    └─→ Check untuk anomalies
  │
  ├─→ Generate Bank Payment File
  │    │ (Export untuk transfer gaji)
  │    └─→ Send ke finance untuk diproses
  │
  ├─→ Distribute Slip Gaji
  │    │ (Email ke karyawan atau print)
  │    └─→ Confirmation of delivery
  │
  └─→ Archive & Close Period
       └─→ Lock data untuk audit trail
```

### Proses 3: Kasbon Request & Approval
```
START
  │
  ├─→ Employee submit kasbon request
  │    │ (Amount, reason, duration)
  │    └─→ System validate eligibility
  │
  ├─→ HR review & verify
  │    │ (Check max limit, salary)
  │    └─→ HR Approve/Reject
  │
  ├─→ Finance review (jika approved)
  │    │ (Budget check)
  │    └─→ Finance Approve/Reject
  │
  ├─→ IF APPROVED:
  │    │
  │    ├─→ Generate cicilan schedule
  │    │
  │    ├─→ Disburse amount to employee
  │    │
  │    ├─→ Setup automatic deduction
  │    │    │ (Add ke payroll calculation)
  │    │    └─→ Start dari next payroll
  │    │
  │    └─→ Notify employee (email)
  │
  └─→ IF REJECTED:
       └─→ Notify employee dengan reason
```

### Proses 4: Absensi Recording & Impact
```
START
  │
  ├─→ Daily: Absensi record (dari attendance system)
  │
  ├─→ Monthly: Review & verify absensi
  │    │ (Check untuk exceptions, sick leave docs)
  │    └─→ Approve absensi untuk payroll
  │
  ├─→ Payroll: Calculate impact
  │    │
  │    ├─→ Calculate working days
  │    ├─→ Calculate absent deduction (if applicable)
  │    ├─→ Calculate late arrival impact
  │    ├─→ Calculate leave deduction
  │    └─→ Adjust gross salary
  │
  └─→ Final: Include di slip gaji
```

---

## 📁 Struktur Folder Aplikasi

```
payroll-app.ptpsf/
│
├── app/                          # Application logic
│   ├── Models/                   # Eloquent models
│   │   ├── User.php              # User & authentication
│   │   ├── Role.php
│   │   ├── Permission.php
│   │   ├── Karyawan.php          # Employee
│   │   ├── PengaturanGaji.php    # Salary configuration
│   │   ├── AcuanGaji.php         # Salary basis/reference
│   │   ├── HitungGaji.php        # Calculated salary
│   │   ├── SlipGaji.php          # Payslip
│   │   ├── Absensi.php           # Attendance
│   │   ├── Kasbon.php            # Loan/advance
│   │   ├── KasbonCicilan.php     # Loan installment
│   │   ├── NKI.php               # NKI binding record
│   │   ├── PengaturanBpjsKoperasi.php
│   │   ├── SystemSetting.php
│   │   ├── ActivityLog.php       # Audit trail
│   │   └── ...
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── DashboardController.php
│   │   │   ├── KaryawanController.php
│   │   │   ├── ProfileController.php
│   │   │   ├── GlobalSearchController.php
│   │   │   ├── Admin/
│   │   │   │   ├── UserController.php
│   │   │   │   ├── RoleController.php
│   │   │   │   └── SettingController.php
│   │   │   ├── Payroll/
│   │   │   │   ├── PengaturanGajiController.php
│   │   │   │   ├── HitungGajiController.php
│   │   │   │   ├── SlipGajiController.php
│   │   │   │   ├── AbsensiController.php
│   │   │   │   ├── KasbonController.php
│   │   │   │   ├── NKIController.php
│   │   │   │   └── ReportController.php
│   │   │   ├── Api/
│   │   │   │   └── DashboardApiController.php
│   │   │   └── Auth/
│   │   │       └── LoginController.php
│   │   │
│   │   ├── Middleware/
│   │   │   ├── Authenticate.php
│   │   │   ├── CheckRole.php
│   │   │   ├── CheckPermission.php
│   │   │   └── LogActivity.php
│   │   │
│   │   └── Kernel.php
│   │
│   ├── Services/                 # Business logic
│   │   ├── PayrollService.php
│   │   ├── KaryawanService.php
│   │   ├── ReportService.php
│   │   ├── ExcelService.php
│   │   └── EmailService.php
│   │
│   ├── Exports/                  # Excel export
│   │   ├── KaryawanExport.php
│   │   ├── AbsensiExport.php
│   │   ├── SlipGajiExport.php
│   │   └── ...
│   │
│   ├── Imports/                  # Excel import
│   │   ├── KaryawanImport.php
│   │   ├── AbsensiImport.php
│   │   └── ...
│   │
│   ├── Helpers/
│   │   ├── ActivityLogger.php
│   │   ├── PayrollCalculator.php
│   │   └── DateHelper.php
│   │
│   ├── Providers/
│   │   └── RouteServiceProvider.php
│   │
│   └── Requests/                 # Form validation
│       ├── StoreKaryawanRequest.php
│       ├── StorePengaturanGajiRequest.php
│       └── ...
│
├── config/                       # Configuration
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   ├── mail.php
│   ├── queue.php
│   ├── session.php
│   └── payroll.php
│
├── database/
│   ├── migrations/               # Schema migrations
│   │   ├── *_create_users_table.php
│   │   ├── *_create_roles_table.php
│   │   ├── *_create_karyawan_table.php
│   │   ├── *_create_pengaturan_gaji_table.php
│   │   ├── *_create_acuan_gaji_table.php
│   │   ├── *_create_hitung_gaji_table.php
│   │   ├── *_create_slip_gaji_table.php
│   │   ├── *_create_absensi_table.php
│   │   ├── *_create_kasbon_table.php
│   │   ├── *_create_kasbon_cicilan_table.php
│   │   ├── *_create_nki_table.php
│   │   ├── *_create_activity_logs_table.php
│   │   └── ...
│   │
│   ├── factories/
│   │   └── ...
│   │
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── RoleSeeder.php
│       └── ...
│
├── public/                       # Public assets
│   ├── index.php
│   ├── build/                    # Compiled assets (Vite)
│   └── images/
│
├── resources/
│   ├── css/
│   │   └── app.css
│   │
│   ├── js/
│   │   ├── app.js
│   │   └── bootstrap.js
│   │
│   └── views/                    # Blade templates
│       ├── layouts/
│       │   ├── app.blade.php
│       │   └── guest.blade.php
│       ├── auth/
│       │   └── login.blade.php
│       ├── dashboard/
│       │   └── index.blade.php
│       ├── karyawan/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       ├── payroll/
│       │   ├── pengaturan-gaji/
│       │   ├── hitung-gaji/
│       │   ├── slip-gaji/
│       │   ├── absensi/
│       │   ├── kasbon/
│       │   ├── nki/
│       │   └── reports/
│       ├── admin/
│       │   ├── users/
│       │   ├── roles/
│       │   └── settings/
│       └── components/
│           └── ...
│
├── routes/
│   ├── web.php
│   ├── api.php
│   └── console.php
│
├── storage/                      # Generated files
│   ├── app/
│   │   ├── exports/
│   │   ├── imports/
│   │   ├── documents/
│   │   └── pdf/
│   ├── framework/
│   └── logs/
│
├── tests/
│   ├── Feature/
│   └── Unit/
│
├── vendor/                       # Composer packages
│
├── .env
├── .env.example
├── .gitignore
├── artisan
├── composer.json
├── package.json
├── vite.config.js
├── tailwind.config.js
├── postcss.config.js
├── phpunit.xml
└── prd.md
```

---

## � Database Schema & ERD

### Database Overview
- **DBMS**: MySQL / PostgreSQL / SQLite
- **Total Tables**: 20+
- **Key Relationships**: Hierarchical (Karyawan → Salary Calculation → Slip)

### ERD (Entity Relationship Diagram)

```
┌─────────────┐
│   Users     │
│─────────────│
│ id (PK)     │
│ email       │
│ password    │
│ created_at  │
└──────┬──────┘
       │
       ├─────────────────────┐
       │                     │
       ▼                     ▼
┌──────────────┐      ┌──────────────────┐
│  Roles       │      │  Permissions     │
│──────────────│      │──────────────────│
│ id (PK)      │      │ id (PK)          │
│ name         │      │ name             │
│ created_at   │      │ action_type      │
└──────────────┘      └──────────────────┘


┌────────────────────────────────────────────────────────────────┐
│                    Karyawan (Employee) - Core                  │
│────────────────────────────────────────────────────────────────│
│ id_karyawan (PK)      │ Unique employee ID                     │
│ nama_karyawan         │ Full name                              │
│ join_date             │ Start date                             │
│ masa_kerja            │ Years of service (calculated)          │
│ jabatan               │ Job title                              │
│ lokasi_kerja          │ Work location                          │
│ jenis_karyawan        │ Type: Tetap, Kontrak, Outsource, etc  │
│ status_pegawai        │ Status: Aktif, Cuti, Suspend, Resign  │
│ npwp, bpjs_* (no)     │ Government IDs                         │
│ no_rekening, bank     │ Bank account for salary transfer       │
│ status_perkawinan     │ Marital status                         │
│ nama_istri            │ Spouse name (if applicable)            │
│ jumlah_anak           │ Number of children                     │
│ created_at, updated_at│ Timestamps                             │
└────────────────────┬─────────────────────────────────────────┘
                     │
                ┌────┴─────┬──────────────┬─────────────┐
                │           │              │             │
                ▼           ▼              ▼             ▼
    ┌───────────────────┐  ┌────────────────────┐  ┌──────────────┐  ┌──────────────┐
    │ Pengaturan Gaji   │  │  AcuanGaji(Basis)  │  │  Absensi     │  │  Kasbon      │
    │ (Salary Config)   │  │ (Salary Reference) │  │ (Attendance) │  │ (Loan/Adv)   │
    │───────────────────│  │────────────────────│  │──────────────│  │──────────────│
    │ id_pengaturan(PK) │  │ id_acuan (PK)      │  │ id_absensi   │  │ id_kasbon    │
    │ id_karyawan (FK)  │  │ id_karyawan (FK)   │  │ id_karyawan  │  │ id_karyawan  │
    │ jenis_karyawan    │  │ periode (YYYY-MM)  │  │ periode      │  │ periode      │
    │ jabatan           │  │                    │  │              │  │              │
    │ lokasi_kerja      │  │ Pendapatan:        │  │ hadir        │  │ nominal      │
    │ gaji_pokok        │  │ - gaji_pokok       │  │ on_site      │  │ metode_pembay│
    │ tunjangan_prestasi│  │ - bpjs_kesehatan   │  │ absence      │  │ status       │
    │                   │  │ - bpjs_kecelakaan  │  │ idle_rest    │  │ jumlah_cicil │
    │ gaji_nett         │  │ - tunjangan_*      │  │ izin_sakit   │  │ cicilan_terb │
    │ total_gaji        │  │ - reward, benefit  │  │ tanpa_ketera │  │              │
    │ unique constraint │  │ - total_pendapatan │  │ potongan_*   │  │ jumlah_cicil │
    │ keterangan        │  │                    │  │              │  │ cicilan_terb │
    │                   │  │ Pengeluaran:       │  │ keterangan   │  │ sisa_cicilan │
    │ created_at        │  │ - koperasi         │  │              │  │              │
    │                   │  │ - kasbon           │  │ created_at   │  │ created_at   │
    │                   │  │ - umroh, kurban    │  │              │  │              │
    │                   │  │ - mutabaah         │  └──────────────┘  └──────────────┘
    │                   │  │ - potongan_*       │
    │                   │  │ - total_pengeluaran│
    │                   │  │                    │
    │                   │  │ gaji_bersih        │
    │                   │  │ keterangan         │
    │                   │  │ created_at         │
    │                   │  │                    │
    │                   │  │ unique(karyawan_id,│
    │                   │  │ periode)           │
    │                   │  └────────┬───────────┘
    │                   │           │
    │                   │           ▼
    └───────────────────┘  ┌──────────────────────┐
                           │   HitungGaji         │
                           │  (Calculated Salary) │
                           │──────────────────────│
                           │ id (PK)              │
                           │ acuan_gaji_id (FK)   │
                           │ karyawan_id (FK)     │
                           │ periode (YYYY-MM)    │
                           │                      │
                           │ Penyesuaian (json):  │
                           │ - penyesuaian_pend.. │
                           │ - penyesuaian_peng.. │
                           │                      │
                           │ Total:               │
                           │ - total_pendapatan*  │
                           │ - total_pengeluaran* │
                           │ - take_home_pay      │
                           │                      │
                           │ status: draft/previe │
                           │ approved_by (FK)     │
                           │ approved_at          │
                           │ catatan_umum         │
                           │ unique(karyawan_id,  │
                           │ periode)             │
                           │ created_at           │
                           └────────┬─────────────┘
                                    │
                                    ▼
                           ┌──────────────────────┐
                           │    SlipGaji          │
                           │   (Payslip/Result)   │
                           │──────────────────────│
                           │ id (PK)              │
                           │ hitung_gaji_id (FK)  │
                           │ karyawan_id (FK)     │
                           │ periode (YYYY-MM)    │
                           │ nomor_slip (unique)  │
                           │                      │
                           │ Snapshot:            │
                           │ - nama_karyawan      │
                           │ - jabatan            │
                           │ - status_pegawai     │
                           │ - tanggal_mulai_*    │
                           │ - masa_kerja         │
                           │                      │
                           │ detail_pendapatan    │
                           │ detail_pengeluaran   │
                           │ total_pendapatan     │
                           │ total_pengeluaran    │
                           │ take_home_pay        │
                           │                      │
                           │ generated_at         │
                           │ generated_by (FK)    │
                           │ is_sent              │
                           │ sent_at              │
                           │ catatan              │
                           │ created_at           │
                           └──────────────────────┘


┌────────────────────────────────┐      ┌──────────────────────┐
│  NKI (Normalisasi Komposisi)   │      │ KasbonCicilan        │
│────────────────────────────────│      │ (Loan Installment)   │
│ id_nki (PK)                    │      │──────────────────────│
│ id_karyawan (FK)               │      │ id (PK)              │
│ periode (YYYY-MM)              │      │ kasbon_id (FK)       │
│ kemampuan (0-100)              │      │ cicilan_ke           │
│ kontribusi (0-100)             │      │ periode_cicilan      │
│ kedisiplinan (0-100)           │      │ nominal_cicilan      │
│ lainnya (0-100)                │      │ status_cicilan       │
│ nilai_nki (auto-calc)          │      │ tanggal_transfer     │
│ persentase_tunjangan           │      │ catatan              │
│ keterangan                     │      │ created_at           │
│ unique(karyawan, periode)      │      └──────────────────────┘
│ created_at                     │
└────────────────────────────────┘

┌──────────────────────────────────────────────┐
│  PengaturanBpjsKoperasi                      │
│ (Global System Settings for BPJS & Koperasi)│
│──────────────────────────────────────────────│
│ id (PK)                                      │
│ bpjs_kesehatan_pendapatan                    │
│ bpjs_kecelakaan_kerja_pendapatan             │
│ bpjs_kematian_pendapatan                     │
│ bpjs_jht_pendapatan                          │
│ bpjs_jp_pendapatan                           │
│ koperasi                                     │
│ created_at, updated_at                       │
└──────────────────────────────────────────────┘

┌──────────────────────────────────┐
│  ActivityLog                      │
│ (Audit Trail)                    │
│──────────────────────────────────│
│ id (PK)                          │
│ user_id (FK) → Users             │
│ action_type                      │
│ entity_type                      │
│ entity_id                        │
│ old_value (JSON)                 │
│ new_value (JSON)                 │
│ description                      │
│ ip_address                       │
│ user_agent                       │
│ created_at                       │
└──────────────────────────────────┘
```

### Key Tables Detail

#### 1. **Karyawan** (Employee Master)
```sql
CREATE TABLE karyawan (
    id_karyawan BIGINT PRIMARY KEY AUTO_INCREMENT,
    nama_karyawan VARCHAR(255) NOT NULL,
    join_date DATETIME NOT NULL,
    masa_kerja INT DEFAULT 0,
    jabatan VARCHAR(100) NOT NULL,
    lokasi_kerja VARCHAR(100) NOT NULL,
    jenis_karyawan VARCHAR(50) NOT NULL,  -- Tetap, Kontrak, Outsource
    status_pegawai VARCHAR(50) NOT NULL,   -- Aktif, Cuti, Suspend, Resign
    npwp VARCHAR(20) NULL,
    bpjs_kesehatan_no VARCHAR(20) NULL,
    bpjs_tk_no VARCHAR(20) NULL,
    no_rekening VARCHAR(30) NOT NULL,
    bank VARCHAR(50) NOT NULL,
    status_perkawinan VARCHAR(50) NULL,
    nama_istri VARCHAR(100) NULL,
    jumlah_anak INT DEFAULT 0,
    no_telp_istri VARCHAR(20) NULL,
    status_karyawan VARCHAR(50) NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### 2. **PengaturanGaji** (Salary Configuration)
```sql
CREATE TABLE pengaturan_gaji (
    id_pengaturan BIGINT PRIMARY KEY AUTO_INCREMENT,
    jenis_karyawan VARCHAR(50) NOT NULL,
    jabatan VARCHAR(100) NOT NULL,
    lokasi_kerja VARCHAR(100) NOT NULL,
    gaji_pokok DECIMAL(15,2) DEFAULT 0,
    tunjangan_prestasi DECIMAL(15,2) DEFAULT 0,
    gaji_nett DECIMAL(15,2) DEFAULT 0,
    total_gaji DECIMAL(15,2) DEFAULT 0,
    keterangan TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE KEY unique_pengaturan (jenis_karyawan, jabatan, lokasi_kerja)
);
```

#### 3. **AcuanGaji** (Salary Basis/Reference per Period)
```sql
CREATE TABLE acuan_gaji (
    id_acuan BIGINT PRIMARY KEY AUTO_INCREMENT,
    id_karyawan BIGINT NOT NULL,
    periode VARCHAR(7) NOT NULL,  -- YYYY-MM
    
    -- Pendapatan (Income)
    gaji_pokok DECIMAL(15,2) DEFAULT 0,
    bpjs_kesehatan DECIMAL(15,2) DEFAULT 0,
    bpjs_kecelakaan_kerja DECIMAL(15,2) DEFAULT 0,
    bpjs_kematian DECIMAL(15,2) DEFAULT 0,
    bpjs_jht DECIMAL(15,2) DEFAULT 0,
    bpjs_jp DECIMAL(15,2) DEFAULT 0,
    tunjangan_prestasi DECIMAL(15,2) DEFAULT 0,
    tunjangan_konjungtur DECIMAL(15,2) DEFAULT 0,
    benefit_ibadah DECIMAL(15,2) DEFAULT 0,
    benefit_komunikasi DECIMAL(15,2) DEFAULT 0,
    benefit_operasional DECIMAL(15,2) DEFAULT 0,
    reward DECIMAL(15,2) DEFAULT 0,
    total_pendapatan DECIMAL(15,2) DEFAULT 0,
    
    -- Pengeluaran (Deduction)
    koperasi DECIMAL(15,2) DEFAULT 0,
    kasbon DECIMAL(15,2) DEFAULT 0,
    umroh DECIMAL(15,2) DEFAULT 0,
    kurban DECIMAL(15,2) DEFAULT 0,
    mutabaah DECIMAL(15,2) DEFAULT 0,
    potongan_absensi DECIMAL(15,2) DEFAULT 0,
    potongan_kehadiran DECIMAL(15,2) DEFAULT 0,
    total_pengeluaran DECIMAL(15,2) DEFAULT 0,
    
    gaji_bersih DECIMAL(15,2) DEFAULT 0,
    keterangan TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE KEY unique_acuan_periode (id_karyawan, periode),
    FOREIGN KEY (id_karyawan) REFERENCES karyawan(id_karyawan) ON DELETE CASCADE
);
```

#### 4. **HitungGaji** (Calculated Salary with Adjustments)
```sql
CREATE TABLE hitung_gaji (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    acuan_gaji_id BIGINT NOT NULL,
    karyawan_id BIGINT NOT NULL,
    periode VARCHAR(7) NOT NULL,  -- YYYY-MM
    
    -- Original acuan
    pendapatan_acuan JSON,
    pengeluaran_acuan JSON,
    
    -- Adjustments
    penyesuaian_pendapatan JSON NULL,
    penyesuaian_pengeluaran JSON NULL,
    
    -- Calculations
    total_pendapatan_acuan DECIMAL(15,2) DEFAULT 0,
    total_penyesuaian_pendapatan DECIMAL(15,2) DEFAULT 0,
    total_pendapatan_akhir DECIMAL(15,2) DEFAULT 0,
    total_pengeluaran_acuan DECIMAL(15,2) DEFAULT 0,
    total_penyesuaian_pengeluaran DECIMAL(15,2) DEFAULT 0,
    total_pengeluaran_akhir DECIMAL(15,2) DEFAULT 0,
    take_home_pay DECIMAL(15,2) DEFAULT 0,
    
    -- Status
    status ENUM('draft', 'preview', 'approved') DEFAULT 'draft',
    approved_at TIMESTAMP NULL,
    approved_by BIGINT NULL,
    catatan_umum TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE KEY unique_hitung_periode (karyawan_id, periode),
    FOREIGN KEY (acuan_gaji_id) REFERENCES acuan_gaji(id_acuan),
    FOREIGN KEY (karyawan_id) REFERENCES karyawan(id_karyawan),
    FOREIGN KEY (approved_by) REFERENCES users(id)
);
```

#### 5. **SlipGaji** (Final Payslip)
```sql
CREATE TABLE slip_gaji (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    hitung_gaji_id BIGINT NOT NULL,
    karyawan_id BIGINT NOT NULL,
    periode VARCHAR(7) NOT NULL,  -- YYYY-MM
    nomor_slip VARCHAR(50) UNIQUE NOT NULL,  -- SG-YYYYMM-XXXX
    
    -- Snapshot from karyawan
    nama_karyawan VARCHAR(255) NOT NULL,
    jabatan VARCHAR(100) NOT NULL,
    status_pegawai VARCHAR(50) NOT NULL,
    tanggal_mulai_bekerja DATE NOT NULL,
    masa_kerja VARCHAR(50) NOT NULL,
    
    -- Details from hitung_gaji
    detail_pendapatan JSON NOT NULL,
    detail_pengeluaran JSON NOT NULL,
    total_pendapatan DECIMAL(15,2) NOT NULL,
    total_pengeluaran DECIMAL(15,2) NOT NULL,
    take_home_pay DECIMAL(15,2) NOT NULL,
    
    -- Status
    generated_at TIMESTAMP NOT NULL,
    generated_by BIGINT NOT NULL,
    is_sent BOOLEAN DEFAULT FALSE,
    sent_at TIMESTAMP NULL,
    catatan TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE KEY unique_slip_periode (karyawan_id, periode),
    FOREIGN KEY (hitung_gaji_id) REFERENCES hitung_gaji(id),
    FOREIGN KEY (karyawan_id) REFERENCES karyawan(id_karyawan),
    FOREIGN KEY (generated_by) REFERENCES users(id)
);
```

#### 6. **Absensi** (Attendance)
```sql
CREATE TABLE absensi (
    id_absensi BIGINT PRIMARY KEY AUTO_INCREMENT,
    id_karyawan BIGINT NOT NULL,
    periode VARCHAR(7) NOT NULL,  -- YYYY-MM
    jumlah_hari_bulan INT DEFAULT 30,
    hadir INT DEFAULT 0,
    on_site INT DEFAULT 0,
    absence INT DEFAULT 0,
    idle_rest INT DEFAULT 0,
    izin_sakit_cuti INT DEFAULT 0,
    tanpa_keterangan INT DEFAULT 0,
    potongan_absensi DECIMAL(15,2) DEFAULT 0,
    keterangan TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE KEY unique_absensi_periode (id_karyawan, periode),
    FOREIGN KEY (id_karyawan) REFERENCES karyawan(id_karyawan) ON DELETE CASCADE
);
```

#### 7. **Kasbon** (Loan/Advance)
```sql
CREATE TABLE kasbon (
    id_kasbon BIGINT PRIMARY KEY AUTO_INCREMENT,
    id_karyawan BIGINT NOT NULL,
    periode VARCHAR(7) NOT NULL,  -- YYYY-MM
    tanggal_pengajuan DATE NOT NULL,
    deskripsi TEXT NOT NULL,
    nominal DECIMAL(15,2) NOT NULL,
    metode_pembayaran ENUM('Langsung', 'Cicilan') DEFAULT 'Langsung',
    status_pembayaran ENUM('Pending', 'Lunas') DEFAULT 'Pending',
    jumlah_cicilan INT NULL,
    cicilan_terbayar INT DEFAULT 0,
    sisa_cicilan DECIMAL(15,2) DEFAULT 0,
    keterangan TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (id_karyawan) REFERENCES karyawan(id_karyawan) ON DELETE CASCADE
);
```

#### 8. **NKI** (Performance Scoring)
```sql
CREATE TABLE nki (
    id_nki BIGINT PRIMARY KEY AUTO_INCREMENT,
    id_karyawan BIGINT NOT NULL,
    periode VARCHAR(7) NOT NULL,  -- YYYY-MM
    kemampuan DECIMAL(5,2) DEFAULT 0,     -- 0-100
    kontribusi DECIMAL(5,2) DEFAULT 0,    -- 0-100
    kedisiplinan DECIMAL(5,2) DEFAULT 0,  -- 0-100
    lainnya DECIMAL(5,2) DEFAULT 0,       -- 0-100
    nilai_nki DECIMAL(5,2) DEFAULT 0,     -- Auto-calculated
    persentase_tunjangan INT DEFAULT 0,   -- 70, 80, 100
    keterangan TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE KEY unique_nki_periode (id_karyawan, periode),
    FOREIGN KEY (id_karyawan) REFERENCES karyawan(id_karyawan) ON DELETE CASCADE
);
```

#### 9. **PengaturanBpjsKoperasi** (Global System Settings)
```sql
CREATE TABLE pengaturan_bpjs_koperasi (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    bpjs_kesehatan_pendapatan DECIMAL(15,2) DEFAULT 0,
    bpjs_kecelakaan_kerja_pendapatan DECIMAL(15,2) DEFAULT 0,
    bpjs_kematian_pendapatan DECIMAL(15,2) DEFAULT 0,
    bpjs_jht_pendapatan DECIMAL(15,2) DEFAULT 0,
    bpjs_jp_pendapatan DECIMAL(15,2) DEFAULT 0,
    koperasi DECIMAL(15,2) DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### 10. **ActivityLog** (Audit Trail)
```sql
CREATE TABLE activity_logs (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    action_type VARCHAR(50) NOT NULL,
    entity_type VARCHAR(50) NOT NULL,
    entity_id BIGINT NULL,
    old_value JSON NULL,
    new_value JSON NULL,
    description TEXT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

### Relationships Summary

| From | To | Type | Foreign Key |
|------|----|----|-------------|
| karyawan | Users (system) | 1:N | - |
| pengaturan_gaji | karyawan | N:1 | - |
| acuan_gaji | karyawan | N:1 | id_karyawan |
| hitung_gaji | acuan_gaji | N:1 | acuan_gaji_id |
| hitung_gaji | karyawan | N:1 | karyawan_id |
| hitung_gaji | users | N:1 | approved_by |
| slip_gaji | hitung_gaji | N:1 | hitung_gaji_id |
| slip_gaji | karyawan | N:1 | karyawan_id |
| slip_gaji | users | N:1 | generated_by |
| absensi | karyawan | N:1 | id_karyawan |
| kasbon | karyawan | N:1 | id_karyawan |
| kasbon_cicilan | kasbon | N:1 | kasbon_id |
| nki | karyawan | N:1 | id_karyawan |
| activity_logs | users | N:1 | user_id |

---

---

## 📊 Data Models

### Karyawan (Employee)
```php
$table->id();
$table->string('nik_internal')->unique(); // Internal ID
$table->string('nik'); // National ID
$table->string('nama_lengkap');
$table->string('email')->unique();
$table->string('phone');
$table->date('tanggal_lahir');
$table->enum('jenis_kelamin', ['L', 'P']);
$table->enum('status_perkawinan', ['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati']);
$table->string('agama');
$table->string('pendidikan_terakhir');
$table->string('jabatan');
$table->string('departemen');
$table->enum('jenis_karyawan', ['Tetap', 'Kontrak', 'Outsource', 'Magang']);
$table->enum('status_pegawai', ['Aktif', 'Cuti', 'Suspend', 'Resign']);
$table->date('tanggal_join');
$table->date('tanggal_resign')->nullable();
$table->string('bank_name')->nullable();
$table->string('bank_account')->nullable();
$table->string('bank_account_holder')->nullable();
$table->decimal('gaji_pokok', 15, 2);
$table->text('alamat');
$table->string('foto')->nullable();
$table->timestamps();
$table->softDeletes();
```

### PengaturanGaji (Salary Configuration)
```php
$table->id();
$table->foreignId('karyawan_id')->constrained();
$table->decimal('gaji_pokok', 15, 2);
$table->decimal('tunjangan_transport', 15, 2)->default(0);
$table->decimal('tunjangan_makan', 15, 2)->default(0);
$table->decimal('tunjangan_komunikasi', 15, 2)->default(0);
$table->decimal('tunjangan_kesehatan', 15, 2)->default(0);
$table->decimal('tunjangan_lainnya', 15, 2)->default(0);
$table->decimal('potongan_bpjs_kesehatan', 15, 2)->default(0);
$table->decimal('potongan_bpjs_ketenagakerjaan', 15, 2)->default(0);
$table->decimal('potongan_koperasi', 15, 2)->default(0);
$table->decimal('potongan_lainnya', 15, 2)->default(0);
$table->date('berlaku_mulai');
$table->date('berlaku_hingga')->nullable();
$table->enum('status', ['Draft', 'Active', 'Inactive']);
$table->timestamps();
```

### HitungGaji (Calculated Salary)
```php
$table->id();
$table->foreignId('karyawan_id')->constrained();
$table->integer('tahun');
$table->integer('bulan');
$table->date('periode_dari');
$table->date('periode_hingga');
$table->decimal('gaji_pokok', 15, 2);
$table->decimal('tunjangan_total', 15, 2);
$table->decimal('potongan_total', 15, 2);
$table->decimal('gaji_kotor', 15, 2); // Gross
$table->decimal('pph_21', 15, 2);
$table->decimal('bpjs_kesehatan', 15, 2);
$table->decimal('bpjs_ketenagakerjaan', 15, 2);
$table->decimal('iuran_koperasi', 15, 2);
$table->decimal('cicilan_kasbon', 15, 2);
$table->decimal('potongan_lain', 15, 2);
$table->decimal('gaji_bersih', 15, 2); // Net
$table->enum('status', ['Draft', 'Calculated', 'Approved', 'Paid']);
$table->timestamps();
```

### SlipGaji (Payslip)
```php
$table->id();
$table->foreignId('hitung_gaji_id')->constrained();
$table->foreignId('karyawan_id')->constrained();
$table->string('nomor_slip')->unique();
$table->date('tanggal_slip');
$table->date('periode_dari');
$table->date('periode_hingga');
$table->json('detail_gaji'); // Serialized calculation details
$table->string('file_path')->nullable(); // Path to generated PDF
$table->enum('status', ['Draft', 'Generated', 'Sent', 'Downloaded']);
$table->timestamp('tanggal_kirim')->nullable();
$table->timestamps();
```

---

## 🔒 Keamanan & Compliance

### Privacy & Data Protection
- Sensitive data encryption (bank accounts, SSN)
- Data retention policy (3 years minimum)
- GDPR compliance for EU users
- Right to be forgotten mechanism

### Compliance Requirements
- PPh Pasal 21 calculation accuracy
- BPJS contribution calculation per regulation
- Payroll tax reports for government
- Labor law compliance (working hours, minimum wage)
- Financial audit trails

### Disaster Recovery
- Automated daily backups
- Backup encryption & storage
- Recovery point objective (RPO): 24 hours
- Recovery time objective (RTO): 4 hours
- Disaster recovery testing quarterly

---

## 🚀 Roadmap Pengembangan

### Phase 1: MVP (Current)
- [x] Authentication & Authorization
- [x] Karyawan management (CRUD)
- [x] Basic payroll calculation
- [x] Slip gaji generation (PDF)
- [x] Excel import/export
- [x] Activity logging

### Phase 2: Enhanced Features
- [ ] Advanced approval workflows
- [ ] Mobile app (iOS/Android)
- [ ] Real-time notifications
- [ ] Advanced reporting & analytics
- [ ] API integration dengan accounting system
- [ ] Multi-company support
- [ ] Advanced tax configuration

### Phase 3: Enterprise Features
- [ ] Biometric attendance integration
- [ ] Loan management system
- [ ] Expense management
- [ ] Performance management integration
- [ ] Predictive analytics & forecasting
- [ ] Advanced security (2FA, SSO)
- [ ] Data visualization dashboard

### Phase 4: AI & Automation
- [ ] ML-based salary recommendations
- [ ] Anomaly detection
- [ ] Automated reconciliation
- [ ] Chatbot support
- [ ] Document OCR for upload

---

## 📞 Support & Maintenance

### SLA (Service Level Agreement)
- Critical Issues: 2 hour response time
- High Priority: 4 hour response time
- Medium Priority: 1 business day
- Low Priority: 3 business days

### Change Management
- All changes must go through git version control
- Pull request review required
- QA testing before production deploy
- Documentation update required
- User communication for breaking changes

### Monitoring
- System health checks every 5 minutes
- Database performance monitoring
- Error rate tracking
- User activity monitoring
- Backup verification daily

---

## 📚 Additional Documentation

- **Setup Guide**: See README.md
- **API Documentation**: /docs/api
- **Database Schema**: /docs/database-schema
- **Deployment Guide**: /docs/deployment
- **Testing Guide**: /docs/testing

---

**Document Version**: 1.0.0  
**Last Updated**: May 2026  
**Next Review**: August 2026

---

*This PRD is a living document and subject to change based on business requirements and stakeholder feedback.*
