<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Gate::policy(\App\Models\User::class, \App\Policies\UserPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Blog::class, \App\Policies\BlogPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Category::class, \App\Policies\CategoryPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Appointment::class, \App\Policies\AppointmentPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\BlockedSlot::class, \App\Policies\BlockedSlotPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Location::class, \App\Policies\LocationPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Patient::class, \App\Policies\PatientPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\PatientRecord::class, \App\Policies\PatientRecordPolicy::class);

        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            return $user->hasRole('super_admin') ? true : null;
        });
    }
}
