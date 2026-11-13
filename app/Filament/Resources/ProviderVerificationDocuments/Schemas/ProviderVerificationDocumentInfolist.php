<?php

namespace App\Filament\Resources\ProviderVerificationDocuments\Schemas;

use App\Enums\ProviderDocumentType;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProviderVerificationDocumentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'default' => 1,
                'xl' => 2,
            ])
            ->components([
                Section::make('Informasi Provider')
                    ->schema([
                        TextEntry::make('providerProfile.user.name')
                            ->label('Nama Provider'),

                        TextEntry::make('providerProfile.display_name')
                            ->label('Nama Tampilan'),

                        TextEntry::make('providerProfile.phone')
                            ->label('Nomor Telepon'),
                    ])
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ]),

                Section::make('Informasi Dokumen')
                    ->schema([
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
                    ])
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ]),

                Section::make('Preview Dokumen')
                    ->schema([
                        ImageEntry::make('file_path')
                            ->hiddenLabel()
                            ->state(fn ($record) => in_array(
                                strtolower(pathinfo($record->original_filename, PATHINFO_EXTENSION)),
                                ['jpg', 'jpeg', 'png']
                            ) ? route('admin.provider-verification-documents.file', $record) : null)
                            ->imageWidth('100%')
                            ->imageHeight('auto')
                            ->extraImgAttributes([
                                'class' => 'w-full h-auto max-h-[560px] object-contain rounded-xl mx-auto',
                                'style' => 'max-height: min(65vh, 560px);',
                            ])
                            ->url(fn ($record) => route(
                                'admin.provider-verification-documents.preview',
                                $record
                            )),

                        TextEntry::make('file_path')
                            ->label('Dokumen PDF')
                            ->formatStateUsing(fn () => 'Buka dokumen PDF')
                            ->url(fn ($record) => route(
                                'admin.provider-verification-documents.preview',
                                $record
                            ))
                            ->openUrlInNewTab()
                            ->visible(fn ($record) => strtolower(
                                pathinfo($record->original_filename, PATHINFO_EXTENSION)
                            ) === 'pdf'),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),

                Section::make('Riwayat Pemeriksaan')
                    ->schema([
                        TextEntry::make('submitted_at')
                            ->label('Tanggal Pengajuan')
                            ->dateTime('d M Y H:i'),

                        TextEntry::make('reviewer.name')
                            ->label('Diperiksa Oleh')
                            ->placeholder('-'),

                        TextEntry::make('reviewed_at')
                            ->label('Tanggal Pemeriksaan')
                            ->dateTime('d M Y H:i')
                            ->placeholder('-'),

                        TextEntry::make('rejection_reason')
                            ->label('Alasan Penolakan')
                            ->placeholder('-'),

                        TextEntry::make('expires_at')
                            ->label('Tanggal Kedaluwarsa')
                            ->date('d M Y')
                            ->placeholder('-'),
                    ])
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ])
                    ->columnSpanFull(),
            ]);
    }

}
