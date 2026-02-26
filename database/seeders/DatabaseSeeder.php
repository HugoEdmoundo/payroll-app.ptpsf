<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * MINIMAL SEEDER - Only for Authentication
     * Focus: User can login without errors
     * 
     * Order:
     * 1. Roles (Superadmin, User)
     * 2. Permissions (All modules)
     * 3. Users (superadmin@ptpsf.com, user@ptpsf.com)
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting MINIMAL database seeding (Auth only)...');
        $this->command->newLine();
        
        // 1. Roles
        $this->command->info('� Step 1: Roles');
        $this->call(RoleSeeder::class);
        $this->command->newLine();
        
        // 2. Permissions
        $this->command->info('� Step 2: Permissions');
        $this->call(PermissionSeeder::class);
        $this->command->newLine();
        
        // 3. Users
        $this->command->info('👥 Step 3: Users');
        $this->call(UserSeeder::class);
        $this->command->newLine();
        
        $this->command->info('✅ Minimal seeding completed!');
        $this->command->info('📝 You can now login with:');
        $this->command->info('   - superadmin@ptpsf.com / password123');
        $this->command->info('   - user@ptpsf.com / password123');
        $this->command->newLine();
        $this->command->warn('⚠️  Master data (Karyawan, Gaji, etc) not seeded yet.');
        $this->command->info('💡 Run individual seeders manually when needed.');
    }
}
