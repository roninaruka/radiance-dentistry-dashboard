<?php

namespace App\Observers;

use App\Models\Appointment;
use App\Models\User;
use App\Mail\AppointmentConfirmed;
use App\Mail\AppointmentConfirmedDoctor;
use App\Mail\AppointmentCancelledEmail;
use App\Mail\AppointmentCancelledDoctorEmail;
use App\Mail\AppointmentPendingDoctorEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AppointmentObserver
{
    /**
     * Handle the Appointment "created" event.
     */
    public function created(Appointment $appointment): void
    {
        try {
            // Notify the patient that their request was received
            Mail::to($appointment->email)->send(new \App\Mail\AppointmentPendingPatientEmail($appointment));

            // Notify all doctors about a new pending appointment request
            $doctors = User::role('doctor')->get();
            foreach ($doctors as $doctor) {
                Mail::to($doctor->email)->send(new \App\Mail\AppointmentPendingDoctorEmail($appointment));
            }
        } catch (\Exception $e) {
            Log::error('Mail failed (Appointment Created): ' . $e->getMessage());
        }
    }

    /**
     * Handle the Appointment "updated" event.
     */
    public function updated(Appointment $appointment): void
    {
        if ($appointment->isDirty('status')) {
            $newStatus = $appointment->status;
            $oldStatus = $appointment->getOriginal('status');

            if ($newStatus === 'confirmed' && $oldStatus !== 'confirmed') {
                $this->sendConfirmationEmails($appointment);
            } elseif ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                $this->sendCancellationEmails($appointment);
            }
        }
    }

    /**
     * Send confirmation emails to patient and doctors.
     */
    protected function sendConfirmationEmails(Appointment $appointment): void
    {
        try {
            // To Patient
            Mail::to($appointment->email)->send(new AppointmentConfirmed($appointment));
            
            // To Doctors
            $doctors = User::role('doctor')->get();
            foreach ($doctors as $doctor) {
                Mail::to($doctor->email)->send(new AppointmentConfirmedDoctor($appointment));
            }
        } catch (\Exception $e) {
            Log::error('Mail failed (Appointment Confirmed): ' . $e->getMessage());
        }
    }

    /**
     * Send cancellation emails to patient and doctors.
     */
    protected function sendCancellationEmails(Appointment $appointment): void
    {
        try {
            // To Patient
            Mail::to($appointment->email)->send(new AppointmentCancelledEmail($appointment));

            // To Doctors
            $doctors = User::role('doctor')->get();
            foreach ($doctors as $doctor) {
                Mail::to($doctor->email)->send(new AppointmentCancelledDoctorEmail($appointment));
            }
        } catch (\Exception $e) {
            Log::error('Mail failed (Appointment Cancelled): ' . $e->getMessage());
        }
    }
}
