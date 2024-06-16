<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Log;

class NewsletterController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
           'subscribed' => ['required', 'boolean'],                
        ]);
        $user = User::find(auth()->user()->id);
        $user->subscribed = $request->subscribed;

        try{
            DB::transaction(function () use ($request){
                if( $user->isDirty('subscribed') ){
                    $user->save();
                }
            });
            Log::info('Newsletter update succeeded');
        }
        catch(\Exception $e){
            Log::error( 'Failed to update newsletter.', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Failed to update newsletter. Please try again.']);
        }
        
        return redirect()->route('profile.edit')->with('status', 'Subscription updated!');
    }
}
