<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Contact;
use App\Events\ContactFormSubmitted;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    //
    public function create()
    {
        $contacts = Contact::all();
        return Inertia::render('Contact/ContactForm', [
            "data" => $contacts,
        ]);
    }
  
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:255',
        ]);
      
        $file = $request->file('image');
        $filenames = [];

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
              $imageInfo = app()->make("SbStorage")->uploadImage($file);
              $imageInfo = array_change_key_case($imageInfo, CASE_LOWER);
              $filenames[] = $imageInfo;
            }
        }
      
        $chua_mobile = $request->header('Sec-Ch-Ua-Mobile');
        $device = ($chua_mobile === '?0') ? "desktop" : (($chua_mobile === null) ? "unknown" : "mobile");

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

        event(new ContactFormSubmitted($request->all()));
      
        return redirect()->route('contact.create')->with('success', '送信しました');
    }

    public function delete(Request $request): RedirectResponse
    {
      $request->validate([
        'path' => 'required|string|max:255',
      ]);

      $path = app()->make('SbStorage')->deleteImage($request->path);

      return redirect()->route('contact.create')->with('success', '削除しました');
    }
}
