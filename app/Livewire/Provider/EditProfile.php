<?php

namespace App\Livewire\Provider;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts::app')]
class EditProfile extends Component
{
    public string $display_name = '';

    public string $phone = '';

    public string $bio = '';

    public ?int $experience_years = null;

    public function mount(): void
    {
        $providerProfile = Auth::user()->providerProfile;

        abort_unless(
            $providerProfile,
            403,
            'Profil provider belum tersedia.'
        );

        $this->display_name = $providerProfile->display_name ?? '';
        $this->phone = $providerProfile->phone ?? '';
        $this->bio = $providerProfile->bio ?? '';
        $this->experience_years = $providerProfile->experience_years;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'display_name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'experience_years' => [
                'nullable',
                'integer',
                'min:0',
                'max:60',
            ],
        ], [
            'display_name.required' => 'Nama tampilan wajib diisi.',
            'display_name.max' => 'Nama tampilan maksimal 100 karakter.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'bio.max' => 'Bio maksimal 1000 karakter.',
            'experience_years.integer' => 'Pengalaman harus berupa angka.',
            'experience_years.min' => 'Pengalaman tidak boleh negatif.',
            'experience_years.max' => 'Pengalaman maksimal 60 tahun.',
        ]);

        $providerProfile = Auth::user()->providerProfile;

        abort_unless($providerProfile, 403);

        $providerProfile->update($validated);

        session()->flash(
            'success',
            'Profil provider berhasil diperbarui.'
        );
    }

    public function render()
    {
        return view('livewire.provider.edit-profile');
    }
}
