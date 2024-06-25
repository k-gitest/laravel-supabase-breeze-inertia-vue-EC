<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Events\ContactFormSubmitted;
use Log;

class AdminContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $contacts = Contact::orderBy('created_at', 'desc')->paginate(10);

        return Inertia::render('Contact/Admin/AdminContactIndex', [
          "pagedata" => $contacts,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response
    {
        $contact = Contact::findOrFail($id);
        
        return Inertia::render('Contact/Admin/AdminContactShow', [
          "data" => $contact,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): RedirectResponse
    {
        dd($request);
        $request->validate([
            'id' => 'required|string|max:255|exists:contacts,id',
        ]);

        $contact = Contact::findOrFail($request->id);

        if($contact->attachments){
            $fileKeys = array_column($contact->attachments, 'key');
            
            try{
                app()->make('SbStorage')->deleteImage($fileKeys);
            }
            catch (\Exception $e) {
                Log::error('contact Image delete failed.', [
                           'error' => $e->getMessage(),
                           'contact_id' => $contact->id
                           ]);
                return redirect()->back()->withErrors(['error' => 'Failed to delete image. Please try again.']);
            }
        }
        
        try {
            DB::transaction(function () use ($contact)
            {
                $contact->delete();
            });
            Log::info('contact delete succeeded');
        }
        catch (\Exception $e) {
            Log::error('Failed to delete contact.', [
                       'error' => $e->getMessage(),
                       'contact_id' => $contact->id
                       ]);
            return redirect()->back()->withErrors(['error' => 'Failed to delete contact. Please try again.']);
        }

        return redirect()->back()->with('success', '削除しました');
    }
}
