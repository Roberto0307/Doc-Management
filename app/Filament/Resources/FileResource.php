<?php

namespace App\Filament\Resources;

use App\Models\File;
use App\Models\Status;
use App\Services\AuthService;
use App\Services\ComplementService;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
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
                    ->schema([
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
                            ->maxSize(10240) // en KB, 10MB ejemplo
                            ->helperText('Allowed types: PDF, DOC, DOCX, XLS, XLSX (max. 10MB)')
                            ->columnSpanFull(),
                        TextArea::make('comments')
                            ->required()
                            ->maxLength(255)
                            ->rule('string')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status.label')
                    ->searchable()
                    ->badge()
                    ->color(fn ($record) => $record->status->colorName()),
                Tables\Columns\TextColumn::make('version')
                    ->searchable(),
                Tables\Columns\TextColumn::make('comments')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('change_reason')
                    ->label('Reason for change')
                    ->searchable()
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Created by')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('decidedBy.name')
                    ->label('Decided By')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('decision_at')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('sha256_hash')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([

                    Action::make('pending')
                        ->label(fn ($record) => Status::labelFromTitle('pending') ?? 'Pending')
                        ->icon(fn ($record) => Status::iconFromTitle('pending') ?? 'heroicon-o-information-circle')
                        ->color(fn ($record) => Status::colorFromTitle('pending') ?? 'gray')
                        ->requiresConfirmation()
                        ->action(function ($record, array $data) {
                            redirect(RecordResource::getUrl('files.pending', [
                                'recordId' => $record->record_id,
                                'file' => $record->id,
                            ]));
                        })
                        ->visible(function ($record) {
                            return app(AuthService::class)->canPending(auth()->user(), $record)
                                && $record->status_id === 1
                                && $record->isLatestVersion();
                        }),

                    Action::make('restore')
                        ->label(fn ($record) => Status::labelFromTitle('restore') ?? 'Restore')
                        ->icon(fn ($record) => Status::iconFromTitle('restore') ?? 'heroicon-o-information-circle')
                        ->color(fn ($record) => Status::colorFromTitle('restore') ?? 'gray')
                        ->authorize(fn ($record) => auth()->user()->can('create_file', $record))
                        ->form([
                            Textarea::make('comment')
                                ->label('Confirm the restoration')
                                ->required()
                                ->maxLength(255)
                                ->rule('string')
                                ->placeholder('¿Reason for restore?'),
                        ])
                        ->action(function ($record, array $data) {
                            redirect(RecordResource::getUrl('files.restore', [
                                'recordId' => $record->record_id,
                                'file' => $record->id,
                                'comment' => $data['comment'],
                            ]));
                        })
                        ->visible(
                            fn ($record) => $record->id !== File::where('record_id', $record->record_id)
                                ->orderByDesc('version')
                                ->first()?->id
                        ),

                    Action::make('approved')
                        ->label(fn ($record) => Status::labelFromTitle('approved') ?? 'Approved')
                        ->icon(fn ($record) => Status::iconFromTitle('approved') ?? 'heroicon-o-information-circle')
                        ->color(fn ($record) => Status::colorFromTitle('approved') ?? 'gray')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            redirect(RecordResource::getUrl('files.approved', [
                                'recordId' => $record->record_id,
                                'file' => $record->id,
                            ]));
                        })
                        ->visible(function ($record) {
                            return app(AuthService::class)->canApproveAndReject(
                                auth()->user(),
                                $record->record->sub_process_id ?? null
                            ) && $record->status_id === 2 && $record->isLatestVersion();
                        }),

                    Action::make('rejected')
                        ->label(fn ($record) => Status::labelFromTitle('rejected') ?? 'Rejected')
                        ->icon(fn ($record) => Status::iconFromTitle('rejected') ?? 'heroicon-o-information-circle')
                        ->color(fn ($record) => Status::colorFromTitle('rejected') ?? 'gray')
                        ->form([
                            Textarea::make('change_reason')
                                ->label('Confirm Rejection')
                                ->required()
                                ->maxLength(255)
                                ->rule('string')
                                ->placeholder('¿Reason for rejected?'),
                        ])
                        ->action(function ($record, array $data) {
                            redirect(RecordResource::getUrl('files.rejected', [
                                'recordId' => $record->record_id,
                                'file' => $record->id,
                                'change_reason' => $data['change_reason'],
                            ]));
                        })
                        ->visible(function ($record) {
                            return app(AuthService::class)->canApproveAndReject(
                                auth()->user(),
                                $record->record->sub_process_id ?? null
                            ) && $record->status_id === 2 && $record->isLatestVersion();
                        }),
                    Action::make('file')
                        ->label('Download')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('primary')
                        ->url(
                            fn ($record) => app(ComplementService::class)->getDownloadUrl($record)
                        )
                        ->openUrlInNewTab(false)
                        ->extraAttributes(fn ($record) => [
                            'download' => $record->title,
                        ])
                        ->visible(
                            fn ($record) => $record->record->canBeAccessedBy(auth()->user())
                        ),

                    DeleteAction::make()
                        ->visible(function ($record) {
                            $user = auth()->user();

                            return $user && $user->hasRole('super_admin');
                        }),
                ])->color('primary')->link()->label(false)->tooltip('Actions'),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
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
            //
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
