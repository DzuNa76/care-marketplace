<?php

namespace App\Filament\Resources\ProviderVerificationDocuments\Pages;

use App\Filament\Resources\ProviderVerificationDocuments\ProviderVerificationDocumentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProviderVerificationDocument extends EditRecord
{
    protected static string $resource = ProviderVerificationDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
