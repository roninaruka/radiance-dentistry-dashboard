<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Blog;
use App\Models\Review;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Patients', Patient::count())
                ->description('All registered patients')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            Stat::make('Upcoming Appointments', Appointment::where('appointment_date', '>=', now()->toDateString())->where('status', '!=', 'cancelled')->count())
                ->description('Future bookings')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),
        ];
    }
}
