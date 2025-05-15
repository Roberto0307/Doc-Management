<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ImprovementActionCompletionResource\Pages;
use App\Filament\Resources\ImprovementActionCompletionResource\RelationManagers;
use App\Models\ImprovementActionCompletion;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ImprovementActionCompletionResource extends Resource
{
    protected static ?string $model = ImprovementActionCompletion::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Completion Data')
                    ->description('Enter the completion data and upload your supports')
                    ->schema([
                        Forms\Components\Textarea::make('real_impact')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('result')
                            ->required()
                            ->columnSpanFull(),
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
                            ->columnSpanFull()
                            ->visible(fn (string $context) => $context === 'create'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('improvement_action_id')
                    ->numeric()
                    ->sortable(),
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
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListImprovementActionCompletions::route('/'),
            'create' => Pages\CreateImprovementActionCompletion::route('/create'),
            'edit' => Pages\EditImprovementActionCompletion::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
