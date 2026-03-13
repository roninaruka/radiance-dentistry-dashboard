<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentPendingDoctorEmail;

class CheckPendingAppointmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $appointment;

    /**
     * Create a new job instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Refresh appointment from database
        $this->appointment->refresh();

        if ($this->appointment->status === 'pending') {
            // Find all doctor users
            $doctors = User::role('doctor')->get();

            foreach ($doctors as $doctor) {
                Mail::to($doctor->email)->send(new AppointmentPendingDoctorEmail($this->appointment));
            }
        }
    }
}
