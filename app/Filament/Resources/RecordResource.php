<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecordResource\Pages;
use App\Models\Record;
use App\Models\SubProcess;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class RecordResource extends Resource
{
    protected static ?string $model = Record::class;

    protected static ?string $navigationGroup = 'Record Management';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Record Data')
                    ->columns(2)
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
                            ->required(),
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
                            ->required(),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
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
                    ->color(fn ($record) => $record->latestFile?->status->badgeColor()),
                Tables\Columns\TextColumn::make('latestFile.version')
                    ->label('Version')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
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
            ])
            ->actions([
                ActionGroup::make([

                    Action::make('LastfileApproved')
                        ->label('Download')
                        ->icon('heroicon-o-document')
                        ->color('success')
                        ->url(fn ($record) => $record->approvedVersionUrl()
                        )
                        ->openUrlInNewTab(false)
                        ->extraAttributes(fn ($record) => [
                            'download' => $record->title,
                        ])
                        ->visible(fn ($record) => $record->hasApprovedVersion()
                        ),

                    Action::make('Files')
                        ->label('Versions')
                        ->icon('heroicon-o-document')
                        ->color('info')
                        ->url(fn (Record $record): string => FileResource::getUrl('index', ['record_id' => $record->id])
                        )
                        ->visible(fn ($record) => $record->canBeAccessedBy(auth()->user())
                        ),

                    DeleteAction::make()
                        ->visible(fn ($record): bool => auth()->user()?->can('delete', $record)),

                ])->color('info')->link()->label(false)->tooltip('Actions'),
            ])
            ->actionsPosition(Tables\Enums\ActionsPosition::BeforeCells)
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
            'index' => Pages\ListRecords::route('/'),
            'create' => Pages\CreateRecord::route('/create'),
        ];
    }
}
