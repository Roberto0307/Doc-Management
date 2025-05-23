<?php

namespace App\Filament\Resources\ImprovementActionResource\RelationManagers;

use App\Filament\Resources\ImprovementActionResource;
use App\Models\User;
use App\Services\AuthService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ImprovementActionTasksRelationManager extends RelationManager
{
    protected static string $relationship = 'improvementActionTasks';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('detail')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('responsible_id')
                    /* ->options(function () {
                        $subProcessId = $this->getOwnerRecord()?->sub_process_id;

                        if (!$subProcessId) {
                            return [];
                        }

                        return User::whereHas('subProcesses', function ($query) use ($subProcessId) {
                            $query->where('sub_process_id', $subProcessId);
                        })
                            ->pluck('name', 'id')
                            ->toArray();
                    }) */
                    ->options(fn (): array => User::whereHas(
                        'subProcesses',
                        fn ($query) => $query->where('sub_process_id', $this->getOwnerRecord()?->sub_process_id)
                    )
                        ->pluck('name', 'id')
                        ->toArray())
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required(),
                Forms\Components\DatePicker::make('start_date')
                    ->minDate(now())
                    ->afterStateUpdated(function (Set $set) {
                        $set('deadline', null);
                    })
                    ->live()
                    ->required(),
                Forms\Components\DatePicker::make('deadline')
                    ->minDate(fn (Get $get) => $get('start_date'))
                    ->live()
                    ->required()
                    ->disabled(fn (Get $get) => $get('start_date') === null),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsible.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('improvementActionTaskStatus.label')
                    ->label('Status')
                    ->sortable()
                    ->badge()
                    ->color(fn ($record) => $record->improvementActionTaskStatus->colorName()),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deadline')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('actual_start_date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            ->filters([
                //
            ])
            ->headerActions([
                /* Tables\Actions\CreateAction::make(), */
                Tables\Actions\Action::make('create')
                    ->label('New improvement action task')
                    ->button()
                    ->color('primary')
                    ->authorize(
                        fn () => app(AuthService::class)->canCreateTask($this->getOwnerRecord()->responsible_id, $this->getOwnerRecord()->improvement_action_status_id)
                    )
                    ->url(fn () => ImprovementActionResource::getUrl('improvement_action_tasks.create', [
                        'improvementactionId' => $this->getOwnerRecord()->id,
                    ])),
            ])
            ->actions([
                /* Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(), */
                Tables\Actions\Action::make('follow-up')
                    ->label('Follow-up')
                    ->color('primary')
                    ->icon('heroicon-o-eye')
                    ->authorize(
                        fn () => app(AuthService::class)->canCreateTask($this->getOwnerRecord()->responsible_id, $this->getOwnerRecord()->improvement_action_status_id)
                    )
                    ->url(fn ($record) => ImprovementActionResource::getUrl('improvement_action_tasks.view', [
                        'improvementactionId' => $this->getOwnerRecord()->id,
                        'record' => $record->id,
                    ])),
            ])
            ->bulkActions([
            /* Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]), */]);
    }
}
