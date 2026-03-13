<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment;

class AppointmentCancelledDoctorEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Appointment $appointment)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Appointment Cancelled: ' . $this->appointment->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.appointments.cancelled_doctor',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
