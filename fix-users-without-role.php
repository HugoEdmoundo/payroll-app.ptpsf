<?php

/**
 * Script untuk assign role ke user yang belum punya role
 * 
 * Cara pakai:
 * php fix-users-without-role.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Role;

echo "=== Fix Users Without Role ===\n\n";

// Cek user yang belum punya role
$usersWithoutRole = User::whereNull('role_id')->get();
echo "Found " . $usersWithoutRole->count() . " users without role\n\n";

if ($usersWithoutRole->count() === 0) {
    echo "All users already have roles! ✓\n";
    exit(0);
}

// Tampilkan user yang belum punya role
echo "Users without role:\n";
foreach ($usersWithoutRole as $user) {
    echo "  - {$user->email} ({$user->name})\n";
}
echo "\n";

// Ambil role default (User)
$defaultRole = Role::where('is_default', true)->first();

// Jika tidak ada role default, ambil role pertama yang bukan superadmin
if (!$defaultRole) {
    echo "No default role found. Looking for non-superadmin role...\n";
    $defaultRole = Role::where('is_superadmin', false)->first();
}

// Jika masih tidak ada role, buat role default
if (!$defaultRole) {
    echo "No suitable role found. Creating default 'User' role...\n";
    $defaultRole = Role::create([
        'name' => 'User',
        'description' => 'Default user role',
        'is_superadmin' => false,
        'is_default' => true
    ]);
    echo "Default role created! ✓\n\n";
}

echo "Will assign role: '{$defaultRole->name}' (ID: {$defaultRole->id})\n";
echo "Proceed? (y/n): ";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);
if(trim($line) != 'y'){
    echo "Cancelled.\n";
    exit(0);
}
fclose($handle);

echo "\nAssigning roles...\n";

// Assign role ke semua user yang belum punya role
foreach ($usersWithoutRole as $user) {
    $user->role_id = $defaultRole->id;
    $user->save();
    echo "  ✓ Assigned role '{$defaultRole->name}' to: {$user->email}\n";
}

echo "\nDone! All users now have roles.\n";
echo "\nYou can now login and the error should be gone.\n";
