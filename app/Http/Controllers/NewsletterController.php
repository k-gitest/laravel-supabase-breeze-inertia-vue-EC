<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Services\NewsletterService;
use App\Http\Requests\NewsletterRequest;
use App\Models\User;
use Log;

class NewsletterController extends Controller
{
    protected $newsletterService;

    public function __construct(NewsletterService $newsletterService)
    {
        $this->newsletterService = $newsletterService;
    }
    
    public function update(NewsletterRequest $request): RedirectResponse|bool
    {
        try {
            // $this->newsletterService->updateSubscription($request, auth()->user()->id);
            // auth()->id() でログインユーザーIDを取得し、
            // $request->boolean() で確実に論理値として渡す
            $this->newsletterService->updateSubscription(
                auth()->id(), 
                $request->boolean('subscribed')
            );
        } catch (\Exception $e) {
            report($e);
            return false;
        }

        return redirect()->back()->with('success', 'Subscription updated!');
    }
}
