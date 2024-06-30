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
                report($e);
                return false;
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
            report($e);
            return false;
        }

        return redirect()->back()->with('success', '削除しました');
    }
}
