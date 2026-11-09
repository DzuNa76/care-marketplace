<?php

namespace App\Filament\Resources\ProviderVerificationDocuments\Schemas;

use App\Enums\ProviderDocumentType;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProviderVerificationDocumentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('providerProfile.user.name')
                    ->label('Nama Provider'),

                TextEntry::make('providerProfile.display_name')
                    ->label('Nama Tampilan'),

                TextEntry::make('providerProfile.phone')
                    ->label('Nomor Telepon'),

                TextEntry::make('document_type')
                    ->label('Jenis Dokumen')
                    ->formatStateUsing(
                        fn ($state) => $state instanceof ProviderDocumentType
                            ? $state->label()
                            : $state
                    )
                    ->badge(),

                TextEntry::make('document_number')
                    ->label('Nomor Dokumen')
                    ->placeholder('-'),

                TextEntry::make('original_filename')
                    ->label('Nama File')
                    ->placeholder('-'),

                TextEntry::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                TextEntry::make('submitted_at')
                    ->label('Tanggal Pengajuan')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),

                TextEntry::make('reviewer.name')
                    ->label('Diperiksa Oleh')
                    ->placeholder('-'),

                TextEntry::make('reviewed_at')
                    ->label('Tanggal Pemeriksaan')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),

                TextEntry::make('rejection_reason')
                    ->label('Alasan Penolakan')
                    ->placeholder('-')
                    ->columnSpanFull(),

                TextEntry::make('expires_at')
                    ->label('Tanggal Kedaluwarsa')
                    ->date('d M Y')
                    ->placeholder('-'),
            ]);
    }
}
