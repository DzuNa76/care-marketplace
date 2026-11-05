<?php

use App\Models\ProviderService;
use App\Models\ServiceCategory;
use App\Models\Skill;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

new class extends Component
{
    public ?int $service_category_id = null;

    public string $title = '';

    public string $description = '';

    public ?int $experience_years = null;

    public array $selectedSkills = [];

    public function getCategoriesProperty()
    {
        return ServiceCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function getSkillsProperty()
    {
        if (! $this->service_category_id) {
            return collect();
        }

        return Skill::query()
            ->where('service_category_id', $this->service_category_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function updatedServiceCategoryId(): void
    {
        // Reset keahlian ketika kategori diganti.
        $this->selectedSkills = [];
    }

    public function save(): void
    {
        $providerProfile = Auth::user()->providerProfile;

        abort_unless($providerProfile, 403);

        $validated = $this->validate([
            'service_category_id' => [
                'required',
                'integer',
                Rule::exists('service_categories', 'id')
                    ->where('is_active', true),
            ],
            'title' => [
                'required',
                'string',
                'max:150',
            ],
            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],
            'experience_years' => [
                'nullable',
                'integer',
                'min:0',
                'max:60',
            ],
            'selectedSkills' => [
                'nullable',
                'array',
            ],
            'selectedSkills.*' => [
                'integer',
                'distinct',
                Rule::exists('skills', 'id')
                    ->where('service_category_id', $this->service_category_id)
                    ->where('is_active', true),
            ],
        ], [
            'service_category_id.required' => 'Kategori layanan wajib dipilih.',
            'service_category_id.exists' => 'Kategori layanan tidak valid.',
            'title.required' => 'Nama layanan wajib diisi.',
            'title.max' => 'Nama layanan maksimal 150 karakter.',
            'description.max' => 'Deskripsi maksimal 2000 karakter.',
            'experience_years.integer' => 'Pengalaman harus berupa angka.',
            'experience_years.min' => 'Pengalaman tidak boleh negatif.',
            'experience_years.max' => 'Pengalaman maksimal 60 tahun.',
            'selectedSkills.*.exists' => 'Keahlian tidak sesuai dengan kategori layanan.',
        ]);

        $service = ProviderService::create([
            'provider_profile_id' => $providerProfile->id,
            'service_category_id' => $validated['service_category_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'experience_years' => $validated['experience_years'] ?? null,
            'is_active' => false,
        ]);

        $service->skills()->sync(
            $validated['selectedSkills'] ?? []
        );

        session()->flash(
            'success',
            'Layanan berhasil ditambahkan. Silakan lengkapi paket harga sebelum mengaktifkannya.'
        );

        $this->redirectRoute('provider.services.index');
    }
};
?>

<div class="min-h-screen bg-gray-100 py-10">
    <div class="mx-auto max-w-3xl px-4">

        <div class="mb-6">
            <a
                href="{{ route('provider.services.index') }}"
                class="text-sm font-medium text-blue-600 hover:underline"
            >
                &larr; Kembali ke Layanan Saya
            </a>

            <h1 class="mt-3 text-2xl font-bold text-gray-800">
                Tambah Layanan
            </h1>

            <p class="mt-1 text-sm text-gray-600">
                Isi informasi layanan yang ingin Anda tawarkan.
            </p>
        </div>

        @if (session('success'))
            <div class="mb-5 rounded-lg bg-green-100 p-4 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <form
            wire:submit="save"
            class="space-y-6 rounded-xl bg-white p-6 shadow-sm"
        >
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">
                    Kategori Layanan *
                </label>

                <select
                    wire:model.live="service_category_id"
                    class="w-full rounded-lg border border-gray-300 p-3"
                >
                    <option value="">Pilih kategori layanan</option>

                    @foreach ($this->categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                @error('service_category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">
                    Nama Layanan *
                </label>

                <input
                    type="text"
                    wire:model="title"
                    maxlength="150"
                    placeholder="Contoh: Pendampingan Lansia"
                    class="w-full rounded-lg border border-gray-300 p-3"
                >

                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">
                    Deskripsi Layanan
                </label>

                <textarea
                    wire:model="description"
                    rows="4"
                    maxlength="2000"
                    placeholder="Jelaskan layanan yang Anda tawarkan..."
                    class="w-full rounded-lg border border-gray-300 p-3"
                ></textarea>

                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">
                    Pengalaman pada Layanan Ini (Tahun)
                </label>

                <input
                    type="number"
                    wire:model="experience_years"
                    min="0"
                    max="60"
                    class="w-full rounded-lg border border-gray-300 p-3"
                >

                @error('experience_years')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">
                    Keahlian
                </label>

                @if ($this->skills->isNotEmpty())
                    <div class="space-y-3 rounded-lg border border-gray-200 p-4">
                        @foreach ($this->skills as $skill)
                            <label class="flex items-center gap-3">
                                <input
                                    type="checkbox"
                                    wire:model="selectedSkills"
                                    value="{{ $skill->id }}"
                                    class="rounded border-gray-300"
                                >

                                <span class="text-sm text-gray-700">
                                    {{ $skill->name }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                @elseif ($service_category_id)
                    <p class="text-sm text-gray-500">
                        Belum ada keahlian untuk kategori ini.
                    </p>
                @else
                    <p class="text-sm text-gray-500">
                        Pilih kategori terlebih dahulu untuk melihat keahlian.
                    </p>
                @endif

                @error('selectedSkills')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @error('selectedSkills.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="rounded-lg bg-yellow-50 p-4 text-sm text-yellow-800">
                Layanan baru akan disimpan dalam status nonaktif.
                Paket harga perlu dilengkapi sebelum layanan dapat diaktifkan.
            </div>

            <div class="flex justify-end gap-3">
                <a
                    href="{{ route('provider.services.index') }}"
                    class="rounded-lg bg-gray-200 px-5 py-3 text-sm font-medium text-gray-700"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="rounded-lg bg-blue-600 px-5 py-3 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50"
                >
                    <span wire:loading.remove wire:target="save">
                        Simpan Layanan
                    </span>

                    <span wire:loading wire:target="save">
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
