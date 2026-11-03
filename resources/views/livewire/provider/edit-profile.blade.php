<div class="min-h-screen bg-gray-100 py-10">
    <div class="mx-auto max-w-3xl px-4 sm:px-6">

        <div class="mb-6">
            <a href="{{ route('provider.dashboard') }}"
                class="text-sm text-blue-700 hover:underline">
                ← Kembali ke Dashboard
            </a>

            <h1 class="mt-4 text-2xl font-bold text-gray-800">
                Edit Profil Provider
            </h1>

            <p class="mt-2 text-sm text-gray-600">
                Lengkapi informasi diri agar customer dapat mengenal Anda.
            </p>
        </div>

        @if (session()->has('success'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-xl bg-white p-6 shadow-sm sm:p-8">

            <form wire:submit="save" class="space-y-6">

                <div>
                    <label for="display_name"
                        class="mb-2 block text-sm font-medium text-gray-700">
                        Nama Tampilan <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="display_name"
                        type="text"
                        wire:model="display_name"
                        maxlength="100"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        placeholder="Masukkan nama tampilan">

                    @error('display_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone"
                        class="mb-2 block text-sm font-medium text-gray-700">
                        Nomor Telepon
                    </label>

                    <input
                        id="phone"
                        type="tel"
                        wire:model="phone"
                        maxlength="20"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        placeholder="Contoh: 081234567890">

                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="experience_years"
                        class="mb-2 block text-sm font-medium text-gray-700">
                        Pengalaman Kerja (Tahun)
                    </label>

                    <input
                        id="experience_years"
                        type="number"
                        wire:model="experience_years"
                        min="0"
                        max="60"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        placeholder="Contoh: 3">

                    @error('experience_years')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="bio"
                        class="mb-2 block text-sm font-medium text-gray-700">
                        Tentang Saya
                    </label>

                    <textarea
                        id="bio"
                        wire:model="bio"
                        rows="5"
                        maxlength="1000"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        placeholder="Ceritakan pengalaman, keahlian, dan pendekatan Anda dalam memberikan layanan..."></textarea>

                    @error('bio')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <p class="mt-1 text-xs text-gray-500">
                        Maksimal 1000 karakter.
                    </p>
                </div>

                <div class="flex flex-col gap-3 border-t border-gray-100 pt-6 sm:flex-row sm:justify-end">

                    <a href="{{ route('provider.dashboard') }}"
                        class="rounded-lg border border-gray-300 px-5 py-3 text-center text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Batal
                    </a>

                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="save"
                        class="rounded-lg bg-blue-700 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-800 disabled:cursor-not-allowed disabled:opacity-60">

                        <span wire:loading.remove wire:target="save">
                            Simpan Profil
                        </span>

                        <span wire:loading wire:target="save">
                            Menyimpan...
                        </span>
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>
