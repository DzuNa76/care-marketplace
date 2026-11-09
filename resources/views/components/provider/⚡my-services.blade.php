
<?php

use App\Models\ProviderService;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    public function getServicesProperty()
    {
        $providerProfile = Auth::user()->providerProfile;

        abort_unless($providerProfile, 403);

        return ProviderService::query()
            ->with(['category', 'prices', 'skills'])
            ->where('provider_profile_id', $providerProfile->id)
            ->latest()
            ->get();
    }

    public function getCategoriesProperty()
    {
        return ServiceCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function render()
    {
        return view('components.provider.⚡my-services');
    }
};
?>

<div class="min-h-screen bg-gray-100 py-10">
    <div class="mx-auto max-w-6xl px-4">

        {{-- Header --}}
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Layanan Saya
                </h1>

                <p class="mt-1 text-sm text-gray-600">
                    Kelola layanan yang Anda tawarkan.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">

                {{-- Tombol Tambah Layanan --}}
                <a
                    href="{{ route('provider.services.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700"
                >
                    <span class="text-lg leading-none">+</span>
                    Tambah Layanan
                </a>

                {{-- Kembali ke Dashboard --}}
                <a
                    href="{{ route('provider.dashboard') }}"
                    class="rounded-lg bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-300"
                >
                    Kembali ke Dashboard
                </a>

            </div>
        </div>

        {{-- Daftar Layanan --}}
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800">
                    Daftar Layanan
                </h2>

                <span class="text-sm text-gray-500">
                    {{ $this->services->count() }} layanan
                </span>
            </div>

            @forelse ($this->services as $service)
                <div class="border-b border-gray-200 py-5 last:border-0">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-800">
                                {{ $service->title }}
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                {{ $service->category->name }}
                            </p>

                            <p class="mt-2 text-sm text-gray-600">
                                {{ $service->description ?: 'Belum ada deskripsi.' }}
                            </p>

                            <p class="mt-2 text-sm text-gray-600">
                                Pengalaman:
                                {{ $service->experience_years ?? 0 }} tahun
                            </p>
                        </div>

                        <span class="rounded-full px-3 py-1 text-xs font-medium
                            {{ $service->is_active
                                ? 'bg-green-100 text-green-700'
                                : 'bg-gray-100 text-gray-600' }}">
                            {{ $service->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>

                    <div class="mt-3 text-sm text-gray-600">
                        <strong>Keahlian:</strong>

                        {{ $service->skills->pluck('name')->join(', ') ?: 'Belum dipilih' }}
                    </div>

                    <div class="mt-2 text-sm text-gray-600">
                        <strong>Paket harga:</strong>

                        {{ $service->prices->count() }} paket
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <a
                            href="{{ route('provider.services.prices.index', $service->id) }}"
                            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                        >
                            Kelola Paket Harga
                        </a>

                        <a
                            href="{{ route('provider.services.edit', $service->id) }}"
                            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                        >
                            Edit Layanan
                        </a>
                    </div>
                </div>
            @empty
                <div class="rounded-lg bg-gray-50 p-8 text-center">
                    <p class="font-medium text-gray-700">
                        Belum ada layanan.
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Anda belum menambahkan layanan.
                    </p>
                </div>
            @endforelse
        </div>

    </div>
</div>
