# Deployment Guide - Payroll PSF

## Prerequisites

### VPS Requirements
- Ubuntu 20.04+ or Debian 11+
- PHP 8.2+
- MySQL 8.0+ or MariaDB 10.6+
- Nginx or Apache
- Composer
- Node.js 18+
- Git

### GitHub Secrets Required
Add these secrets in your GitHub repository (Settings > Secrets and variables > Actions):

- `VPS_HOST`: Your VPS IP address or domain
- `VPS_USERNAME`: SSH username (usually `root` or your user)
- `VPS_SSH_KEY`: Private SSH key for authentication
- `VPS_PORT`: SSH port (default: 22)
- `VPS_DEPLOY_PATH`: Full path to deployment directory (e.g., `/var/www/payroll-psf`)

## Deployment Methods

### Method 1: GitHub Actions (Recommended)

1. **Setup GitHub Secrets** (as listed above)

2. **Push to main/master branch**
   ```bash
   git add .
   git commit -m "Deploy to production"
   git push origin main
   ```

3. **Monitor deployment**
   - Go to GitHub repository > Actions tab
   - Watch the deployment progress
   - Check for any errors

### Method 2: Manual Deployment

1. **Clone repository on VPS**
   ```bash
   cd /var/www
   git clone https://github.com/your-username/payroll-psf.git
   cd payroll-psf
   ```

2. **Copy and configure .env**
   ```bash
   cp .env.production.example .env
   nano .env
   ```
   
   Update these values:
   - `APP_URL`: Your domain
   - `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`: Database credentials
   - `APP_KEY`: Will be generated in next step

3. **Run deployment script**
   ```bash
   chmod +x deploy.sh
   ./deploy.sh
   ```

4. **Generate application key**
   ```bash
   php artisan key:generate
   ```

5. **Create first superadmin user**
   ```bash
   php artisan tinker
   ```
   Then run:
   ```php
   $role = App\Models\Role::where('is_superadmin', true)->first();
   $user = App\Models\User::create([
       'name' => 'Super Admin',
       'email' => 'admin@payroll-psf.com',
       'password' => bcrypt('password'),
       'role_id' => $role->id,
       'is_active' => true,
       'join_date' => now()
   ]);
   ```

## Server Configuration

### Nginx Configuration

Create file: `/etc/nginx/sites-available/payroll-psf`

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/payroll-psf/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable site:
```bash
ln -s /etc/nginx/sites-available/payroll-psf /etc/nginx/sites-enabled/
nginx -t
systemctl reload nginx
```

### SSL Certificate (Let's Encrypt)

```bash
apt install certbot python3-certbot-nginx
certbot --nginx -d your-domain.com
```

### File Permissions

```bash
cd /var/www/payroll-psf
chown -R www-data:www-data .
chmod -R 755 .
chmod -R 775 storage bootstrap/cache
```

## Database Setup

1. **Create database**
   ```bash
   mysql -u root -p
   ```
   ```sql
   CREATE DATABASE payroll_psf CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   CREATE USER 'payroll_user'@'localhost' IDENTIFIED BY 'strong_password';
   GRANT ALL PRIVILEGES ON payroll_psf.* TO 'payroll_user'@'localhost';
   FLUSH PRIVILEGES;
   EXIT;
   ```

2. **Run migrations**
   ```bash
   php artisan migrate --force
   php artisan db:seed --class=PermissionSeeder --force
   ```

## Post-Deployment

### 1. Test the application
- Visit your domain
- Login with superadmin credentials
- Test all features

### 2. Setup cron jobs
Add to crontab (`crontab -e`):
```bash
* * * * * cd /var/www/payroll-psf && php artisan schedule:run >> /dev/null 2>&1
```

### 3. Setup queue worker (optional)
Create systemd service: `/etc/systemd/system/payroll-worker.service`
```ini
[Unit]
Description=Payroll PSF Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/payroll-psf/artisan queue:work --sleep=3 --tries=3

[Install]
WantedBy=multi-user.target
```

Enable and start:
```bash
systemctl enable payroll-worker
systemctl start payroll-worker
```

### 4. Monitor logs
```bash
tail -f /var/www/payroll-psf/storage/logs/laravel.log
```

## Troubleshooting

### Permission Issues
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Clear all caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Database connection error
- Check `.env` database credentials
- Verify MySQL is running: `systemctl status mysql`
- Test connection: `mysql -u payroll_user -p payroll_psf`

### 500 Internal Server Error
- Check error logs: `tail -f storage/logs/laravel.log`
- Check Nginx error log: `tail -f /var/log/nginx/error.log`
- Verify file permissions

## Rollback

If deployment fails:
```bash
cd /var/www/payroll-psf
rm -f current
ln -s backup-YYYYMMDD-HHMMSS current
systemctl reload php8.2-fpm
systemctl reload nginx
```

## Security Checklist

- [ ] `APP_DEBUG=false` in production
- [ ] Strong `APP_KEY` generated
- [ ] Database credentials are secure
- [ ] SSL certificate installed
- [ ] Firewall configured (UFW)
- [ ] Regular backups scheduled
- [ ] File permissions set correctly
- [ ] `.env` file not in git
- [ ] Error reporting disabled in production

## Backup Strategy

### Database Backup
```bash
mysqldump -u payroll_user -p payroll_psf > backup-$(date +%Y%m%d).sql
```

### Full Backup
```bash
tar -czf payroll-backup-$(date +%Y%m%d).tar.gz /var/www/payroll-psf
```

### Automated Daily Backup
Add to crontab:
```bash
0 2 * * * /usr/bin/mysqldump -u payroll_user -pYOUR_PASSWORD payroll_psf > /backups/db-$(date +\%Y\%m\%d).sql
0 3 * * * tar -czf /backups/files-$(date +\%Y\%m\%d).tar.gz /var/www/payroll-psf/storage
```

## Support

For issues or questions:
- Check logs: `storage/logs/laravel.log`
- Review this guide
- Contact system administrator
