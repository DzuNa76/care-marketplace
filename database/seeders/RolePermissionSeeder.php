<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan cache permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat role utama aplikasi
        $roles = [
            'customer',
            'provider',
            'admin',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web',
            ]);
        }

        // Bersihkan kembali cache permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
