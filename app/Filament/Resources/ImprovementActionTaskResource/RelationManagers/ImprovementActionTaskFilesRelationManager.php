<?php

namespace App\Filament\Resources\ImprovementActionTaskResource\RelationManagers;

use App\Filament\Resources\ImprovementActionResource;
use App\Services\AuthService;
use App\Services\ComplementService;
use App\Services\TaskService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ImprovementActionTaskFilesRelationManager extends RelationManager
{
    protected static string $relationship = 'improvementActionTaskFiles';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('attachments')
                    ->label('Support files')
                    ->storeFileNamesIn('title')
                    ->disk('public')
                    ->directory('improvement_action_completion/task/files')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',       // .xlsx
                    ])
                    ->maxSize(10240) // en KB, 10MB ejemplo
                    ->helperText('Allowed types: PDF, DOC, DOCX, XLS, XLSX (max. 10MB)')
                    ->multiple()
                    ->maxParallelUploads(1)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('file_name')
            ->columns([
                Tables\Columns\TextColumn::make('file_name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                /* Tables\Actions\CreateAction::make(), */
                Tables\Actions\Action::make('create')
                    ->label('New task support files')
                    ->button()
                    ->color('primary')
                    ->form([
                        Forms\Components\FileUpload::make('attachments')
                            ->label('Support files')
                            ->storeFileNamesIn('title')
                            ->disk('public')
                            ->directory('improvement_action_completion/task/files')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',       // .xlsx
                            ])
                            ->maxSize(10240) // en KB, 10MB ejemplo
                            ->helperText('Allowed types: PDF, DOC, DOCX, XLS, XLSX (max. 10MB)')
                            ->multiple()
                            ->maxParallelUploads(1)
                            ->columnSpanFull(),
                    ])
                    ->authorize(
                        fn () => app(AuthService::class)->canTaskUploadFollowUp($this->getOwnerRecord())
                    )
                    ->action(function (array $data) {
                        app(TaskService::class)->createFiles($this->getOwnerRecord(), $data);
                        redirect(ImprovementActionResource::getUrl('improvement_action_tasks.view', [
                            'improvementactionId' => $this->getOwnerRecord()->improvement_action_id,
                            'record' => $this->getOwnerRecord()->id,
                        ]));
                    }),
            ])
            ->actions([
                //
                Tables\Actions\Action::make('file')
                    ->label('Download')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('primary')
                    ->url(
                        fn ($record) => app(ComplementService::class)->getDownloadUrl($record),
                    )
                    ->openUrlInNewTab(false)
                    ->extraAttributes(fn ($record) => [
                        'download' => $record->file_name,
                    ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
