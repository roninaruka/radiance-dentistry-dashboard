<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Client related';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Client Information')
                    ->schema([
                        Forms\Components\Select::make('patient_id')
                            ->relationship('patient', 'name')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {
                                if (empty($state)) {
                                    return;
                                }
                                $patient = Patient::find($state);
                                if ($patient) {
                                    $set('name', $patient->name);
                                    $set('email', $patient->email);
                                    $set('phone', $patient->phone);
                                }
                            })
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')->required(),
                                Forms\Components\TextInput::make('email')->email(),
                                Forms\Components\TextInput::make('phone')->tel(),
                            ])
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('name')->required(),
                        Forms\Components\TextInput::make('email')->email(),
                        Forms\Components\TextInput::make('phone')->tel(),
                        Forms\Components\Textarea::make('reason')->columnSpanFull(),
                        Forms\Components\Textarea::make('note')->columnSpanFull(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Booking Details')
                    ->schema([
                        Forms\Components\Select::make('doctor_id')
                            ->label('Assigned Doctor')
                            ->options(User::role('doctor')->pluck('name', 'id'))
                            ->searchable()
                            ->nullable()
                            ->placeholder('No specific doctor')
                            ->columnSpanFull(),
                        Forms\Components\DatePicker::make('appointment_date')->required(),
                        Forms\Components\TimePicker::make('appointment_time')
                            ->required()
                            ->step(1800), // 30 min
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('pending'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('patient.name')
                    ->label('Patient')
                    ->searchable()
                    ->sortable()
                    ->url(fn (Appointment $record): ?string => $record->patient_id ? PatientResource::getUrl('edit', ['record' => $record->patient_id]) : null),
                Tables\Columns\TextColumn::make('doctor.name')
                    ->label('Doctor')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('phone')->searchable(),
                Tables\Columns\TextColumn::make('appointment_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('appointment_time')->time('H:i')->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'completed' => 'info',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Confirm')
                    ->action(fn (Appointment $record) => $record->update(['status' => 'confirmed']))
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->hidden(fn (Appointment $record) => $record->status !== 'pending'),
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
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
