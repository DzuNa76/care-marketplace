<?php

namespace App\Livewire\Provider;

use App\Enums\ProviderDocumentType;
use App\Models\ProviderVerificationDocument;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Storage;

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

        $filePath = null;

        try {
            $filePath = $this->documentFile->store(
                $providerProfile->id . '/' . $documentType->value,
                'provider_documents'
            );

            $document = new ProviderVerificationDocument();

            $document->provider_profile_id = $providerProfile->id;
            $document->document_type = $documentType;
            $document->document_number = $validated['documentNumber'] ?? null;
            $document->file_path = $filePath;
            $document->original_filename = basename(
                $this->documentFile->getClientOriginalName()
            );
            $document->status = 'pending';
            $document->submitted_at = now();

            $document->save();

        } catch (\Throwable $exception) {
            if ($filePath) {
                Storage::disk('provider_documents')->delete($filePath);
            }

            report($exception);

            $this->addError(
                'documentFile',
                'Dokumen gagal disimpan. Silakan coba kembali.'
            );

            return;
        }

        $this->reset([
            'documentType',
            'documentNumber',
            'documentFile',
        ]);

        session()->flash('success', 'Dokumen berhasil diunggah dan menunggu pemeriksaan admin.');
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

                // Dokumen umum berlaku untuk semua kategori.
                if (in_array($type, [
                    ProviderDocumentType::IDENTITY_CARD,
                    ProviderDocumentType::SELFIE,
                    ProviderDocumentType::SUPPORTING_DOCUMENT,
                ], true)) {
                    return true;
                }

                // Dokumen khusus hanya tersedia untuk kategori terkait.
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
