<?php

namespace App\Listeners;

use App\Events\ContactFormSubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;
use Log;

class SaveContactWithUserAgent
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
    public function handle(ContactFormSubmitted $event): void
    {
        try {
            Log::info('Contact form email sending started', ['data' => $event]);
            Mail::send([], [], function ($message) use ($event) {
              $message->from('xxxxxxxxx@gmail.com', "管理者")
                      ->to($event->formData["email"], $event->formData["name"])
                      ->subject('テストメール')
                      ->text('お問い合わせありがとうございます');
            });

            Log::info('Contact form email sending completed');
        }
        catch (\Exception $e) {
            Log::error('Failed to send contact form email', ['error' => $e->getMessage()]);
        }
        

    }
}
