<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class NewsletterController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        DB::transaction(function () use ($request){
            $request->validate([
               'subscribed' => ['required', 'boolean'],                
            ]);
            $user = User::find(auth()->user()->id);
            $user->subscribed = $request->subscribed;
            if( $user->isDirty('subscribed') ){
                $user->save();
            }
        });
        
        return redirect()->route('profile.edit')->with('status', 'Subscription updated!');
    }
}
