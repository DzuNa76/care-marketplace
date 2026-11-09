<?php

namespace App\Filament\Resources\ProviderVerificationDocuments\Tables;

use App\Enums\ProviderDocumentType;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProviderVerificationDocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('providerProfile.user.name')
                    ->label('Nama Provider')
                    ->searchable(),

                TextColumn::make('document_type')
                    ->label('Jenis Dokumen')
                    ->formatStateUsing(
                        fn ($state) => $state instanceof ProviderDocumentType
                            ? $state->label()
                            : $state
                    )
                    ->badge(),

                TextColumn::make('document_number')
                    ->label('Nomor Dokumen')
                    ->placeholder('-'),

                TextColumn::make('original_filename')
                    ->label('Nama File')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('submitted_at')
                    ->label('Tanggal Pengajuan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('reviewer.name')
                    ->label('Diperiksa Oleh')
                    ->placeholder('-'),

                TextColumn::make('reviewed_at')
                    ->label('Tanggal Pemeriksaan')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('expires_at')
                    ->label('Tanggal Kedaluwarsa')
                    ->date('d M Y')
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
