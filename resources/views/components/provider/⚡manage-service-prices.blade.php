
<?php

use App\Models\ProviderService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    public ?ProviderService $service = null;

    public function mount(ProviderService $service): void
    {
        $providerProfile = Auth::user()->providerProfile;

        abort_unless($providerProfile, 403);

        $this->service = ProviderService::query()
            ->with(['category', 'prices'])
            ->where('provider_profile_id', $providerProfile->id)
            ->findOrFail($service->id);
    }

    public function deletePrice(int $priceId): void
    {
        $providerProfile = Auth::user()->providerProfile;

        abort_unless($providerProfile, 403);

        $service = ProviderService::query()
            ->where('provider_profile_id', $providerProfile->id)
            ->findOrFail($this->service->id);

        $price = $service->prices()
            ->findOrFail($priceId);

        $price->delete();

        session()->flash('success', 'Paket harga berhasil dihapus.');
    }

    public function render()
    {
        return view('components.provider.⚡manage-service-prices');
    }
};
?>

<div class="min-h-screen bg-gray-100 py-10">
    <div class="mx-auto max-w-6xl px-4">

        {{-- Header --}}
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Paket Harga
                </h1>

                <p class="mt-1 text-sm text-gray-600">
                    Kelola paket harga untuk layanan:
                    <strong>{{ $service->title }}</strong>
                </p>

                <p class="mt-1 text-sm text-gray-500">
                    Kategori: {{ $service->category->name }}
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a
                    href="{{ route('provider.services.index') }}"
                    class="rounded-lg bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-300"
                >
                    Kembali ke Layanan
                </a>

                <a
                    href="{{ route('provider.services.prices.create', $service->id) }}"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                >
                    + Tambah Paket
                </a>
            </div>
        </div>

        {{-- Daftar Paket Harga --}}
        <div class="rounded-xl bg-white p-6 shadow-sm">

            <div class="mb-5 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800">
                    Daftar Paket Harga
                </h2>

                <span class="text-sm text-gray-500">
                    {{ $service->prices->count() }} paket
                </span>
            </div>

            @forelse ($service->prices as $price)
                <div class="border-b border-gray-200 py-5 last:border-0">

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">

                        <div>
                            <h3 class="font-semibold text-gray-800">
                                {{ $price->package_name }}
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Durasi:
                                {{ $price->duration_value }}
                                {{ match ($price->duration_unit) {
                                    'day' => 'Hari',
                                    'week' => 'Minggu',
                                    'month' => 'Bulan',
                                    default => $price->duration_unit,
                                } }}
                            </p>

                            <p class="mt-2 text-lg font-bold text-blue-600">
                                Rp {{ number_format($price->price, 0, ',', '.') }}
                            </p>

                            <p class="mt-2 text-sm text-gray-600">
                                {{ $price->description ?: 'Tidak ada deskripsi.' }}
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">

                            <span class="rounded-full px-3 py-1 text-xs font-medium
                                {{ $price->is_active
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-gray-100 text-gray-600' }}">
                                {{ $price->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>

                            <a
                                href="{{ route('provider.services.prices.edit', [
                                    'service' => $service->id,
                                    'price' => $price->id,
                                ]) }}"
                                class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                            >
                                Edit
                            </a>

                            <button
                                type="button"
                                wire:click="deletePrice({{ $price->id }})"
                                wire:confirm="Apakah Anda yakin ingin menghapus paket harga {{ $price->package_name }}?"
                                class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700"
                            >
                                Hapus
                            </button>

                        </div>

                    </div>

                </div>
            @empty
                <div class="rounded-lg bg-gray-50 p-8 text-center">
                    <p class="font-medium text-gray-700">
                        Belum ada paket harga.
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Tambahkan paket harga untuk layanan ini.
                    </p>
                </div>
            @endforelse

        </div>

    </div>
</div>
