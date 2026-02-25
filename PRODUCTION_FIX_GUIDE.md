# Production Fix Guide - 500 Error

## Current Issue
- **Site**: https://just-atesting.hugoedm.fun
- **Error**: 500 Internal Server Error
- **Status**: GitHub Actions shows green ✅ but site returns 500 ❌

## Possible Causes

### 1. Missing Dashboard View
The dashboard view might not be deployed properly.

### 2. Cache Issues
Old cached routes/views/config causing conflicts.

### 3. Permission Issues
Storage or bootstrap/cache folders have wrong permissions.

### 4. Database Migration Not Run
The email_valid migration might not have run.

### 5. Composer Autoload Issues
Class autoload not updated after deployment.

### 6. PHP-FPM Not Restarted
Old PHP processes still running.

---

## Fix Steps (SSH Required)

### Option 1: Quick Fix Script (Recommended)

```bash
# SSH to server
ssh root@just-atesting.hugoedm.fun

# Navigate to project
cd /opt/just-atesting

# Run quick fix
bash quick-fix.sh
```

### Option 2: Manual Fix (Step by Step)

```bash
# SSH to server
ssh root@just-atesting.hugoedm.fun

# Navigate to project
cd /opt/just-atesting

# 1. Check if dashboard view exists
ls -la resources/views/dashboard/index.blade.php

# 2. If missing, create it
mkdir -p resources/views/dashboard
cat > resources/views/dashboard/index.blade.php << 'EOF'
@extends('layouts.app')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')
@section('content')
<div class="space-y-6">
    <div class="card p-6">
        <h1 class="text-2xl font-bold">Welcome, {{ auth()->user()->name }}!</h1>
        <p class="mt-2 text-gray-600">Dashboard Payroll System</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card p-6">
            <p class="text-sm text-gray-600">Total Karyawan</p>
            <p class="text-3xl font-bold mt-2">{{ $stats['total_karyawan'] ?? 0 }}</p>
        </div>
        <div class="card p-6">
            <p class="text-sm text-gray-600">Karyawan Aktif</p>
            <p class="text-3xl font-bold mt-2">{{ $stats['active_karyawan'] ?? 0 }}</p>
        </div>
        @if(isset($stats['total_users']))
        <div class="card p-6">
            <p class="text-sm text-gray-600">Total Users</p>
            <p class="text-3xl font-bold mt-2">{{ $stats['total_users'] }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
EOF

# 3. Check Laravel logs
tail -50 storage/logs/laravel.log

# 4. Run migrations
php artisan migrate --force

# 5. Clear all caches
php artisan optimize:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 6. Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Dump autoload
composer dump-autoload --optimize

# 8. Fix permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# 9. Restart PHP-FPM
systemctl restart php8.3-fpm

# 10. Check status
systemctl status php8.3-fpm
systemctl status nginx

# 11. Test
curl -I https://just-atesting.hugoedm.fun
```

### Option 3: Nuclear Option (If All Else Fails)

```bash
# SSH to server
ssh root@just-atesting.hugoedm.fun

cd /opt/just-atesting

# Backup .env
cp .env /tmp/.env.backup

# Pull latest from GitHub
git fetch origin main
git reset --hard origin/main

# Restore .env
cp /tmp/.env.backup .env

# Install dependencies
composer install --no-dev --optimize-autoloader

# Run migrations
php artisan migrate --force

# Clear and rebuild everything
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Fix permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Restart services
systemctl restart php8.3-fpm
systemctl restart nginx
```

---

## Debugging Commands

### Check Laravel Logs
```bash
tail -100 storage/logs/laravel.log
```

### Check Nginx Error Logs
```bash
tail -100 /var/log/nginx/error.log
```

### Check PHP-FPM Logs
```bash
tail -100 /var/log/php8.3-fpm.log
```

### Test PHP
```bash
php artisan --version
php artisan route:list
```

### Check File Permissions
```bash
ls -la storage/
ls -la bootstrap/cache/
```

### Check .env Configuration
```bash
cat .env | grep APP_
cat .env | grep DB_
```

### Test Database Connection
```bash
php artisan tinker
>>> DB::connection()->getPdo();
>>> exit
```

---

## Common Issues & Solutions

### Issue 1: "View [dashboard.index] not found"
**Solution**: Create dashboard view (see Option 2, step 2)

### Issue 2: "Class not found" or "Target class does not exist"
**Solution**:
```bash
composer dump-autoload --optimize
php artisan optimize:clear
```

### Issue 3: "Permission denied" on storage
**Solution**:
```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### Issue 4: "SQLSTATE[HY000] [2002] Connection refused"
**Solution**: Check database credentials in .env

### Issue 5: Cached routes causing 404
**Solution**:
```bash
php artisan route:clear
php artisan route:cache
```

### Issue 6: Old views being served
**Solution**:
```bash
php artisan view:clear
php artisan view:cache
```

---

## Prevention for Future

### 1. Add Health Check to Deployment

Update `.github/workflows/deploy.yml`:

```yaml
- name: Health Check
  uses: appleboy/ssh-action@v1.0.3
  with:
    host: ${{ secrets.VPS_HOST }}
    username: root
    key: ${{ secrets.VPS_SSH_KEY }}
    script: |
      sleep 5
      HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" https://just-atesting.hugoedm.fun)
      if [ $HTTP_CODE -ne 200 ]; then
        echo "❌ Health check failed! HTTP $HTTP_CODE"
        exit 1
      fi
      echo "✅ Health check passed! HTTP $HTTP_CODE"
```

### 2. Add Rollback Capability

```yaml
- name: Backup Current Release
  script: |
    if [ -d /opt/just-atesting ]; then
      tar -czf /opt/backups/release-$(date +%Y%m%d-%H%M%S).tar.gz /opt/just-atesting
    fi
```

### 3. Test Locally Before Deploy

```bash
# Always test locally
php artisan serve
# Visit http://localhost:8000
# Test all routes
```

---

## Contact Information

If still experiencing issues after all steps:

1. **Check logs**: Share Laravel log output
2. **Check environment**: Verify .env settings
3. **Check services**: Ensure nginx and php-fpm are running
4. **Check DNS**: Verify domain points to correct IP

---

## Quick Reference

### Essential Commands
```bash
# Navigate to project
cd /opt/just-atesting

# View logs
tail -50 storage/logs/laravel.log

# Clear caches
php artisan optimize:clear

# Rebuild caches
php artisan config:cache && php artisan route:cache && php artisan view:cache

# Fix permissions
chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache

# Restart PHP
systemctl restart php8.3-fpm

# Test site
curl -I https://just-atesting.hugoedm.fun
```

### One-Liner Fix
```bash
cd /opt/just-atesting && php artisan optimize:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache && chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache && systemctl restart php8.3-fpm
```
