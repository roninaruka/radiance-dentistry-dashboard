<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlockedSlotResource\Pages;
use App\Models\BlockedSlot;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BlockedSlotResource extends Resource
{
    protected static ?string $model = BlockedSlot::class;

    protected static ?string $navigationIcon = 'heroicon-o-no-symbol';

    protected static ?string $navigationGroup = 'Client related';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')->required(),
                Forms\Components\Toggle::make('is_full_day')
                    ->reactive()
                    ->label('Block Entire Day'),
                Forms\Components\TimePicker::make('start_time')
                    ->hidden(fn (Forms\Get $get) => $get('is_full_day'))
                    ->step(1800),
                Forms\Components\TimePicker::make('end_time')
                    ->hidden(fn (Forms\Get $get) => $get('is_full_day'))
                    ->step(1800),
                Forms\Components\TextInput::make('reason')->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')->date()->sortable(),
                Tables\Columns\IconColumn::make('is_full_day')
                    ->boolean()
                    ->label('Full Day'),
                Tables\Columns\TextColumn::make('start_time')->time('H:i'),
                Tables\Columns\TextColumn::make('end_time')->time('H:i'),
                Tables\Columns\TextColumn::make('reason'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlockedSlots::route('/'),
            'create' => Pages\CreateBlockedSlot::route('/create'),
            'edit' => Pages\EditBlockedSlot::route('/{record}/edit'),
        ];
    }
}
