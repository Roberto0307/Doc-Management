<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubProcessResource\Pages;
use App\Models\SubProcess;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SubProcessResource extends Resource
{
    protected static ?string $model = SubProcess::class;

    protected static ?string $navigationGroup = 'System Management';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->disabled(fn (string $context) => $context === 'edit')
                    ->required(fn (string $context) => $context === 'create'),
                Forms\Components\TextInput::make('acronym')
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->disabled(fn (string $context) => $context === 'edit')
                    ->required(fn (string $context) => $context === 'create'),
                Forms\Components\Select::make('process_id')
                    ->relationship('process', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->label('Assigned thread leader')
                    ->options(fn ($record) => $record->users()->pluck('users.name', 'users.id') ?? [])
                    ->searchable()
                    ->preload()
                    ->required(fn (string $context) => $context === 'edit')
                    ->visible(fn (string $context) => $context === 'edit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('acronym')
                    ->searchable(),
                Tables\Columns\TextColumn::make('process.title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('owner.name')
                    ->label('Thread leader')
                    ->searchable(),
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
                    //
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
            'index' => Pages\ListSubProcesses::route('/'),
            'create' => Pages\CreateSubProcess::route('/create'),
            'edit' => Pages\EditSubProcess::route('/{record}/edit'),
        ];
    }
}
