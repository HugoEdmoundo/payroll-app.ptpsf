# Production Fix Scripts

This directory contains scripts to diagnose and fix production issues.

## Available Scripts

### 1. diagnose.sh
**Purpose**: Comprehensive diagnostic tool to identify issues

**Usage**:
```bash
bash diagnose.sh
```

**What it checks**:
- PHP and Laravel versions
- Environment configuration (.env)
- Critical files existence
- Storage permissions
- Database connection
- Routes
- Services status (PHP-FPM, Nginx)
- Site accessibility
- Laravel logs
- Nginx error logs
- PHP-FPM error logs

**Output**: Detailed diagnostic report with summary

**When to use**: 
- Before running fixes (to understand the problem)
- After fixes (to verify everything is correct)
- When troubleshooting unknown issues

---

### 2. quick-fix.sh
**Purpose**: Automated fix for common production issues

**Usage**:
```bash
bash quick-fix.sh
```

**What it does**:
1. Checks Laravel logs
2. Verifies dashboard view exists (creates if missing)
3. Runs database migrations
4. Dumps composer autoload
5. Clears all caches (optimize, cache, config, route, view)
6. Rebuilds caches (config, route, view)
7. Fixes storage permissions
8. Restarts PHP-FPM
9. Checks services status
10. Tests site accessibility
11. Provides detailed report

**Output**: Step-by-step progress with final status report

**When to use**:
- After deployment if site shows 500 error
- When dashboard view is missing
- When caches are corrupted
- When permissions are wrong
- As first attempt to fix any issue

---

## Quick Start Guide

### If Site Shows 500 Error:

```bash
# 1. SSH to server
ssh root@just-atesting.hugoedm.fun

# 2. Navigate to project
cd /opt/just-atesting

# 3. Run quick fix
bash quick-fix.sh

# 4. Check result
# Script will show if site is working or not
```

### If You Want to Investigate First:

```bash
# 1. SSH to server
ssh root@just-atesting.hugoedm.fun

# 2. Navigate to project
cd /opt/just-atesting

# 3. Run diagnostic
bash diagnose.sh

# 4. Review output and identify issue

# 5. Run quick fix
bash quick-fix.sh
```

---

## Common Scenarios

### Scenario 1: After GitHub Actions Deploy
```bash
# Wait for GitHub Actions to complete
# Test site: https://just-atesting.hugoedm.fun
# If 500 error:
ssh root@just-atesting.hugoedm.fun
cd /opt/just-atesting
bash quick-fix.sh
```

### Scenario 2: Dashboard View Missing
```bash
ssh root@just-atesting.hugoedm.fun
cd /opt/just-atesting
bash quick-fix.sh
# Script will create dashboard view automatically
```

### Scenario 3: Cache Issues
```bash
ssh root@just-atesting.hugoedm.fun
cd /opt/just-atesting
bash quick-fix.sh
# Script will clear and rebuild all caches
```

### Scenario 4: Permission Issues
```bash
ssh root@just-atesting.hugoedm.fun
cd /opt/just-atesting
bash quick-fix.sh
# Script will fix storage and cache permissions
```

### Scenario 5: Unknown Issue
```bash
ssh root@just-atesting.hugoedm.fun
cd /opt/just-atesting
bash diagnose.sh > report.txt
cat report.txt
# Review report to identify issue
bash quick-fix.sh
```

---

## Script Requirements

### System Requirements:
- Root access or sudo privileges
- Bash shell
- Running from Laravel project directory (/opt/just-atesting)

### Dependencies:
- PHP CLI
- Composer
- Artisan (Laravel)
- systemctl (for service management)
- curl (for site testing)

---

## Troubleshooting the Scripts

### If diagnose.sh fails:
```bash
# Check if in correct directory
pwd
# Should show: /opt/just-atesting

# Check if artisan exists
ls -la artisan

# Run with verbose output
bash -x diagnose.sh
```

### If quick-fix.sh fails:
```bash
# Check permissions
ls -la quick-fix.sh

# Make executable if needed
chmod +x quick-fix.sh

# Run with verbose output
bash -x quick-fix.sh

# Run as root if permission denied
sudo bash quick-fix.sh
```

---

## Manual Commands

If scripts don't work, run commands manually:

```bash
# Navigate to project
cd /opt/just-atesting

# Check logs
tail -50 storage/logs/laravel.log

# Clear caches
php artisan optimize:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Fix permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Restart PHP-FPM
systemctl restart php8.3-fpm

# Test site
curl -I https://just-atesting.hugoedm.fun
```

---

## Success Indicators

### diagnose.sh success:
- Shows "No critical issues found"
- HTTP Response Code: 200 or 302
- All services active
- No errors in logs

### quick-fix.sh success:
- Shows "SUCCESS! Site is working!"
- HTTP Response Code: 200 or 302
- All steps completed without errors
- Site accessible in browser

---

## What to Do If Scripts Don't Fix Issue

1. **Review diagnostic output**:
   ```bash
   bash diagnose.sh > diagnostic-report.txt
   cat diagnostic-report.txt
   ```

2. **Check specific logs**:
   ```bash
   # Laravel logs
   tail -100 storage/logs/laravel.log
   
   # Nginx logs
   tail -100 /var/log/nginx/error.log
   
   # PHP-FPM logs
   tail -100 /var/log/php8.3-fpm.log
   ```

3. **Verify .env configuration**:
   ```bash
   cat .env | grep APP_
   cat .env | grep DB_
   ```

4. **Test database connection**:
   ```bash
   php artisan tinker
   >>> DB::connection()->getPdo();
   >>> exit
   ```

5. **Check web server config**:
   ```bash
   nginx -t
   systemctl status nginx
   ```

6. **Refer to detailed guides**:
   - `PRODUCTION_FIX_GUIDE.md` - Comprehensive manual fix guide
   - `MANUAL_FIX_PRODUCTION.md` - Quick reference guide

---

## Additional Resources

- **Deployment Workflow**: `.github/workflows/deploy.yml`
- **Dashboard Controller**: `app/Http/Controllers/DashboardController.php`
- **Dashboard View**: `resources/views/dashboard/index.blade.php`
- **Routes**: `routes/web.php`

---

## Support

If you need help:
1. Run `bash diagnose.sh` and save output
2. Check Laravel logs: `tail -100 storage/logs/laravel.log`
3. Share the error message and diagnostic output
4. Refer to `PRODUCTION_FIX_GUIDE.md` for detailed solutions

---

**Last Updated**: February 25, 2026
**Scripts Version**: 1.0
**Tested On**: Ubuntu 20.04/22.04, PHP 8.3, Laravel 11
