<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class AddMissingPermissionsSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // Acuan Gaji - Generate
            [
                'name' => 'Generate Acuan Gaji',
                'key' => 'acuan_gaji.generate',
                'action_type' => 'generate',
                'module' => 'acuan_gaji',
                'group' => 'payroll',
                'description' => 'Generate acuan gaji untuk periode tertentu'
            ],
            
            // Kasbon - Approve
            [
                'name' => 'Approve Kasbon',
                'key' => 'kasbon.approve',
                'action_type' => 'approve',
                'module' => 'kasbon',
                'group' => 'payroll',
                'description' => 'Approve pengajuan kasbon karyawan'
            ],
            
            // Kasbon - Reject
            [
                'name' => 'Reject Kasbon',
                'key' => 'kasbon.reject',
                'action_type' => 'reject',
                'module' => 'kasbon',
                'group' => 'payroll',
                'description' => 'Reject pengajuan kasbon karyawan'
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['key' => $permission['key']],
                $permission
            );
        }

        $this->command->info('Missing permissions added successfully!');
    }
}
