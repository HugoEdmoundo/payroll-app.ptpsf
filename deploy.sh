#!/bin/bash

# Deployment script for Payroll PSF
# Usage: ./deploy.sh

set -e

echo "🚀 Starting deployment..."

# Check if .env exists
if [ ! -f .env ]; then
    echo "❌ Error: .env file not found!"
    echo "Please copy .env.example to .env and configure it."
    exit 1
fi

# Install dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "📦 Installing NPM dependencies..."
npm ci

# Build assets
echo "🔨 Building assets..."
npm run build

# Set permissions
echo "🔐 Setting permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Run migrations
echo "🗄️  Running migrations..."
php artisan migrate --force

# Seed permissions if needed
echo "🌱 Seeding permissions..."
php artisan db:seed --class=PermissionSeeder --force

# Clear and optimize
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo "⚡ Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link if not exists
if [ ! -L public/storage ]; then
    echo "🔗 Creating storage link..."
    php artisan storage:link
fi

echo "✅ Deployment completed successfully!"
echo ""
echo "📝 Next steps:"
echo "1. Make sure your web server is configured correctly"
echo "2. Point document root to: $(pwd)/public"
echo "3. Ensure PHP-FPM is running"
echo "4. Check logs if any issues: storage/logs/laravel.log"
