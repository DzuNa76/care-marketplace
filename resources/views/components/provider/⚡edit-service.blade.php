
<?php

use App\Models\ProviderService;
use App\Models\ServiceCategory;
use App\Models\Skill;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

new class extends Component
{
    public ?int $serviceId = null;

    public ?int $service_category_id = null;

    public string $title = '';

    public string $description = '';

    public ?int $experience_years = null;

    public array $selectedSkills = [];

    public function mount(ProviderService $service): void
    {
        $providerProfile = Auth::user()->providerProfile;

        abort_unless($providerProfile, 403);

        // Pastikan layanan merupakan milik provider yang login.
        abort_unless(
            $service->provider_profile_id === $providerProfile->id,
            404
        );

        $this->serviceId = $service->id;

        // Isi form dengan data layanan sebelumnya.
        $this->service_category_id = $service->service_category_id;
        $this->title = $service->title;
        $this->description = $service->description ?? '';
        $this->experience_years = $service->experience_years;

        // Ambil keahlian yang sudah dipilih sebelumnya.
        $this->selectedSkills = $service->skills()
            ->pluck('skills.id')
            ->map(fn ($id) => (int) $id)
            ->toArray();
    }

    public function getCategoriesProperty()
    {
        return ServiceCategory::query()
            ->where(function ($query) {
                $query->where('is_active', true)
                    ->orWhere('id', $this->service_category_id);
            })
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
        // Kosongkan keahlian ketika kategori diganti.
        $this->selectedSkills = [];
    }

    public function update(): void
    {
        $providerProfile = Auth::user()->providerProfile;

        abort_unless($providerProfile, 403);

        // Ambil ulang layanan berdasarkan pemiliknya.
        $service = ProviderService::query()
            ->where('provider_profile_id', $providerProfile->id)
            ->findOrFail($this->serviceId);

        $validated = $this->validate([
            'service_category_id' => [
                'required',
                'integer',
                Rule::exists('service_categories', 'id')
                    ->where(function ($query) use ($service) {
                        $query->where('is_active', true)
                            ->orWhere('id', $service->service_category_id);
                    }),
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

        // Perbarui informasi layanan.
        $service->update([
            'service_category_id' => $validated['service_category_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'experience_years' => $validated['experience_years'] ?? null,
        ]);

        // Perbarui relasi keahlian.
        $service->skills()->sync(
            $validated['selectedSkills'] ?? []
        );

        session()->flash(
            'success',
            'Layanan berhasil diperbarui.'
        );

        $this->redirectRoute('provider.services.index');
    }

    public function render()
    {
        return view('components.provider.⚡edit-service');
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
                Edit Layanan
            </h1>

            <p class="mt-1 text-sm text-gray-600">
                Perbarui informasi layanan yang Anda tawarkan.
            </p>
        </div>

        <form
            wire:submit="update"
            class="space-y-6 rounded-xl bg-white p-6 shadow-sm"
        >

            {{-- Kategori Layanan --}}
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

            {{-- Nama Layanan --}}
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

            {{-- Deskripsi --}}
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

            {{-- Pengalaman --}}
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

            {{-- Keahlian --}}
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

            {{-- Informasi --}}
            <div class="rounded-lg bg-blue-50 p-4 text-sm text-blue-800">
                Perubahan informasi layanan tidak akan otomatis mengubah
                status aktivasi maupun menghapus paket harga.
            </div>

            {{-- Tombol --}}
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
