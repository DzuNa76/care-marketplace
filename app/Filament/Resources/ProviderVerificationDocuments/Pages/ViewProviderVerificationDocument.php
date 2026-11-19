<?php

namespace App\Filament\Resources\ProviderVerificationDocuments\Pages;

use App\Filament\Resources\ProviderVerificationDocuments\ProviderVerificationDocumentResource;
use App\Models\ProviderVerificationLog;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
                    DB::transaction(function (): void {
                        $previousStatus = $this->record->status;

                        $this->record->update([
                            'status' => 'approved',
                            'reviewed_by' => Auth::id(),
                            'reviewed_at' => now(),
                            'rejection_reason' => null,
                        ]);

                        ProviderVerificationLog::create([
                            'provider_verification_document_id' => $this->record->id,
                            'actor_id' => Auth::id(),
                            'action' => 'approved',
                            'previous_status' => $previousStatus,
                            'new_status' => 'approved',
                            'original_filename' => $this->record->original_filename,
                        ]);
                    });

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
                    DB::transaction(function () use ($data): void {
                        $previousStatus = $this->record->status;

                        $this->record->update([
                            'status' => 'rejected',
                            'reviewed_by' => Auth::id(),
                            'reviewed_at' => now(),
                            'rejection_reason' => $data['rejection_reason'],
                        ]);

                        ProviderVerificationLog::create([
                            'provider_verification_document_id' => $this->record->id,
                            'actor_id' => Auth::id(),
                            'action' => 'rejected',
                            'previous_status' => $previousStatus,
                            'new_status' => 'rejected',
                            'original_filename' => $this->record->original_filename,
                            'rejection_reason' => $data['rejection_reason'],
                        ]);
                    });

                    Notification::make()
                        ->title('Dokumen berhasil ditolak')
                        ->success()
                        ->send();
                }),
        ];
    }
}
