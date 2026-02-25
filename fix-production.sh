#!/bin/bash

# Script untuk fix production error
# Jalankan di server: bash fix-production.sh

echo "🔧 Fixing Production Error..."

cd /opt/just-atesting || exit 1

# Clear all caches
echo "📦 Clearing caches..."
php artisan optimize:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

# Rebuild autoload
echo "🔄 Rebuilding autoload..."
composer dump-autoload --optimize --no-interaction

# Check if dashboard view exists, if not create fallback
echo "🔍 Checking dashboard view..."
if [ ! -f "resources/views/dashboard/index.blade.php" ]; then
    echo "⚠️  Dashboard view missing! Creating fallback..."
    mkdir -p resources/views/dashboard
    cat > resources/views/dashboard/index.blade.php << 'EOF'
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
fi

# Run migrations
echo "🗄️  Running migrations..."
php artisan migrate --force 2>/dev/null || echo "⚠️  Migration failed or already up to date"

# Rebuild caches
echo "💾 Rebuilding caches..."
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true

# Fix permissions
echo "🔐 Fixing permissions..."
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

# Restart PHP-FPM if available
echo "🔄 Restarting PHP-FPM..."
systemctl restart php8.3-fpm 2>/dev/null || systemctl restart php-fpm 2>/dev/null || echo "⚠️  Could not restart PHP-FPM"

echo ""
echo "✅ Production fix completed!"
echo "🌐 Please test: https://just-atesting.hugoedm.fun"
echo ""
echo "If still error, check logs:"
echo "  tail -f storage/logs/laravel.log"

