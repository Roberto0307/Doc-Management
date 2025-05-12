<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ImprovementActionStatusResource\Pages;
use App\Models\ImprovementActionStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ImprovementActionStatusResource extends Resource
{
    protected static ?string $model = ImprovementActionStatus::class;

    protected static ?string $navigationGroup = 'AM Management';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 13;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->disabled(fn (string $context) => $context === 'edit')
                    ->required(fn (string $context) => $context === 'create')
                    ->maxLength(255),
                Forms\Components\TextInput::make('label')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('color')
                    ->maxLength(255),
                Forms\Components\TextInput::make('icon')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Color and Icon')
                    ->badge()
                    ->color(fn ($record) => $record->color)
                    ->icon(fn ($record) => $record->icon)
                    ->searchable(),
                Tables\Columns\TextColumn::make('color')
                    ->searchable(),
                Tables\Columns\TextColumn::make('icon')
                    ->searchable(),
                Tables\Columns\IconColumn::make('protected')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListImprovementActionStatuses::route('/'),
            'create' => Pages\CreateImprovementActionStatus::route('/create'),
            'edit' => Pages\EditImprovementActionStatus::route('/{record}/edit'),
        ];
    }
}
