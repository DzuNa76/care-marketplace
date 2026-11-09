<?php

namespace App\Filament\Resources\ProviderVerificationDocuments\Pages;

use App\Filament\Resources\ProviderVerificationDocuments\ProviderVerificationDocumentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProviderVerificationDocuments extends ListRecords
{
    protected static string $resource = ProviderVerificationDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
