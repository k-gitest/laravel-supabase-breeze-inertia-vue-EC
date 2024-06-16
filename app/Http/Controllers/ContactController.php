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
use Log;

class ContactController extends Controller
{
  public function create(): Response
  {
    return Inertia::render('Contact/ContactForm');
  }
  
  public function store(Request $request): RedirectResponse
  {
    Log::info('Validation started', ['request' => $request->all()]);
    
    $validated = $request->validate([
      'name' => 'required|string|max:100',
      'email' => 'required|email|max:255',
      'message' => 'required|string|max:255',
    ]);
    
    Log::info('Validation succeeded');

    try {
        $filenames = $this->handleImageUploads($request);
    } catch (\Exception $e) {
        Log::error('Image upload failed.', ['error' => $e->getMessage()]);
        return redirect()->back()->withErrors(['error' => 'Failed to upload image. Please try again.']);
    }

    $chua_mobile = $request->header('Sec-Ch-Ua-Mobile');
    $device = ($chua_mobile === '?0') ? "desktop" : (($chua_mobile === null) ? "unknown" : "mobile");
    
    try {
      DB::transaction(function () use ($request, $device, $filenames)
      {
          Log::info('Contact create start');
          Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'language' => $request->header('Accept-Language'),
            'previous_url' => $request->session()->get('_previous')['url'],
            'referrer' => $request->header('Referer'),
            'device' => $device,
            'platform' => trim($request->header('Sec-Ch-Ua-Platform'), '"'),
            'browser' => $request->header('sec-ch-ua'),
            'attachments' => $filenames,
          ]);
          Log::info('Contact create succeeded');
      });
    }
    catch (\Exception $e){
      Log::error('Failed to submit contact form.', ['error' => $e->getMessage()]);
      return redirect()->back()->withErrors(['error' => 'Failed to submit contact form. Please try again.']);
    }

    event(new ContactFormSubmitted($request->all()));
    
    return redirect()->route('contact.create')->with('success', '送信しました');
  }

  private function handleImageUploads(Request $request)
  {
      $filenames = [];

      if ($request->hasFile('image')) {
          foreach ($request->file('image') as $i => $file) {
              $imageInfo = app()->make("SbStorage")->uploadImageToContacts($file, $i);
              $imageInfo = array_change_key_case($imageInfo, CASE_LOWER);
              $filenames[] = $imageInfo;
          }
      }

      return $filenames;
  }

}
