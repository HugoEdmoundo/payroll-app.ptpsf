#!/bin/bash
# Quick Fix for Production 500 Error
# Usage: bash quick-fix.sh

set -e  # Exit on error

echo "🚨 Quick Fix for Production 500 Error"
echo "======================================"
echo ""

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
  echo "⚠️  Please run as root: sudo bash quick-fix.sh"
  exit 1
fi

# Navigate to project directory
cd /opt/just-atesting || { echo "❌ Directory /opt/just-atesting not found"; exit 1; }

echo "📋 Current directory: $(pwd)"
echo ""

# Step 1: Check Laravel logs first
echo "📄 Checking Laravel logs..."
if [ -f storage/logs/laravel.log ]; then
  echo "Last 20 lines of Laravel log:"
  tail -20 storage/logs/laravel.log
  echo ""
else
  echo "⚠️  No Laravel log found"
fi

# Step 2: Check if dashboard view exists
echo "📁 Checking dashboard view..."
if [ -f resources/views/dashboard/index.blade.php ]; then
  echo "✅ Dashboard view exists"
else
  echo "❌ Dashboard view missing! Creating..."
  mkdir -p resources/views/dashboard
  
  cat > resources/views/dashboard/index.blade.php << 'EOF'
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
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Recent Karyawan</h2>
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
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $k->nama_karyawan }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $k->jabatan }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $k->status_karyawan === 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
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
EOF
  echo "✅ Dashboard view created"
fi
echo ""

# Step 3: Run migrations
echo "🗄️  Running migrations..."
php artisan migrate --force 2>&1 || echo "⚠️  Migration warning (might be already up to date)"
echo ""

# Step 4: Dump autoload
echo "📚 Dumping autoload..."
composer dump-autoload --optimize 2>&1 || echo "⚠️  Composer autoload warning"
echo ""

# Step 5: Clear all caches
echo "🧹 Clearing all caches..."
php artisan optimize:clear 2>&1 || true
php artisan cache:clear 2>&1 || true
php artisan config:clear 2>&1 || true
php artisan route:clear 2>&1 || true
php artisan view:clear 2>&1 || true
echo "✅ Caches cleared"
echo ""

# Step 6: Rebuild caches
echo "💾 Rebuilding caches..."
php artisan config:cache 2>&1 || echo "⚠️  Config cache warning"
php artisan route:cache 2>&1 || echo "⚠️  Route cache warning"
php artisan view:cache 2>&1 || echo "⚠️  View cache warning"
echo "✅ Caches rebuilt"
echo ""

# Step 7: Fix permissions
echo "🔐 Fixing permissions..."
chown -R www-data:www-data storage bootstrap/cache 2>&1 || echo "⚠️  Permission warning"
chmod -R 775 storage bootstrap/cache 2>&1 || echo "⚠️  Chmod warning"
echo "✅ Permissions fixed"
echo ""

# Step 8: Restart PHP-FPM
echo "🔄 Restarting PHP-FPM..."
if systemctl restart php8.3-fpm 2>&1; then
  echo "✅ PHP 8.3 FPM restarted"
elif systemctl restart php-fpm 2>&1; then
  echo "✅ PHP-FPM restarted"
else
  echo "⚠️  Could not restart PHP-FPM"
fi
echo ""

# Step 9: Check services status
echo "🔍 Checking services..."
echo "PHP-FPM Status:"
systemctl status php8.3-fpm --no-pager -l 2>&1 | head -5 || systemctl status php-fpm --no-pager -l 2>&1 | head -5
echo ""
echo "Nginx Status:"
systemctl status nginx --no-pager -l 2>&1 | head -5
echo ""

# Step 10: Test site
echo "🌐 Testing site..."
sleep 3
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" https://just-atesting.hugoedm.fun 2>&1 || echo "000")
echo "HTTP Response Code: $HTTP_CODE"
echo ""

# Final report
echo "======================================"
if [ "$HTTP_CODE" = "200" ] || [ "$HTTP_CODE" = "302" ]; then
  echo "✅ SUCCESS! Site is working!"
  echo "🌐 Visit: https://just-atesting.hugoedm.fun"
else
  echo "⚠️  Site returned HTTP $HTTP_CODE"
  echo ""
  echo "📋 Next steps:"
  echo "1. Check Laravel logs:"
  echo "   tail -50 storage/logs/laravel.log"
  echo ""
  echo "2. Check Nginx error logs:"
  echo "   tail -50 /var/log/nginx/error.log"
  echo ""
  echo "3. Check PHP-FPM logs:"
  echo "   tail -50 /var/log/php8.3-fpm.log"
  echo ""
  echo "4. Test database connection:"
  echo "   php artisan tinker"
  echo "   >>> DB::connection()->getPdo();"
fi
echo "======================================"
