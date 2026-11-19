<?php

namespace App\Livewire\Provider;

use App\Enums\ProviderDocumentType;
use App\Models\ProviderProfile;
use App\Models\ProviderVerificationDocument;
use App\Models\ProviderVerificationLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class UploadVerificationDocument extends Component
{
    use WithFileUploads;

    public $documentType = '';

    public $documentNumber = '';

    public $documentFile;

    public function mount(): void
    {
        abort_unless(
            Auth::user()?->providerProfile,
            403,
            'Profil provider belum tersedia.'
        );
    }

    public function updatedDocumentFile(): void
    {
        $this->resetValidation('documentFile');
    }

    public function saveDocument(): void
    {
        $user = Auth::user();

        abort_unless($user, 403);

        $providerProfile = $user->providerProfile;

        abort_unless($providerProfile, 403, 'Profil provider belum tersedia.');

        $allowedTypes = collect(ProviderDocumentType::cases())
            ->pluck('value')
            ->all();

        $validated = $this->validate([
            'documentType' => [
                'required',
                'string',
                Rule::in($allowedTypes),
            ],
            'documentNumber' => [
                'nullable',
                'string',
                'max:100',
            ],
            'documentFile' => [
                'required',
                'file',
                File::types(['pdf', 'jpg', 'jpeg', 'png'])->max(5120),
            ],
        ]);

        $documentType = ProviderDocumentType::from(
            $validated['documentType']
        );

        $categorySlugs = $providerProfile
            ->providerServices()
            ->with('category')
            ->get()
            ->pluck('category.slug')
            ->filter()
            ->unique()
            ->all();

        // Validasi kategori untuk dokumen khusus.
        if (! in_array($documentType, [
            ProviderDocumentType::IDENTITY_CARD,
            ProviderDocumentType::SELFIE,
            ProviderDocumentType::SUPPORTING_DOCUMENT,
        ], true)) {
            abort_unless(
                count(array_intersect(
                    $documentType->allowedCategories(),
                    $categorySlugs
                )) > 0,
                403,
                'Jenis dokumen tidak sesuai dengan kategori layanan.'
            );
        }

        $newFilePath = null;
        $oldFilePath = null;

        try {
            $result = DB::transaction(function () use (
                $providerProfile,
                $documentType,
                $validated,
                &$newFilePath,
                &$oldFilePath
            ) {
                // Mengunci profil agar upload bersamaan tidak membuat
                // dua record untuk jenis dokumen yang sama.
                ProviderProfile::query()
                    ->whereKey($providerProfile->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $document = ProviderVerificationDocument::query()
                    ->where('provider_profile_id', $providerProfile->id)
                    ->where('document_type', $documentType->value)
                    ->lockForUpdate()
                    ->first();

                // Dokumen pending tidak boleh diganti.
                if ($document && $document->status === 'pending') {
                    return ['blocked' => 'pending'];
                }

                // Dokumen approved yang belum kedaluwarsa
                // tidak boleh diganti.
                if ($document && $document->status === 'approved') {
                    $isExpired = $document->expires_at !== null
                        && $document->expires_at->lt(today());

                    if (! $isExpired) {
                        return ['blocked' => 'approved'];
                    }
                }

                // Simpan file baru terlebih dahulu.
                $newFilePath = $this->documentFile->store(
                    $providerProfile->id . '/' . $documentType->value,
                    'provider_documents'
                );

                if (! $newFilePath) {
                    throw new \RuntimeException(
                        'File dokumen gagal disimpan.'
                    );
                }

                $originalFilename = basename(
                    $this->documentFile->getClientOriginalName()
                );

                $previousStatus = $document?->status;
                $action = $document ? 'resubmitted' : 'uploaded';

                if (! $document) {
                    $document = new ProviderVerificationDocument();

                    $document->provider_profile_id = $providerProfile->id;
                    $document->document_type = $documentType;
                } else {
                    $oldFilePath = $document->file_path;
                }

                $document->document_number = $validated['documentNumber'] ?? null;
                $document->file_path = $newFilePath;
                $document->original_filename = $originalFilename;
                $document->status = 'pending';
                $document->submitted_at = now();

                // Reset hasil pemeriksaan sebelumnya.
                $document->reviewed_by = null;
                $document->reviewed_at = null;
                $document->rejection_reason = null;
                $document->expires_at = null;

                $document->save();

                // Catat metadata upload ke tabel riwayat.
                ProviderVerificationLog::create([
                    'provider_verification_document_id' => $document->id,
                    'actor_id' => Auth::id(),
                    'action' => $action,
                    'previous_status' => $previousStatus,
                    'new_status' => 'pending',
                    'original_filename' => $originalFilename,
                ]);

                return ['blocked' => null];
            });

            // Jika upload tidak diizinkan, tidak ada file yang dibuat.
            if ($result['blocked'] === 'pending') {
                $this->addError(
                    'documentType',
                    'Dokumen masih menunggu pemeriksaan admin. Anda belum dapat mengunggah ulang.'
                );

                return;
            }

            if ($result['blocked'] === 'approved') {
                $this->addError(
                    'documentType',
                    'Dokumen sudah disetujui dan belum kedaluwarsa. Anda belum dapat menggantinya.'
                );

                return;
            }

        } catch (\Throwable $exception) {
            // Hapus file baru jika transaksi database gagal.
            if ($newFilePath) {
                Storage::disk('provider_documents')->delete($newFilePath);
            }

            report($exception);

            $this->addError(
                'documentFile',
                'Dokumen gagal disimpan. Silakan coba kembali.'
            );

            return;
        }

        // Hapus file lama hanya setelah transaksi database berhasil.
        if ($oldFilePath && $oldFilePath !== $newFilePath) {
            try {
                Storage::disk('provider_documents')->delete($oldFilePath);
            } catch (\Throwable $exception) {
                report($exception);
            }
        }

        $this->reset([
            'documentType',
            'documentNumber',
            'documentFile',
        ]);

        session()->flash(
            'success',
            'Dokumen berhasil diunggah dan menunggu pemeriksaan admin.'
        );
    }

    public function render()
    {
        $providerProfile = Auth::user()?->providerProfile;

        $categorySlugs = [];

        if ($providerProfile) {
            $categorySlugs = $providerProfile
                ->providerServices()
                ->with('category')
                ->get()
                ->pluck('category.slug')
                ->filter()
                ->unique()
                ->all();
        }

        $documentTypes = collect(ProviderDocumentType::cases())
            ->filter(function ($type) use ($categorySlugs) {
                $allowedCategories = $type->allowedCategories();

                if (in_array($type, [
                    ProviderDocumentType::IDENTITY_CARD,
                    ProviderDocumentType::SELFIE,
                    ProviderDocumentType::SUPPORTING_DOCUMENT,
                ], true)) {
                    return true;
                }

                return count(array_intersect(
                    $allowedCategories,
                    $categorySlugs
                )) > 0;
            })
            ->map(fn ($type) => [
                'value' => $type->value,
                'label' => $type->label(),
            ])
            ->values()
            ->all();

        return view(
            'livewire.provider.upload-verification-document',
            compact('documentTypes')
        );
    }
}
