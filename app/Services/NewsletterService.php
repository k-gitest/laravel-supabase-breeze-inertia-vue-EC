<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NewsletterService
{
    public function updateSubscription(Request $request, $userId)
    {
        $request->validate([
            'subscribed' => ['required', 'boolean'],                
        ]);

        $user = User::findOrFail($userId);
        $user->subscribed = $request->subscribed;

        try {
            DB::transaction(function () use ($user) {
                if ($user->isDirty('subscribed')) {
                    $user->save();
                }
            });
            Log::info('Newsletter update succeeded');
        } catch (\Exception $e) {
            report($e);
            throw $e;
        }
    }
}
