<?php

namespace App\Filament\Resources\ProviderVerificationDocuments\Schemas;

use App\Enums\ProviderDocumentType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProviderVerificationDocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('provider_profile_id')
                    ->relationship('providerProfile', 'id')
                    ->required(),
                Select::make('document_type')
                    ->options(ProviderDocumentType::class)
                    ->required(),
                TextInput::make('document_number'),
                TextInput::make('file_path')
                    ->required(),
                TextInput::make('original_filename'),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                DateTimePicker::make('submitted_at'),
                TextInput::make('reviewed_by')
                    ->numeric(),
                DateTimePicker::make('reviewed_at'),
                Textarea::make('rejection_reason')
                    ->columnSpanFull(),
                DatePicker::make('expires_at'),
            ]);
    }
}
