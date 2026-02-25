# Manual Fix Production - Step by Step

## Error: 500 Internal Server Error
Site: https://just-atesting.hugoedm.fun

## Quick Fix (Run via SSH)

### Step 1: SSH ke Server
```bash
ssh root@just-atesting.hugoedm.fun
# atau
ssh root@[IP_ADDRESS]
```

### Step 2: Navigate to Project
```bash
cd /opt/just-atesting
```

### Step 3: Check Current State
```bash
# Check if dashboard view exists
ls -la resources/views/dashboard/

# Check Laravel log
tail -50 storage/logs/laravel.log
```

### Step 4: Create Dashboard View (If Missing)
```bash
mkdir -p resources/views/dashboard

cat > resources/views/dashboard/index.blade.php << 'EOFVIEW'
@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="space-y-6">
    <div class="card p-6">
        <h1 class="text-2xl font-bold text-gray-900">Welcome, {{ auth()->user()->name }}!</h1>
        <p class="mt-2 text-gray-600">Dashboard Payroll System PT. PSF</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Karyawan</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_karyawan'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Karyawan Aktif</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['active_karyawan'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-check text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        @if(isset($stats['total_users']))
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Users</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_users'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-shield text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
        @endif
    </div>

    @if(isset($stats['recent_karyawan']) && $stats['recent_karyawan']->count() > 0)
    <div class="card">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold">Recent Karyawan</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jabatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($stats['recent_karyawan'] as $k)
                    <tr>
                        <td class="px-6 py-4">{{ $k->nama_karyawan }}</td>
                        <td class="px-6 py-4">{{ $k->jabatan }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full {{ $k->status_karyawan === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $k->status_karyawan }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
EOFVIEW
```

### Step 5: Clear All Caches
```bash
php artisan optimize:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Step 6: Rebuild Caches
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 7: Fix Permissions
```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### Step 8: Restart PHP-FPM
```bash
systemctl restart php8.3-fpm
# or
systemctl restart php-fpm
```

### Step 9: Check Logs Again
```bash
tail -50 storage/logs/laravel.log
```

### Step 10: Test
Open browser: https://just-atesting.hugoedm.fun

---

## Alternative: One-Line Fix

```bash
cd /opt/just-atesting && mkdir -p resources/views/dashboard && cat > resources/views/dashboard/index.blade.php << 'EOF'
@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold">Dashboard</h1>
    <p class="mt-4">Welcome, {{ auth()->user()->name }}!</p>
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-semibold">Total Karyawan</h3>
            <p class="text-3xl font-bold mt-2">{{ $stats['total_karyawan'] ?? 0 }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-semibold">Karyawan Aktif</h3>
            <p class="text-3xl font-bold mt-2">{{ $stats['active_karyawan'] ?? 0 }}</p>
        </div>
        @if(isset($stats['total_users']))
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="font-semibold">Total Users</h3>
            <p class="text-3xl font-bold mt-2">{{ $stats['total_users'] }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
EOF
php artisan optimize:clear && php artisan view:clear && php artisan view:cache && chown -R www-data:www-data storage bootstrap/cache && systemctl restart php8.3-fpm
```

---

## Troubleshooting

### If Still Error After Fix:

#### 1. Check .env File
```bash
cat .env | grep APP_
```
Make sure:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_KEY` is set

#### 2. Check Database Connection
```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

#### 3. Check File Permissions
```bash
ls -la storage/
ls -la bootstrap/cache/
```
Should be owned by `www-data:www-data`

#### 4. Check PHP Version
```bash
php -v
```
Should be PHP 8.3 or higher

#### 5. Check Composer Autoload
```bash
composer dump-autoload --optimize
```

#### 6. Check Web Server Config
```bash
# For Nginx
nginx -t
systemctl status nginx

# For Apache
apachectl -t
systemctl status apache2
```

#### 7. View Full Error (Temporarily)
```bash
# Edit .env
nano .env
# Change: APP_DEBUG=true
# Save and exit

# Clear cache
php artisan config:clear

# Check site again to see full error
# Then change back to APP_DEBUG=false
```

---

## Common Issues & Solutions

### Issue 1: "View [dashboard.index] not found"
**Solution**: Create dashboard view (Step 4 above)

### Issue 2: "Class not found"
**Solution**: 
```bash
composer dump-autoload --optimize
php artisan optimize:clear
```

### Issue 3: "Permission denied"
**Solution**:
```bash
chown -R www-data:www-data /opt/just-atesting
chmod -R 775 storage bootstrap/cache
```

### Issue 4: "Database connection failed"
**Solution**: Check .env database credentials

### Issue 5: "Route not found"
**Solution**:
```bash
php artisan route:clear
php artisan route:cache
```

---

## Prevention for Future Deployments

### 1. Add Health Check to Deployment
Add to `.github/workflows/deploy.yml`:
```yaml
- name: Health Check
  run: |
    sleep 10
    curl -f https://just-atesting.hugoedm.fun || exit 1
```

### 2. Keep Backup Before Deploy
```bash
# Before deployment
tar -czf backup-$(date +%Y%m%d-%H%M%S).tar.gz /opt/just-atesting
```

### 3. Test Locally First
```bash
# Always test locally before push
php artisan serve
# Visit http://localhost:8000
```

---

## Contact for Help

If still error after all steps:
1. Check Laravel log: `tail -100 storage/logs/laravel.log`
2. Check web server error log:
   - Nginx: `/var/log/nginx/error.log`
   - Apache: `/var/log/apache2/error.log`
3. Share the error message for further assistance

