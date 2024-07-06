<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Contact;
use App\Events\ContactFormSubmitted;
use App\Services\ContactService;
use Log;

class ContactController extends Controller
{
  protected $contactService;

  public function __construct(ContactService $contactService)
  {
      $this->contactService = $contactService;
  }
  
  public function create(): Response
  {
    return Inertia::render('Contact/ContactForm');
  }
  
  public function store(Request $request): RedirectResponse|bool
  {    
    $request->validate([
        'name' => 'required|string|max:100',
        'email' => 'required|email|max:255',
        'message' => 'required|string|max:255',
    ]);

    try {
        $this->contactService->createContact($request);
    } catch (\Exception $e) {
        report($e);
        return false;
    }

    return redirect()->route('contact.create')->with('success', '送信しました');
  }

}
