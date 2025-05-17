<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ImprovementActionResource\Pages;
use App\Models\ImprovementAction;
use App\Models\SubProcess;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class ImprovementActionResource extends Resource
{
    protected static ?string $model = ImprovementAction::class;

    protected static ?string $navigationGroup = 'AM/ACPs';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Improvement Action Data')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('process_id')
                            ->relationship('process', 'title')
                            ->afterStateUpdated(function (Set $set) {
                                $set('sub_process_id', null);
                                $set('responsible_id', null);
                            })
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),
                        Forms\Components\Select::make('sub_process_id')
                            ->label('Sub Process')
                            ->options(
                                fn (Get $get): Collection => SubProcess::query()
                                    ->where('process_id', $get('process_id'))
                                    ->pluck('title', 'id')
                            )
                            ->afterStateUpdated(fn (Set $set) => $set('responsible_id', null))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),
                        Forms\Components\Select::make('improvement_action_origin_id')
                            ->relationship('improvementActionOrigin', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('responsible_id')
                            ->options(
                                fn (Get $get): array => User::whereHas('subProcesses',
                                    fn ($query) => $query->where('sub_process_id', $get('sub_process_id')))
                                    ->pluck('name', 'id')
                                    ->toArray())
                            /* ->options(function (Get $get) {
                                    $subProcessId = $get('sub_process_id');

                                    return User::whereHas('subProcesses', function ($query) use ($subProcessId) {
                                        $query->where('sub_process_id', $subProcessId);
                                    })->pluck('name', 'id')->toArray();
                            }) */ // Otra opcion
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),
                        Forms\Components\Textarea::make('expected_impact')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\DatePicker::make('deadline')
                            ->minDate(now())
                            ->required(),
                        /* Forms\Components\DatePicker::make('actual_closing_date'), */
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('process.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('subProcess.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('improvementActionOrigin.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('registration_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('registeredBy.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('responsible.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('improvementActionStatus.label')
                    ->label('Status')
                    ->searchable()
                    ->badge()
                    ->color(fn ($record) => $record->improvementActionStatus->colorName()),
                Tables\Columns\TextColumn::make('deadline')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('actual_closing_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->recordUrl(null)
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'view' => Pages\ViewImprovementAction::route('/{record}'),
            'edit' => Pages\EditImprovementAction::route('/{record}/edit'),
            'improvement_action_completions.create' => \App\Filament\Resources\ImprovementActionCompletionResource\Pages\CreateImprovementActionCompletion::route('/{improvementactionId}/completions/create'),
            'improvement_action_completions.view' => \App\Filament\Resources\ImprovementActionCompletionResource\Pages\ViewImprovementActionCompletion::route('/{record}/completions/{completionId}/view'),

        ];
    }
}
