<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PatientResource;
use App\Models\Patient;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestPatients extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected static ?int $sort = 4;
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Patient::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registered At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (Patient $record): string => PatientResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
