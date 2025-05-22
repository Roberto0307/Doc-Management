<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatusResource\Pages;
use App\Models\Status;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StatusResource extends Resource
{
    protected static ?string $model = Status::class;

    protected static ?string $navigationGroup = 'Records Management';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('title')
                    ->label('Internal title')
                    ->required()
                    ->disabled(fn () => ! auth()->user()?->hasRole('super_admin'))
                    ->dehydrated(fn () => auth()->user()?->hasRole('super_admin'))
                    ->helperText('This is the status identifier. Only super admins can edit it.')
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('label')
                    ->label('Display name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder(fn ($record) => $record?->title ?? 'State title')
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('color')
                    ->label('Color'),

                Forms\Components\TextInput::make('icon')
                    ->label('Icon'),

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
            'index' => Pages\ListStatuses::route('/'),
            'create' => Pages\CreateStatus::route('/create'),
            'edit' => Pages\EditStatus::route('/{record}/edit'),
        ];
    }
}
