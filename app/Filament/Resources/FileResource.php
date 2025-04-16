<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FileResource\Pages;
use App\Filament\Resources\FileResource\RelationManagers;
use App\Models\File;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use App\Filament\Resources\RecordResource;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Textarea;
use App\Models\Status;


class FileResource extends Resource
{
    protected static ?string $model = File::class;

    protected static ?string $navigationGroup = 'System Management';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('File Data')
                    ->columns(3)
                    ->schema([
                        Forms\Components\Hidden::make('record_id')
                            ->required()
                            ->default(request()->query('record_id')),
                        Forms\Components\FileUpload::make('file_path')
                            ->label('File')
                            ->storeFileNamesIn('title')
                            ->disk('public')
                            ->directory('records/files')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextArea::make('comments')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('file_path')
                    ->label('File')
                    ->formatStateUsing(fn($state, $record) => $record->getDownloadButtonHtml())
                    ->html(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status.title')
                    ->searchable()
                    ->badge()
                    ->colors([
                        'success' => 'Approve',
                        'danger' => 'Rejected',
                        'info' => 'Pending',
                    ])
                    ->formatStateUsing(fn ($state, $record) => $record->status->display_name),
                Tables\Columns\TextColumn::make('version')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('comments')
                    ->searchable(),
                Tables\Columns\TextColumn::make('responses')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Created by')
                    ->numeric()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('record.title')
                    ->numeric(),
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
                Action::make('restore')
                    ->label(fn ($record) => Status::displayNameFromTitle('Restore') ?? 'Restore')
                    ->icon('heroicon-o-arrow-path')
                    ->authorize(fn ($record) => auth()->user()->can('create_file', $record))
                    ->form([
                        Textarea::make('comment')
                            ->label('Confirm the restoration')
                            ->required()
                            ->placeholder('¿Reason for restore?'),
                    ])
                    ->color('warning')
                    ->action(function ($record, array $data) {
                        redirect(FileResource::getUrl('restore', [
                            'record' => $record->id,
                            'record_id' => $record->record_id,
                            'comment' => $data['comment'],
                        ]));
                    })
                    ->visible(fn ($record) =>
                        $record->id !== \App\Models\File::where('record_id', $record->record_id)
                            ->orderByDesc('version')
                            ->first()?->id
                    ),

                Action::make('approved')
                    ->label(fn ($record) => Status::displayNameFromTitle('Approved') ?? 'Approved')
                    ->icon('heroicon-o-check')
                    ->requiresConfirmation()
                    ->color('success')
                    ->action(function ($record) {
                        redirect(FileResource::getUrl('approved', [
                            'record' => $record->id,
                            'record_id' => $record->record_id,
                        ]));
                    })
                    ->visible(fn ($record) =>
                        auth()->user()->hasRole('super_admin') &&
                        $record->status_id === 1 &&
                        $record->isLatestVersion()
                    ),

                Action::make('rejected')
                    ->label(fn ($record) => Status::displayNameFromTitle('Rejected') ?? 'Rejected')
                    ->icon('heroicon-o-x-circle')
                    ->form([
                        Textarea::make('responses')
                            ->label('Confirm Rejection')
                            ->required()
                            ->placeholder('¿Reason for rejected?'),
                    ])
                    ->color('danger')
                    ->action(function ($record, array $data) {
                        redirect(FileResource::getUrl('rejected', [
                            'record' => $record->id,
                            'record_id' => $record->record_id,
                            'responses' => $data['responses'],
                        ]));
                    })
                    ->visible(fn ($record) =>
                        auth()->user()->hasRole('super_admin') &&
                        $record->status_id === 1 &&
                        $record->isLatestVersion()
                    ),

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
            'index' => Pages\ListFiles::route('/'),
            'create' => Pages\CreateFile::route('/create'),
            'restore' => Pages\RestoreFile::route('/restore/{record}'),
            'approved' => Pages\ApprovedFile::route('/approved/{record}'),
            'rejected' => Pages\RejectedFile::route('/rejected/{record}'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

}
