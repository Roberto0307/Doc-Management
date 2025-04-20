<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FileResource\Pages;
use App\Models\File;
use App\Models\Status;
use App\Services\AuthService;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Table;

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
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',       // .xlsx
                            ])
                            ->rules(['mimes:pdf,doc,docx,xlsx'])
                            ->maxSize(10240) // en KB, 10MB ejemplo
                            ->columnSpanFull(),
                        TextArea::make('comments')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('file_path')
                    ->label('File')
                    ->formatStateUsing(fn ($state, $record) => $record->getDownloadButtonHtml())
                    ->html(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status.display_name')
                    ->searchable()
                    ->badge()
                    ->color(fn ($state, $record) => Status::colorFromId($record->status_id)
                    ),
                Tables\Columns\TextColumn::make('version')
                    ->searchable(),
                Tables\Columns\TextColumn::make('comments')
                    ->searchable(),
                Tables\Columns\TextColumn::make('responses')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Created by')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('record.title')
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
            ->actions([

                Action::make('pending')
                    ->label(fn ($record) => Status::displayNameFromTitle('Pending') ?? 'Pending')
                    ->icon('heroicon-o-pause-circle')
                    ->form([
                        Textarea::make('responses')
                            ->label('Confirm Pending')
                            ->required()
                            ->placeholder('¿Are you sure you want to leave Pending?'),
                    ])
                    ->color('info')
                    ->action(function ($record, array $data) {
                        redirect(FileResource::getUrl('pending', [
                            'record' => $record->id,
                            'record_id' => $record->record_id,
                            'responses' => $data['responses'],
                        ]));
                    })
                    ->visible(function ($record) {
                        return app(AuthService::class)->canPending(auth()->user(), $record)
                            && $record->status_id === 1;
                    }),

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
                    ->visible(fn ($record) => $record->id !== File::where('record_id', $record->record_id)
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
                    ->visible(function ($record) {
                        return app(AuthService::class)->canApprove(
                            auth()->user(),
                            $record->record->sub_process_id ?? null
                        ) && $record->status_id === 2 && $record->isLatestVersion();
                    }),

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
                    ->visible(function ($record) {
                        return app(AuthService::class)->canApprove(
                            auth()->user(),
                            $record->record->sub_process_id ?? null
                        ) && $record->status_id === 2 && $record->isLatestVersion();
                    }),

                DeleteAction::make()
                    ->visible(function ($record) {

                        $user = Filament::auth()->user();

                        return $user && $user->hasRole('super_admin');
                    }),

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
            'pending' => Pages\PendingFile::route('/pending/{record}'),
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
