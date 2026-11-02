<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Caregiver',
                'slug' => 'caregiver',
                'description' => 'Layanan pendampingan dan bantuan perawatan bagi lansia atau individu yang membutuhkan bantuan aktivitas sehari-hari.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Babysitter',
                'slug' => 'babysitter',
                'description' => 'Layanan pengasuhan dan pendampingan bayi serta anak.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Perawat',
                'slug' => 'perawat',
                'description' => 'Layanan keperawatan oleh tenaga yang memenuhi persyaratan kompetensi dan legalitas profesi.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Cleaning Service',
                'slug' => 'cleaning-service',
                'description' => 'Layanan kebersihan rumah dan lingkungan tempat tinggal.',
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $category) {
            ServiceCategory::updateOrCreate(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'is_active' => true,
                    'sort_order' => $category['sort_order'],
                ]
            );
        }
    }
}
