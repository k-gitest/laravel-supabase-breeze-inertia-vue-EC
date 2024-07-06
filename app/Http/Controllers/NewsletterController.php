<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Services\NewsletterService;
use App\Models\User;
use Log;

class NewsletterController extends Controller
{
    protected $newsletterService;

    public function __construct(NewsletterService $newsletterService)
    {
        $this->newsletterService = $newsletterService;
    }
    
    public function update(Request $request): RedirectResponse
    {
        try {
            $this->newsletterService->updateSubscription($request, auth()->user()->id);
        } catch (\Exception $e) {
            return false;
        }

        return redirect()->back()->with('success', 'Subscription updated!');
    }
}
