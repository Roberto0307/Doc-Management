<?php

namespace App\Filament\Resources\ImprovementActionTaskResource\RelationManagers;

use App\Filament\Resources\ImprovementActionResource;
use App\Services\AuthService;
use App\Services\TaskService;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ImprovementActionTaskCommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'improvementActionTaskComments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('comment')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            // ->recordTitleAttribute('comment')
            ->columns([
                Tables\Columns\TextColumn::make('comment')
                    ->extraAttributes([
                        'style' => 'max-width: 400px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;',
                    ])
                    ->tooltip(fn ($record) => $record->comment),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                /* Tables\Actions\CreateAction::make(), */
                Tables\Actions\Action::make('create')
                    ->label('New task comment')
                    ->button()
                    ->color('primary')
                    ->form([
                        Textarea::make('comment')
                            ->label('Comment')
                            ->required()
                            ->rule('string')
                            ->placeholder('Follow up comment'),
                    ])
                    ->authorize(
                        fn () => app(AuthService::class)->canTaskUploadFollowUp($this->getOwnerRecord())
                    )
                    ->action(function (array $data) {
                        app(TaskService::class)->createComment($this->getOwnerRecord(), $data);
                        redirect(ImprovementActionResource::getUrl('improvement_action_tasks.view', [
                            'improvementactionId' => $this->getOwnerRecord()->improvement_action_id,
                            'record' => $this->getOwnerRecord()->id,
                        ]));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }
}
