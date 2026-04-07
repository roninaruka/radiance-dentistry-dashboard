<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\AppointmentResource;
use App\Models\Appointment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestAppointments extends BaseWidget
{
    protected static ?string $heading = 'Today & Upcoming Bookings';

    protected static ?string $pollingInterval = null;

    protected static ?int $sort = 1;
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn () => Appointment::query()
                    ->where('appointment_date', '>=', now()->toDateString())
                    ->where('status', '!=', 'cancelled')
                    ->orderBy('appointment_date')
                    ->orderBy('appointment_time')
            )
            ->columns([
                Tables\Columns\TextColumn::make('appointment_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('appointment_time')
                    ->label('Time')
                    ->time('H:i'),
                Tables\Columns\TextColumn::make('patient.name')
                    ->label('Patient')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (Appointment $record): string => AppointmentResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
