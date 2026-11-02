
<div class="max-w-xl space-y-6">

    <div>
        <h2 class="text-xl font-semibold text-gray-900">
            Unggah Dokumen Verifikasi
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Unggah dokumen yang diperlukan untuk proses verifikasi akun provider.
        </p>
    </div>

    @if (session()->has('success'))
        <div class="rounded-lg bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit="saveDocument" class="space-y-5">

        <div>
            <label for="documentType" class="mb-2 block text-sm font-medium text-gray-700">
                Jenis Dokumen
            </label>

            <select
                id="documentType"
                wire:model="documentType"
                class="w-full rounded-lg border-gray-300 shadow-sm"
            >
                <option value="">Pilih jenis dokumen</option>

                @foreach ($documentTypes as $type)
                    <option value="{{ $type['value'] }}">
                        {{ $type['label'] }}
                    </option>
                @endforeach
            </select>

            @error('documentType')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="documentNumber" class="mb-2 block text-sm font-medium text-gray-700">
                Nomor Dokumen (Opsional)
            </label>

            <input
                id="documentNumber"
                type="text"
                wire:model="documentNumber"
                maxlength="100"
                class="w-full rounded-lg border-gray-300 shadow-sm"
                placeholder="Masukkan nomor dokumen jika tersedia"
            >

            @error('documentNumber')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="documentFile" class="mb-2 block text-sm font-medium text-gray-700">
                File Dokumen
            </label>

            <input
                id="documentFile"
                type="file"
                wire:model="documentFile"
                accept=".pdf,.jpg,.jpeg,.png"
                class="w-full rounded-lg border border-gray-300 p-2"
            >

            <p class="mt-1 text-xs text-gray-500">
                Format PDF, JPG, JPEG, atau PNG. Maksimal 5 MB.
            </p>

            @error('documentFile')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <div wire:loading wire:target="documentFile" class="mt-2 text-sm text-blue-600">
                Sedang mengunggah file sementara...
            </div>
        </div>

        <button
            type="submit"
            wire:loading.attr="disabled"
            class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50"
        >
            <span wire:loading.remove wire:target="saveDocument">
                Kirim Dokumen
            </span>

            <span wire:loading wire:target="saveDocument">
                Memproses...
            </span>
        </button>

    </form>
</div>
