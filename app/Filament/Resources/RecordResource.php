<?php

namespace App\Filament\Resources;

use App\Exports\RecordExport;
use App\Filament\Resources\RecordResource\Pages;
use App\Models\CentralTime;
use App\Models\ManagementTime;
use App\Models\Record;
use App\Models\SubProcess;
use App\Services\RecordService;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class RecordResource extends Resource
{
    protected static ?string $model = Record::class;

    protected static ?string $navigationGroup = 'Records';

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Record Data')
                    ->columns(6)
                    ->schema([

                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->unique()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('type_id')
                            ->relationship('type', 'title')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('process_id')
                            ->relationship('process', 'title')
                            ->afterStateUpdated(fn (Set $set) => $set('sub_process_id', null))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required()
                            ->columnSpan(3),
                        Forms\Components\Select::make('sub_process_id')
                            ->label('Sub Process')
                            ->options(
                                fn (Get $get): Collection => SubProcess::query()
                                    ->where('process_id', $get('process_id'))
                                    ->pluck('title', 'id')
                            )
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required()
                            ->columnSpan(3),
                        Forms\Components\Select::make('management_time_id')
                            ->label('Management Time')
                            ->options(ManagementTime::selectOptions())
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(2),
                        Forms\Components\Select::make('central_time_id')
                            ->label('Central Time')
                            ->options(CentralTime::selectOptions())
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(2),
                        Forms\Components\Select::make('final_disposition_id')
                            ->relationship('finaldisposition', 'label')
                            ->label('Final Disposition')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('classification_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->extraAttributes([
                        'style' => 'max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;',
                    ])
                    ->tooltip(fn ($record) => $record->title),
                Tables\Columns\TextColumn::make('type.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('process.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('subprocess.title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('latestFile.status.label')
                    ->label('State')
                    ->searchable()
                    ->badge()
                    ->color(fn ($record) => $record->latestFile?->status->colorName()),
                Tables\Columns\TextColumn::make('latestFile.version')
                    ->label('Version')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Created by')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('managementtime.year_label')
                    ->label('Management time')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('centraltime.year_label')
                    ->label('Central time')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('finaldisposition.label')
                    ->label('Final disposition')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('Expiration')
                    ->label('Expiration state')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        return app(RecordService::class)->isExpired($record) ? 'Expired' : 'Current';
                    })
                    ->colors([
                        'danger' => 'Expired',
                        'success' => 'Current',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->date('l, d \d\e F \d\e Y')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->sortable()
                    ->date('l, d \d\e F \d\e Y')
                    ->toggleable(isToggledHiddenByDefault: true),

            ])->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('type_id')
                    ->relationship('type', 'title')
                    ->label('Type')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                SelectFilter::make('process_id')
                    ->relationship('process', 'title')
                    ->label('Process')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                SelectFilter::make('sub_process_id')
                    ->relationship('subprocess', 'title')
                    ->label('Sub Process')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                SelectFilter::make('management_time_id')
                    ->label('Management time')
                    ->options(ManagementTime::selectOptions())
                    ->multiple()
                    ->searchable()
                    ->preload(),
                SelectFilter::make('central_time_id')
                    ->label('Central time')
                    ->options(CentralTime::selectOptions())
                    ->multiple()
                    ->searchable()
                    ->preload(),
                SelectFilter::make('final_disposition_id')
                    ->relationship('finaldisposition', 'label')
                    ->label('Final disposition')
                    ->multiple()
                    ->searchable()
                    ->preload(),
            ])
            ->actions([

                Action::make('Files')
                    ->label('Versions')
                    ->icon('heroicon-o-document')
                    ->color('primary')
                    ->url(
                        fn (Record $record): string => RecordResource::getUrl('files.list', ['recordId' => $record->id])
                    )
                    ->disabled(fn ($record) => ! $record->canBeAccessedBy(auth()->user()))
                    ->extraAttributes(fn ($record) => [
                        'style' => $record->canBeAccessedBy(auth()->user())
                            ? ''
                            : 'opacity: 0.3; cursor: not-allowed;',

                    ]),

                Action::make('LastfileApproved')
                    ->label('Download')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('primary')
                    ->url(
                        fn ($record) => $record->approvedVersionUrl()
                    )
                    ->openUrlInNewTab(false)
                    ->disabled(fn ($record) => ! $record->hasApprovedVersion())
                    ->extraAttributes(fn ($record) => [
                        'download' => $record->title,
                        'style' => $record->hasApprovedVersion()
                            ? ''
                            : 'opacity: 0.3; cursor: not-allowed;',

                    ]),

                ActionGroup::make([

                    DeleteAction::make()
                        ->visible(fn ($record): bool => auth()->user()?->can('delete', $record)),

                ])->color('primary')->link()->label(false)->tooltip('Actions'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                    BulkAction::make('export')
                        ->label('Exportar seleccionados')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(fn ($records) => Excel::download(
                            new RecordExport($records->pluck('id')->toArray()),
                            'registros_'.now()->format('Y_m_d_His').'.xlsx'
                        )),
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
            'index' => Pages\ListRecords::route('/'),
            'create' => Pages\CreateRecord::route('/create'),
            'files.list' => \App\Filament\Resources\FileResource\Pages\ListFiles::route('/{recordId}/files'),
            'files.create' => \App\Filament\Resources\FileResource\Pages\CreateFile::route('/{recordId}/files/create'),
            'files.pending' => \App\Filament\Resources\FileResource\Pages\PendingFile::route('/{recordId}/files/pending/{file}'),
            'files.restore' => \App\Filament\Resources\FileResource\Pages\RestoreFile::route('/{recordId}/files/restore/{file}'),
            'files.approved' => \App\Filament\Resources\FileResource\Pages\ApprovedFile::route('/{recordId}/files/approved/{file}'),
            'files.rejected' => \App\Filament\Resources\FileResource\Pages\RejectedFile::route('/{recordId}/files/rejected/{file}'),
        ];
    }
}
