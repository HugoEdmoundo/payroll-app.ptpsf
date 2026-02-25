#!/bin/bash

# Script untuk fix production error
# Jalankan di server: bash fix-production.sh

echo "🔧 Fixing Production Error..."

cd /opt/just-atesting

# Clear all caches
echo "📦 Clearing caches..."
php artisan optimize:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild autoload
echo "🔄 Rebuilding autoload..."
composer dump-autoload --optimize

# Rebuild caches
echo "💾 Rebuilding caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Fix permissions
echo "🔐 Fixing permissions..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Check if dashboard view exists
if [ ! -f "resources/views/dashboard/index.blade.php" ]; then
    echo "❌ Dashboard view missing! Creating..."
    mkdir -p resources/views/dashboard
    # View will be created by next deployment
fi

echo "✅ Production fix completed!"
echo "🌐 Please test: https://just-atesting.hugoedm.fun"
