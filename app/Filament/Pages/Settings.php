<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static string $view = 'filament.pages.settings';
    protected static ?string $navigationGroup = 'System';
    protected static ?int $navigationSort = 99;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'booking_amount' => Setting::get('booking_amount', 500),
            'clinic_name' => Setting::get('clinic_name', 'Radiance Dentistry'),
            'clinic_email' => Setting::get('clinic_email', 'support@radiancedentistryclinic.com'),
            'clinic_phone' => Setting::get('clinic_phone', ''),
            'working_hours' => Setting::get('working_hours', 'Mon-Fri: 9:00 AM - 6:00 PM'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Booking Settings')
                    ->schema([
                        TextInput::make('booking_amount')
                            ->label('Booking Amount')
                            ->numeric()
                            ->required()
                            ->prefix('₹')
                            ->helperText('Default amount charged for appointments'),
                    ]),
                Section::make('Clinic Information')
                    ->schema([
                        TextInput::make('clinic_name')
                            ->label('Clinic Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('clinic_email')
                            ->label('Clinic Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('clinic_phone')
                            ->label('Clinic Phone')
                            ->tel()
                            ->maxLength(20),
                        Textarea::make('working_hours')
                            ->label('Working Hours')
                            ->rows(3)
                            ->helperText('Display format for clinic working hours'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::set('booking_amount', $data['booking_amount'], 'number', 'Default booking amount');
        Setting::set('clinic_name', $data['clinic_name'], 'string', 'Clinic name');
        Setting::set('clinic_email', $data['clinic_email'], 'string', 'Clinic email');
        Setting::set('clinic_phone', $data['clinic_phone'], 'string', 'Clinic phone');
        Setting::set('working_hours', $data['working_hours'], 'string', 'Working hours');

        Notification::make()
            ->success()
            ->title('Settings saved')
            ->body('Your settings have been saved successfully.')
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->submit('save'),
        ];
    }
}
