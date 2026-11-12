<?php

namespace App\Filament\Resources\ProviderVerificationDocuments\Pages;

use App\Filament\Resources\ProviderVerificationDocuments\ProviderVerificationDocumentResource;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewProviderVerificationDocument extends ViewRecord
{
    protected static string $resource = ProviderVerificationDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Setujui Dokumen')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (): bool => $this->record->status === 'pending')
                ->action(function (): void {
                    $this->record->update([
                        'status' => 'approved',
                        'reviewed_by' => Auth::id(),
                        'reviewed_at' => now(),
                        'rejection_reason' => null,
                    ]);

                    Notification::make()
                        ->title('Dokumen berhasil disetujui')
                        ->success()
                        ->send();
                }),

            Action::make('reject')
                ->label('Tolak Dokumen')
                ->color('danger')
                ->schema([
                    Textarea::make('rejection_reason')
                        ->label('Alasan Penolakan')
                        ->required()
                        ->maxLength(500),
                ])
                ->visible(fn (): bool => $this->record->status === 'pending')
                ->action(function (array $data): void {
                    $this->record->update([
                        'status' => 'rejected',
                        'reviewed_by' => Auth::id(),
                        'reviewed_at' => now(),
                        'rejection_reason' => $data['rejection_reason'],
                    ]);

                    Notification::make()
                        ->title('Dokumen berhasil ditolak')
                        ->success()
                        ->send();
                }),
        ];
    }
}
