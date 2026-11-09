<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // =====================================
        // AKUN CUSTOMER TEST
        // =====================================

        $customer = User::updateOrCreate(
            [
                'email' => 'customer.test@caremarketplace.local',
            ],
            [
                'name' => 'Customer Test',
                'password' => Hash::make('CustomerTest123!'),
            ]
        );

        $customer->syncRoles(['customer']);


        // =====================================
        // AKUN PROVIDER TEST
        // =====================================

        $provider = User::updateOrCreate(
            [
                'email' => 'provider.test@caremarketplace.local',
            ],
            [
                'name' => 'Provider Test',
                'password' => Hash::make('CareTest!2026Local'),
            ]
        );

        $provider->syncRoles(['provider']);

        // Pastikan provider memiliki profil.
        $provider->providerProfile()->updateOrCreate(
            [],
            [
                'display_name' => 'Provider Test',
            ]
        );
    }
}
