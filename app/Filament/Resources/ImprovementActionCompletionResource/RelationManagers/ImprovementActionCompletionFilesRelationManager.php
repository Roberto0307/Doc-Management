<?php

namespace App\Filament\Resources\ImprovementActionCompletionResource\RelationManagers;

use App\Services\ComplementService;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ImprovementActionCompletionFilesRelationManager extends RelationManager
{
    protected static string $relationship = 'improvementActionCompletionFiles';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                /* Forms\Components\TextInput::make('file_name')
                    ->required()
                    ->maxLength(255), */
                Forms\Components\FileUpload::make('attachments')
                            ->label('Support files')
                            ->storeFileNamesIn('title')
                            ->disk('public')
                            ->directory('improvement_action_completion/files')
                            ->required()
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
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('file')
                    ->label('Download')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('primary')
                    ->url(
                        fn($record) => app(ComplementService::class)->getDownloadUrl($record),
                    )
                    ->openUrlInNewTab(false)
                    ->extraAttributes(fn($record) => [
                        'download' => $record->file_name,
                    ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
