# Real-Time Auto-Refresh Implementation Guide

## ✅ Yang Sudah Selesai

### 1. Activity Logging (SEMUA MODULE)
- ✅ Middleware `LogActivity` otomatis log semua CRUD operations
- ✅ Trait `LogsActivity` untuk manual logging
- ✅ Sudah diterapkan di semua controller:
  - Karyawan (create, update, delete, import, export)
  - NKI (create, update, delete, import, export)
  - Absensi, Acuan Gaji, Hitung Gaji, Kasbon, Pengaturan Gaji, Slip Gaji
  - User Management, Role Management
- ✅ Activity logs ditampilkan di dashboard superadmin
- ✅ Halaman dedicated untuk semua activity logs dengan filter

### 2. Real-Time System
- ✅ `realtime.js` - Dashboard widgets (stats, activities, managed users)
- ✅ `realtime-universal.js` - Universal system untuk SEMUA data
- ✅ Sudah include di `layouts/app.blade.php`

## 🚀 Cara Menggunakan Real-Time untuk Data Tables

### Metode 1: Otomatis dengan Data Attribute (PALING MUDAH)

Tambahkan attribute `data-realtime` pada wrapper table:

```blade
<!-- Karyawan Index -->
<div class="card p-0 overflow-hidden" 
     data-realtime 
     data-realtime-interval="normal"
     data-realtime-endpoint="{{ route('karyawan.index') }}"
     x-data="realtimeTable({ interval: 'normal' })">
    
    <div data-realtime-content>
        @include('components.karyawan.table', ['karyawan' => $karyawan])
    </div>
</div>
```

**Interval Options:**
- `fast` = 10 detik (untuk data critical)
- `normal` = 30 detik (default, untuk data regular)
- `slow` = 60 detik (untuk data yang jarang berubah)

### Metode 2: Manual dengan Alpine.js

```blade
<div x-data="realtimeTable({ 
    interval: 'normal',
    endpoint: '{{ route('karyawan.index') }}',
    selector: '[data-realtime-content]'
})">
    <!-- Loading indicator -->
    <div x-show="loading" class="text-sm text-gray-500">
        <i class="fas fa-sync fa-spin"></i> Updating...
    </div>
    
    <!-- Last update time -->
    <div class="text-xs text-gray-400" x-text="'Last update: ' + getLastUpdateText()"></div>
    
    <!-- Manual refresh button -->
    <button @click="forceRefresh()" class="btn btn-sm">
        <i class="fas fa-sync"></i> Refresh
    </button>
    
    <!-- Content yang akan di-refresh -->
    <div data-realtime-content>
        @include('components.karyawan.table', ['karyawan' => $karyawan])
    </div>
</div>
```

### Metode 3: Stats/Cards (untuk Dashboard)

```blade
<div x-data="realtimeStats({ 
    interval: 'normal',
    endpoint: '/api/dashboard/stats'
})">
    <div class="stat-card">
        <h3>Total Karyawan</h3>
        <p x-text="formatNumber(stats.total_karyawan)">0</p>
    </div>
    
    <div class="stat-card">
        <h3>Active Karyawan</h3>
        <p x-text="formatNumber(stats.active_karyawan)">0</p>
    </div>
</div>
```

### Metode 4: Lists (untuk Activity Logs, Users, dll)

```blade
<div x-data="realtimeList({ 
    interval: 'normal',
    endpoint: '/admin/activity-logs/latest',
    dataKey: 'activities'
})">
    <template x-for="item in items" :key="item.id">
        <div class="list-item">
            <p x-text="item.description"></p>
            <span x-text="item.time"></span>
        </div>
    </template>
    
    <div x-show="items.length === 0">
        No data available
    </div>
</div>
```

## 📝 Implementasi untuk Setiap Halaman

### Karyawan Index
```blade
<!-- resources/views/karyawan/index.blade.php -->
<div class="card p-0 overflow-hidden" 
     x-data="realtimeTable({ interval: 'normal' })">
    <div data-realtime-content>
        @include('components.karyawan.table', ['karyawan' => $karyawan])
    </div>
</div>
```

### NKI Index
```blade
<!-- resources/views/payroll/nki/index.blade.php -->
<div class="card p-0 overflow-hidden" 
     x-data="realtimeTable({ interval: 'normal' })">
    <div data-realtime-content>
        @include('components.nki.table', ['nkiList' => $nkiList])
    </div>
</div>
```

### Absensi Index
```blade
<!-- resources/views/payroll/absensi/index.blade.php -->
<div class="card p-0 overflow-hidden" 
     x-data="realtimeTable({ interval: 'normal' })">
    <div data-realtime-content>
        @include('components.absensi.table', ['absensiList' => $absensiList])
    </div>
</div>
```

### Kasbon Index
```blade
<!-- resources/views/payroll/kasbon/index.blade.php -->
<div class="card p-0 overflow-hidden" 
     x-data="realtimeTable({ interval: 'normal' })">
    <div data-realtime-content>
        @include('components.kasbon.table', ['kasbonList' => $kasbonList])
    </div>
</div>
```

