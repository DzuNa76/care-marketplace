<?php

namespace App\Enums;

enum ProviderDocumentType: string
{
    case IDENTITY_CARD = 'identity_card';
    case SELFIE = 'selfie';
    case TRAINING_CERTIFICATE = 'training_certificate';
    case NURSE_STR = 'nurse_str';
    case NURSE_CERTIFICATE = 'nurse_certificate';
    case SUPPORTING_DOCUMENT = 'supporting_document';

    public function label(): string
    {
        return match ($this) {
            self::IDENTITY_CARD => 'KTP',
            self::SELFIE => 'Foto Diri',
            self::TRAINING_CERTIFICATE => 'Sertifikat Pelatihan',
            self::NURSE_STR => 'Surat Tanda Registrasi (STR)',
            self::NURSE_CERTIFICATE => 'Sertifikat Kompetensi Perawat',
            self::SUPPORTING_DOCUMENT => 'Dokumen Pendukung',
        };
    }

    public function allowedCategories(): array
    {
        return match ($this) {
            self::IDENTITY_CARD,
            self::SELFIE,
            self::SUPPORTING_DOCUMENT => [
                'caregiver',
                'babysitter',
                'perawat',
                'cleaning-service',
            ],

            self::TRAINING_CERTIFICATE => [
                'caregiver',
                'babysitter',
            ],

            self::NURSE_STR,
            self::NURSE_CERTIFICATE => [
                'perawat',
            ],
        };
    }
}
