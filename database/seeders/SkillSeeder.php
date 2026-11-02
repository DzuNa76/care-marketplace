<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use App\Models\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            'caregiver' => [
                'Pendampingan Lansia',
                'Bantuan Aktivitas Sehari-hari',
                'Bantuan Mobilitas',
                'Pendampingan Pasien',
                'Pendampingan Kontrol Kesehatan',
                'Bantuan Makan dan Minum',
                'Pendampingan Istirahat',
            ],

            'babysitter' => [
                'Perawatan Bayi',
                'Pengasuhan Anak',
                'Memandikan Bayi dan Anak',
                'Menyiapkan Makanan Anak',
                'Pendampingan Bermain',
                'Pendampingan Belajar',
                'Perawatan Kebersihan Anak',
            ],

            'perawat' => [
                'Pemeriksaan Tanda Vital',
                'Perawatan Luka',
                'Pemantauan Kondisi Pasien',
                'Perawatan Pascaoperasi',
                'Pendampingan Pasien di Rumah',
                'Edukasi Kesehatan Pasien',
                'Perawatan Dasar Pasien',
            ],

            'cleaning-service' => [
                'Membersihkan Kamar',
                'Membersihkan Kamar Mandi',
                'Membersihkan Dapur',
                'Menyapu dan Mengepel',
                'Membersihkan Kaca',
                'Mencuci Pakaian',
                'Menyetrika Pakaian',
                'Membersihkan Perabot Rumah',
            ],
        ];

        foreach ($skills as $categorySlug => $skillNames) {
            $category = ServiceCategory::where('slug', $categorySlug)
                ->firstOrFail();

            foreach ($skillNames as $skillName) {
                Skill::updateOrCreate(
                    [
                        'service_category_id' => $category->id,
                        'slug' => Str::slug($skillName),
                    ],
                    [
                        'name' => $skillName,
                        'description' => null,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
