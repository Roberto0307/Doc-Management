<?php

namespace App\Filament\Resources\ImprovementActionResource\RelationManagers;

use App\Filament\Resources\ImprovementActionResource;
use App\Services\AuthService;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ImprovementActionTasksRelationManager extends RelationManager
{
    protected static string $relationship = 'improvementActionTasks';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->title),
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
                Tables\Actions\Action::make('follow-up')
                    ->label('Follow-up')
                    ->color('primary')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => ImprovementActionResource::getUrl('improvement_action_tasks.view', [
                        'improvementactionId' => $this->getOwnerRecord()->id,
                        'record' => $record->id,
                    ])),
            ])
            ->bulkActions([
            ]);
    }
}
