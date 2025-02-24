<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        $permissions = [
            'view own patients',      // Doctor
            'book appointments',      // Patient
            'reschedule appointments', // Patient
            'cancel appointments',    // Patient
            'create doctors',         // Admin
            'create patients',        // Admin
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Define roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo(['create doctors', 'create patients']);

        $doctorRole = Role::firstOrCreate(['name' => 'doctor', 'guard_name' => 'web']);
        $doctorRole->givePermissionTo(['view own patients']);

        $patientRole = Role::firstOrCreate(['name' => 'patient', 'guard_name' => 'web']);
        $patientRole->givePermissionTo(['book appointments', 'reschedule appointments', 'cancel appointments']);
    }
}

