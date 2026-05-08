# Supabase Migration Guide

## Current Status ✅
- **Application**: Running successfully on SQLite (http://127.0.0.1:8000)
- **Migrations**: All 43 migrations completed successfully
- **Database**: Fully functional with complete schema
- **Functionality**: Application tested and working

## Supabase Migration Steps

### Issue Identified
The Supabase hostname `db.anoqfwtwrhrqidmtvllt.supabase.co` has network connectivity issues. This needs to be resolved before migration.

### Solutions

#### Option 1: Fix Supabase Connectivity
1. **Check Supabase Dashboard**
   - Log into your Supabase project
   - Verify project is active (not paused)
   - Get updated connection string from Settings → Database

2. **Update Credentials**
   Replace the hostname if it has changed:
   ```bash
   php migrate-to-supabase.php
   ```

#### Option 2: Use Alternative Supabase Project
If the current project has issues:
1. Create a new Supabase project
2. Update credentials in `migrate-to-supabase.php`
3. Run the migration script

#### Option 3: Network Troubleshooting
If you believe the credentials are correct:
1. Check if your network blocks IPv6 connections
2. Try using a VPN
3. Test from a different network

### Migration Script Usage
Once connectivity is resolved:

```bash
# Run the complete migration
php migrate-to-supabase.php
```

This script will:
- Update `.env` with Supabase credentials
- Test database connection
- Run all migrations
- Verify installation

### Manual Migration (Alternative)
If the script fails, you can migrate manually:

```bash
# 1. Update .env file manually
DB_CONNECTION=pgsql
DB_HOST=your_supabase_host.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your_password

# 2. Clear and cache config
php artisan config:clear
php artisan config:cache

# 3. Test connection
php artisan db:show

# 4. Run migrations
php artisan migrate --force
```

## Database Schema
The application includes 43 migration files creating:
- User management system
- Employee data (karyawan)
- Payroll components (komponen gaji)
- Attendance tracking
- Loan management (kasbon)
- Payroll calculations
- Reports and slips

## Next Steps After Migration
1. Create admin user account
2. Configure company settings
3. Add employee data
4. Set up payroll components
5. Test payroll calculations

## Support
If you continue to have connectivity issues:
1. Verify Supabase project status
2. Check network connectivity
3. Try alternative connection methods
4. Consider using a different Supabase region
