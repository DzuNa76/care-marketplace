<?php

namespace App\Filament\Resources\ProviderVerificationDocuments\Pages;

use App\Filament\Resources\ProviderVerificationDocuments\ProviderVerificationDocumentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProviderVerificationDocument extends ViewRecord
{
    protected static string $resource = ProviderVerificationDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
