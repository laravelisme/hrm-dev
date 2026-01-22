<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cache permission
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // List permission
        $permissions = [
            'worktime.view',
            'worktime.update',

            'employee.view',
            'employee.create',
            'employee.update',
            'employee.delete',
        ];

        // Create permission
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Role HR (FULL ACCESS)
        $hr = Role::firstOrCreate(['name' => 'hr']);
        $hr->syncPermissions(Permission::all());

        // Role Admin (ONLY WORKTIME)
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions([
            'worktime.view',
            'worktime.update',
        ]);
    }
}
