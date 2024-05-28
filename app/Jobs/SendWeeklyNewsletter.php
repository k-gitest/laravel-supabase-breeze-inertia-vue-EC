<?php

namespace App\Jobs;

use App\Mail\WeeklyNewsletter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class SendWeeklyNewsletter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $subscribers = User::all();

        foreach ($subscribers as $subscriber) {
            if( $subscriber->subscribed ){
                Mail::to($subscriber->email)->send(new WeeklyNewsletter($subscriber));
            }
        }
    }
}
