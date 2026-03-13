<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Mail\Events\MessageSent;
use App\Models\EmailLog;

class LogSentEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MessageSent $event): void
    {
        $message = $event->message;

        $recipients = [];
        foreach ($message->getTo() as $address) {
            $recipients[] = $address->getAddress();
        }

        EmailLog::create([
            'recipient' => implode(', ', $recipients),
            'subject' => $message->getSubject(),
            'body' => $message->getHtmlBody() ?: $message->getTextBody(),
            'headers' => $message->getHeaders()->toArray(),
            'status' => 'sent',
        ]);
    }
}
