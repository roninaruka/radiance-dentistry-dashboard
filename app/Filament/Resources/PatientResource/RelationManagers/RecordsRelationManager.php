<?php

namespace App\Filament\Resources\PatientResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RecordsRelationManager extends RelationManager
{
    protected static string $relationship = 'records';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->options([
                        'note' => 'Note',
                        'image' => 'Image/Scan',
                    ])
                    ->required()
                    ->default('note')
                    ->reactive(),
                Forms\Components\DatePicker::make('record_date')
                    ->default(now())
                    ->required(),
                Forms\Components\Textarea::make('content')
                    ->label('Entry Content')
                    ->placeholder('Enter clinical notes here...')
                    ->columnSpanFull()
                    ->required(fn ($get) => $get('type') === 'note'),
                Forms\Components\FileUpload::make('attachments')
                    ->label('Images / Documents')
                    ->multiple()
                    ->image()
                    ->directory('patient-records')
                    ->columnSpanFull()
                    ->required(fn ($get) => $get('type') === 'image'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->columns([
                Tables\Columns\TextColumn::make('record_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'note' => 'info',
                        'image' => 'success',
                    }),
                Tables\Columns\TextColumn::make('content')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\ImageColumn::make('attachments')
                    ->circular()
                    ->stacked()
                    ->limit(3),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'note' => 'Note',
                        'image' => 'Image',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('record_date', 'desc');
    }
}
