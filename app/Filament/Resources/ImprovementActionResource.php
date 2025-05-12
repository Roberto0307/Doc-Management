<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ImprovementActionResource\Pages;
use App\Filament\Resources\ImprovementActionResource\RelationManagers;
use App\Models\ImprovementAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ImprovementActionResource extends Resource
{
    protected static ?string $model = ImprovementAction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('process_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('sub_process_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('improvement_action_origin_id')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('registration_date')
                    ->required(),
                Forms\Components\TextInput::make('registered_by_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('responsible_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('improvement_action_status_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('expected_impact')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('deadline')
                    ->required(),
                Forms\Components\DatePicker::make('actual_closing_date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('process_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sub_process_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('improvement_action_origin_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('registration_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('registered_by_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('responsible_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('improvement_action_status_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deadline')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('actual_closing_date')
                    ->date()
                    ->sortable(),
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
            'index' => Pages\ListImprovementActions::route('/'),
            'create' => Pages\CreateImprovementAction::route('/create'),
            'edit' => Pages\EditImprovementAction::route('/{record}/edit'),
        ];
    }
}
