<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BeforeAfterResource\Pages;
use App\Models\BeforeAfter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BeforeAfterResource extends Resource
{
    protected static ?string $model = BeforeAfter::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Content related';
    
    protected static ?string $modelLabel = 'Before & After';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\FileUpload::make('before_image')
                    ->image()
                    ->required()
                    ->directory('before-afters/before'),
                Forms\Components\FileUpload::make('after_image')
                    ->image()
                    ->required()
                    ->directory('before-afters/after'),
                Forms\Components\Toggle::make('is_active')
                    ->required()
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('before_image')
                    ->square(),
                Tables\Columns\ImageColumn::make('after_image')
                    ->square(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
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
            'index' => Pages\ListBeforeAfters::route('/'),
            'create' => Pages\CreateBeforeAfter::route('/create'),
            'edit' => Pages\EditBeforeAfter::route('/{record}/edit'),
        ];
    }
}
