<?php

namespace App\Listeners;

use App\Events\ContactFormSubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;

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
        //
      Mail::send([], [], function ($message) use ($event) {
          $message->from('xxxxxxxxx@gmail.com', "管理者")
                  ->to($event->formData["email"], $event->formData["name"])
                  ->subject('テストメール')
                  ->text('テストメールだよ');
      });

    }
}
