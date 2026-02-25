#!/bin/bash
# Diagnostic Script for Production Issues
# Usage: bash diagnose.sh

echo "🔍 Production Diagnostic Report"
echo "================================"
echo ""
echo "📅 Date: $(date)"
echo "🖥️  Server: $(hostname)"
echo ""

# Check if running in correct directory
if [ ! -f artisan ]; then
  echo "❌ Not in Laravel project directory!"
  echo "Please run from /opt/just-atesting"
  exit 1
fi

echo "📂 Current Directory: $(pwd)"
echo ""

# 1. Check PHP Version
echo "1️⃣  PHP Version:"
php -v | head -1
echo ""

# 2. Check Laravel Version
echo "2️⃣  Laravel Version:"
php artisan --version 2>&1 || echo "❌ Cannot run artisan"
echo ""

# 3. Check .env file
echo "3️⃣  Environment Configuration:"
if [ -f .env ]; then
  echo "✅ .env file exists"
  echo "APP_ENV: $(grep APP_ENV .env | cut -d '=' -f2)"
  echo "APP_DEBUG: $(grep APP_DEBUG .env | cut -d '=' -f2)"
  echo "APP_KEY: $(grep APP_KEY .env | cut -d '=' -f2 | cut -c1-20)..."
  echo "DB_CONNECTION: $(grep DB_CONNECTION .env | cut -d '=' -f2)"
  echo "DB_DATABASE: $(grep DB_DATABASE .env | cut -d '=' -f2)"
else
  echo "❌ .env file not found!"
fi
echo ""

# 4. Check critical files
echo "4️⃣  Critical Files:"
FILES=(
  "app/Http/Controllers/DashboardController.php"
  "resources/views/dashboard/index.blade.php"
  "resources/views/layouts/app.blade.php"
  "routes/web.php"
  "composer.json"
)

for file in "${FILES[@]}"; do
  if [ -f "$file" ]; then
    echo "✅ $file"
  else
    echo "❌ $file (MISSING!)"
  fi
done
echo ""

# 5. Check storage permissions
echo "5️⃣  Storage Permissions:"
ls -ld storage/ | awk '{print "storage/: " $1 " " $3 ":" $4}'
ls -ld storage/logs/ | awk '{print "storage/logs/: " $1 " " $3 ":" $4}' 2>&1 || echo "❌ storage/logs/ not found"
ls -ld bootstrap/cache/ | awk '{print "bootstrap/cache/: " $1 " " $3 ":" $4}' 2>&1 || echo "❌ bootstrap/cache/ not found"
echo ""

# 6. Check Laravel logs
echo "6️⃣  Laravel Logs (Last 30 lines):"
if [ -f storage/logs/laravel.log ]; then
  echo "----------------------------------------"
  tail -30 storage/logs/laravel.log
  echo "----------------------------------------"
else
  echo "⚠️  No Laravel log file found"
fi
echo ""

# 7. Check database connection
echo "7️⃣  Database Connection:"
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Connected to: ' . DB::connection()->getDatabaseName() . PHP_EOL; } catch (Exception \$e) { echo 'Error: ' . \$e->getMessage() . PHP_EOL; }" 2>&1
echo ""

# 8. Check routes
echo "8️⃣  Routes (First 10):"
php artisan route:list 2>&1 | head -15 || echo "❌ Cannot list routes"
echo ""

# 9. Check services
echo "9️⃣  Services Status:"
echo "PHP-FPM:"
systemctl is-active php8.3-fpm 2>&1 || systemctl is-active php-fpm 2>&1 || echo "❌ PHP-FPM not running"
echo "Nginx:"
systemctl is-active nginx 2>&1 || echo "❌ Nginx not running"
echo ""

# 10. Test site
echo "🔟 Site Accessibility:"
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" https://just-atesting.hugoedm.fun 2>&1 || echo "000")
echo "HTTP Response Code: $HTTP_CODE"

if [ "$HTTP_CODE" = "200" ] || [ "$HTTP_CODE" = "302" ]; then
  echo "✅ Site is accessible"
else
  echo "❌ Site returned error code"
fi
echo ""

# 11. Check Nginx error logs
echo "1️⃣1️⃣  Nginx Error Logs (Last 20 lines):"
if [ -f /var/log/nginx/error.log ]; then
  echo "----------------------------------------"
  tail -20 /var/log/nginx/error.log
  echo "----------------------------------------"
else
  echo "⚠️  No Nginx error log found"
fi
echo ""

# 12. Check PHP-FPM error logs
echo "1️⃣2️⃣  PHP-FPM Error Logs (Last 20 lines):"
if [ -f /var/log/php8.3-fpm.log ]; then
  echo "----------------------------------------"
  tail -20 /var/log/php8.3-fpm.log
  echo "----------------------------------------"
elif [ -f /var/log/php-fpm.log ]; then
  echo "----------------------------------------"
  tail -20 /var/log/php-fpm.log
  echo "----------------------------------------"
else
  echo "⚠️  No PHP-FPM error log found"
fi
echo ""

# Summary
echo "================================"
echo "📊 Diagnostic Summary"
echo "================================"

ISSUES=0

# Check critical issues
if [ ! -f .env ]; then
  echo "❌ Missing .env file"
  ((ISSUES++))
fi

if [ ! -f resources/views/dashboard/index.blade.php ]; then
  echo "❌ Missing dashboard view"
  ((ISSUES++))
fi

if [ "$HTTP_CODE" != "200" ] && [ "$HTTP_CODE" != "302" ]; then
  echo "❌ Site not accessible (HTTP $HTTP_CODE)"
  ((ISSUES++))
fi

if ! systemctl is-active --quiet php8.3-fpm && ! systemctl is-active --quiet php-fpm; then
  echo "❌ PHP-FPM not running"
  ((ISSUES++))
fi

if ! systemctl is-active --quiet nginx; then
  echo "❌ Nginx not running"
  ((ISSUES++))
fi

if [ $ISSUES -eq 0 ]; then
  echo "✅ No critical issues found"
  echo ""
  echo "If site still shows 500 error, check:"
  echo "1. Laravel logs above for specific errors"
  echo "2. Run: bash quick-fix.sh"
else
  echo ""
  echo "⚠️  Found $ISSUES critical issue(s)"
  echo ""
  echo "Recommended action:"
  echo "  bash quick-fix.sh"
fi

echo "================================"
