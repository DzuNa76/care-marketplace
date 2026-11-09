<?php

namespace App\Filament\Resources\ProviderVerificationDocuments;

use App\Filament\Resources\ProviderVerificationDocuments\Pages\CreateProviderVerificationDocument;
use App\Filament\Resources\ProviderVerificationDocuments\Pages\EditProviderVerificationDocument;
use App\Filament\Resources\ProviderVerificationDocuments\Pages\ListProviderVerificationDocuments;
use App\Filament\Resources\ProviderVerificationDocuments\Pages\ViewProviderVerificationDocument;
use App\Filament\Resources\ProviderVerificationDocuments\Schemas\ProviderVerificationDocumentForm;
use App\Filament\Resources\ProviderVerificationDocuments\Schemas\ProviderVerificationDocumentInfolist;
use App\Filament\Resources\ProviderVerificationDocuments\Tables\ProviderVerificationDocumentsTable;
use App\Models\ProviderVerificationDocument;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProviderVerificationDocumentResource extends Resource
{
    protected static ?string $model = ProviderVerificationDocument::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'original_filename';

    public static function form(Schema $schema): Schema
    {
        return ProviderVerificationDocumentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProviderVerificationDocumentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProviderVerificationDocumentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProviderVerificationDocuments::route('/'),
            'create' => CreateProviderVerificationDocument::route('/create'),
            'view' => ViewProviderVerificationDocument::route('/{record}'),
            'edit' => EditProviderVerificationDocument::route('/{record}/edit'),
        ];
    }
}