### Pengaturan Gaji Index
```blade
<!-- resources/views/payroll/pengaturan-gaji/index.blade.php -->
<div class="card p-0 overflow-hidden" 
     x-data="realtimeTable({ interval: 'slow' })">
    <div data-realtime-content>
        @include('components.pengaturan-gaji.table', ['pengaturanGajiList' => $pengaturanGajiList])
    </div>
</div>
```

### Acuan Gaji Periode
```blade
<!-- resources/views/payroll/acuan-gaji/periode.blade.php -->
<div class="card p-0 overflow-hidden" 
     x-data="realtimeTable({ interval: 'normal' })">
    <div data-realtime-content>
        @include('components.acuan-gaji.table', ['acuanGajiList' => $acuanGajiList])
    </div>
</div>
```

### Hitung Gaji Periode
```blade
<!-- resources/views/payroll/hitung-gaji/periode.blade.php -->
<div class="card p-0 overflow-hidden" 
     x-data="realtimeTable({ interval: 'normal' })">
    <div data-realtime-content>
        @include('components.hitung-gaji.table', ['hitungGajiList' => $hitungGajiList])
    </div>
</div>
```

### Slip Gaji Periode
```blade
<!-- resources/views/payroll/slip-gaji/periode.blade.php -->
<div class="card p-0 overflow-hidden" 
     x-data="realtimeTable({ interval: 'normal' })">
    <div data-realtime-content>
        @include('components.slip-gaji.table', ['slipGajiList' => $slipGajiList])
    </div>
</div>
```

### Users Index
```blade
<!-- resources/views/admin/users/index.blade.php -->
<div class="card p-0 overflow-hidden" 
     x-data="realtimeTable({ interval: 'slow' })">
    <div data-realtime-content>
        @include('components.users.table', ['users' => $users])
    </div>
</div>
```

### Roles Index
```blade
<!-- resources/views/admin/roles/index.blade.php -->
<div class="card p-0 overflow-hidden" 
     x-data="realtimeTable({ interval: 'slow' })">
    <div data-realtime-content>
        @include('components.roles.table', ['roles' => $roles])
    </div>
</div>
```

## 🎯 Best Practices

### 1. Pilih Interval yang Tepat
- **Fast (10s)**: Dashboard stats, critical data
- **Normal (30s)**: Regular data tables (karyawan, nki, absensi, dll)
- **Slow (60s)**: Settings, roles, users (data yang jarang berubah)

### 2. Gunakan data-realtime-content
Pastikan content yang akan di-refresh dibungkus dengan `data-realtime-content`:

```blade
<div data-realtime-content>
    <!-- Content yang akan di-update -->
</div>
```

### 3. Preserve Pagination & Filters
System otomatis preserve query parameters (search, filters, pagination).

### 4. Loading Indicator (Optional)
```blade
<div x-show="loading" class="loading-indicator">
    <i class="fas fa-sync fa-spin"></i> Updating...
</div>
```

### 5. Manual Refresh Button (Optional)
```blade
<button @click="forceRefresh()" class="btn">
    <i class="fas fa-sync"></i> Refresh Now
</button>
```

### 6. Last Update Time (Optional)
```blade
<span x-text="'Last update: ' + getLastUpdateText()"></span>
```

## 🔧 Configuration

Edit `public/js/realtime-universal.js` untuk mengubah config global:

```javascript
const REALTIME_CONFIG = {
    intervals: {
        fast: 10000,    // 10 seconds
        normal: 30000,  // 30 seconds
        slow: 60000     // 60 seconds
    },
    enabled: true,      // Set false untuk disable semua realtime
    debug: false        // Set true untuk debug mode
};
```

## 🐛 Debugging

Enable debug mode:
```javascript
window.REALTIME_CONFIG.debug = true;
```

Atau di browser console:
```javascript
REALTIME_CONFIG.debug = true;
```

## 📊 Activity Logging

### Otomatis (via Middleware)
Semua CRUD operations otomatis ter-log:
- Create (store)
- Update (update)
- Delete (destroy)
- Import (import, importStore)
- Export (export)

### Manual (via Trait)
Jika perlu custom logging:

```php
use App\Traits\LogsActivity;

class MyController extends Controller
{
    use LogsActivity;
    
    public function myMethod()
    {
        // Log custom activity
        $this->logActivity('custom_action', 'Module Name', 'Description');
        
        // Or use helper methods
        $this->logCreate('Module', 'Item Name');
        $this->logUpdate('Module', 'Item Name');
        $this->logDelete('Module', 'Item Name');
        $this->logImport('Module', $count);
        $this->logExport('Module');
        $this->logGenerate('Module', 'Item Name');
    }
}
```

## 🎉 Summary

✅ **Activity Logging**: Semua CRUD operations otomatis ter-log
✅ **Real-Time System**: Siap digunakan untuk semua data
✅ **Easy Implementation**: Tinggal tambah `x-data="realtimeTable()"` dan `data-realtime-content`
✅ **Efficient**: Smart polling dengan configurable intervals
✅ **No Page Reload**: Semua data update otomatis tanpa reload
✅ **Hemat Resource**: Hanya update content yang perlu, preserve scroll position

Tinggal terapkan ke halaman-halaman yang kamu mau! 🚀
