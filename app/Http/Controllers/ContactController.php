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
use App\Services\Admin\AdminImageService;
use Log;

class ContactController extends Controller
{
  protected $adminImageService;

  public function __construct(AdminImageService $adminImageService)
  {
    $this->adminImageService = $adminImageService;
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
      DB::transaction(function () use ($request)
      {
          $filenames = $this->adminImageService->handleContactImageUploads($request);

          $chua_mobile = $request->header('Sec-Ch-Ua-Mobile');
          $device = ($chua_mobile === '?0') ? "desktop" : (($chua_mobile === null) ? "unknown" : "mobile");
        
          try{
            Contact::create([
              'name' => $request->name,
              'email' => $request->email,
              'message' => $request->message,
              'ip_address' => $request->ip(),
              'user_agent' => $request->userAgent(),
              'language' => $request->header('Accept-Language'),
              'previous_url' => $request->session()->get('_previous')['url'] ?? "unknown",
              'referrer' => $request->header('Referer'),
              'device' => $device,
              'platform' => trim($request->header('Sec-Ch-Ua-Platform'), '"'),
              'browser' => $request->header('sec-ch-ua'),
              'attachments' => $filenames,
            ]);
            Log::info('Contact create succeeded');
          }
          catch(\Exception $e){
            $this->adminImageService->deleteUploadImages($filenames, 'key');
            throw $e;
          }
          
      });
    }
    catch (\Exception $e){
      report($e);
      return false;
    }

    event(new ContactFormSubmitted($request->all()));
    
    return redirect()->route('contact.create')->with('success', '送信しました');
  }

}
