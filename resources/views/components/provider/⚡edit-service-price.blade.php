
<?php

use App\Models\ProviderService;
use App\Models\ProviderServicePrice;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    public ?ProviderService $service = null;

    public ?ProviderServicePrice $pricePackage = null;

    public string $package_name = '';

    public ?int $duration_value = 1;

    public string $duration_unit = 'day';

    public ?int $price = null;

    public string $description = '';

    public bool $is_active = true;

    public function mount(
        ProviderService $service,
        int $price
    ): void {
        $providerProfile = Auth::user()->providerProfile;

        abort_unless($providerProfile, 403);

        $this->service = ProviderService::query()
            ->where('provider_profile_id', $providerProfile->id)
            ->findOrFail($service->id);

        $this->pricePackage = ProviderServicePrice::query()
            ->where('provider_service_id', $this->service->id)
            ->findOrFail($price);

        $this->package_name = $this->pricePackage->package_name;
        $this->duration_value = $this->pricePackage->duration_value;
        $this->duration_unit = $this->pricePackage->duration_unit;
        $this->price = $this->pricePackage->price;
        $this->description = $this->pricePackage->description ?? '';
        $this->is_active = $this->pricePackage->is_active;
    }

    public function update(): void
    {
        $providerProfile = Auth::user()->providerProfile;

        abort_unless($providerProfile, 403);

        $service = ProviderService::query()
            ->where('provider_profile_id', $providerProfile->id)
            ->findOrFail($this->service->id);

        $pricePackage = ProviderServicePrice::query()
            ->where('provider_service_id', $service->id)
            ->findOrFail($this->pricePackage->id);

        $validated = $this->validate([
            'package_name' => [
                'required',
                'string',
                'max:100',
            ],
            'duration_value' => [
                'required',
                'integer',
                'min:1',
                'max:3650',
            ],
            'duration_unit' => [
                'required',
                'in:day,week,month',
            ],
            'price' => [
                'required',
                'integer',
                'min:1',
                'max:999999999999',
            ],
            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],
            'is_active' => [
                'required',
                'boolean',
            ],
        ], [
            'package_name.required' => 'Nama paket wajib diisi.',
            'package_name.max' => 'Nama paket maksimal 100 karakter.',
            'duration_value.required' => 'Durasi wajib diisi.',
            'duration_value.integer' => 'Durasi harus berupa angka.',
            'duration_value.min' => 'Durasi minimal 1.',
            'duration_value.max' => 'Durasi maksimal 3650.',
            'duration_unit.required' => 'Satuan durasi wajib dipilih.',
            'duration_unit.in' => 'Satuan durasi tidak valid.',
            'price.required' => 'Harga wajib diisi.',
            'price.integer' => 'Harga harus berupa angka bulat.',
            'price.min' => 'Harga minimal Rp1.',
            'description.max' => 'Deskripsi maksimal 2000 karakter.',
        ]);

        $pricePackage->update([
            'package_name' => $validated['package_name'],
            'duration_value' => $validated['duration_value'],
            'duration_unit' => $validated['duration_unit'],
            'price' => $validated['price'],
            'description' => $validated['description'] ?: null,
            'is_active' => $validated['is_active'],
        ]);

        session()->flash('success', 'Paket harga berhasil diperbarui.');

        $this->redirectRoute(
            'provider.services.prices.index',
            ['service' => $service->id]
        );
    }

    public function render()
    {
        return view('components.provider.⚡edit-service-price');
    }
};
?>

<div class="min-h-screen bg-gray-100 py-10">
    <div class="mx-auto max-w-3xl px-4">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                Edit Paket Harga
            </h1>

            <p class="mt-1 text-sm text-gray-600">
                Layanan: <strong>{{ $service->title }}</strong>
            </p>

            <p class="text-sm text-gray-500">
                Paket: {{ $pricePackage->package_name }}
            </p>
        </div>

        {{-- Form --}}
        <div class="rounded-xl bg-white p-6 shadow-sm">

            <form wire:submit="update" class="space-y-5">

                {{-- Nama Paket --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Nama Paket
                    </label>

                    <input
                        type="text"
                        wire:model="package_name"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-blue-500 focus:outline-none"
                    >

                    @error('package_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Durasi --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Durasi
                    </label>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <input
                                type="number"
                                min="1"
                                wire:model="duration_value"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-blue-500 focus:outline-none"
                            >

                            @error('duration_value')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <select
                                wire:model="duration_unit"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-blue-500 focus:outline-none"
                            >
                                <option value="day">Hari</option>
                                <option value="week">Minggu</option>
                                <option value="month">Bulan</option>
                            </select>

                            @error('duration_unit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Harga --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Harga (Rupiah)
                    </label>

                    <input
                        type="number"
                        min="1"
                        wire:model="price"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-blue-500 focus:outline-none"
                    >

                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Deskripsi Paket
                    </label>

                    <textarea
                        wire:model="description"
                        rows="4"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-blue-500 focus:outline-none"
                    ></textarea>

                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                        <input
                            type="checkbox"
                            wire:model="is_active"
                            class="h-4 w-4 rounded border-gray-300 text-blue-600"
                        >

                        Aktifkan paket harga
                    </label>

                    @error('is_active')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol --}}
                <div class="flex flex-wrap justify-end gap-3 border-t border-gray-200 pt-5">

                    <a
                        href="{{ route('provider.services.prices.index', $service->id) }}"
                        class="rounded-lg bg-gray-200 px-5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-300"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-50"
                    >
                        <span wire:loading.remove wire:target="update">
                            Simpan Perubahan
                        </span>

                        <span wire:loading wire:target="update">
                            Menyimpan...
                        </span>
                    </button>

                </div>

            </form>

        </div>

    </div>
</div>
