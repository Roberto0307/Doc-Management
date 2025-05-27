<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ImprovementActionTaskResource\Pages;
use App\Filament\Resources\ImprovementActionTaskResource\RelationManagers\ImprovementActionTaskCommentsRelationManager;
use App\Filament\Resources\ImprovementActionTaskResource\RelationManagers\ImprovementActionTaskFilesRelationManager;
use App\Models\ImprovementAction;
use App\Models\ImprovementActionTask;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ImprovementActionTaskResource extends Resource
{
    protected static ?string $model = ImprovementActionTask::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Improvement Action Task Data')
                    ->description('')
                    ->columns(2)
                    ->schema([

                        Forms\Components\TextInput::make('improvement_action_id')
                            ->required()
                            ->numeric()
                            ->live()
                            ->default(request()->route('improvementactionId'))
                            ->visible(false),
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('detail')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('responsible_id')
                            ->options(
                                fn (Forms\Get $get): array => User::whereHas('subProcesses', function ($query) use ($get) {
                                    $action = ImprovementAction::find($get('improvement_action_id'));
                                    if (! $action) {
                                        return;
                                    }
                                    $query->where('sub_process_id', $action->sub_process_id);
                                })->pluck('name', 'id')->toArray()
                            )
                            ->preload()
                            ->searchable()
                            ->required(),
                        Forms\Components\DatePicker::make('start_date')
                            ->minDate(now()->format('Y-m-d'))
                            ->maxDate(fn (Forms\Get $get) => ImprovementAction::find($get('improvement_action_id'))?->deadline)
                            ->afterStateUpdated(function (Forms\Set $set) {
                                $set('deadline', null);
                            })
                            ->live()
                            ->required(),
                        Forms\Components\DatePicker::make('deadline')
                            ->minDate(fn (Forms\Get $get) => $get('start_date'))
                            ->maxDate(fn (Forms\Get $get) => ImprovementAction::find($get('improvement_action_id'))?->deadline)
                            ->live()
                            ->required()
                            ->disabled(fn (Forms\Get $get) => $get('start_date') === null),
                        Forms\Components\Placeholder::make('status')
                            ->label('Status')
                            ->content(fn ($record) => $record?->improvementActionTaskStatus?->label ?? 'Sin estado')
                            ->visible(fn (string $context) => $context === 'view'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            /*                 Tables\Columns\TextColumn::make('improvement_action_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsible_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deadline')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('actual_start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('actual_closing_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('improvement_action_task_status_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), */])
            ->filters([
                //
            ])
            ->actions([
            /* Tables\Actions\EditAction::make(), */])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            ImprovementActionTaskCommentsRelationManager::class,
            ImprovementActionTaskFilesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListImprovementActionTasks::route('/'),
            'create' => Pages\CreateImprovementActionTask::route('/create'),
            'view' => Pages\ViewImprovementActionTask::route('/{record}'),
            'edit' => Pages\EditImprovementActionTask::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
