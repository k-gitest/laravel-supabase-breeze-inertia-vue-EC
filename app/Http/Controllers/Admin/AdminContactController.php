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
use App\Services\Admin\AdminContactService;
use Log;

class AdminContactController extends Controller
{

    protected $adminContactService;

    public function __construct(AdminContactService $adminContactService)
    {
        $this->adminContactService = $adminContactService;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $contacts = $this->adminContactService->getAllContacts();

        return Inertia::render('Contact/Admin/AdminContactIndex', [
            "pagedata" => $contacts,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response|bool
    {
        try {
            $contact = $this->adminContactService->getContactById($id);
        } 
        catch (ModelNotFoundException $e) {
            report($e);
            return false;
        }

        return Inertia::render('Contact/Admin/AdminContactShow', [
            "data" => $contact,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): RedirectResponse|bool
    {
        $request->validate([
            'id' => 'required|string|max:255|exists:contacts,id',
        ]);

        try {
            $this->adminContactService->deleteContact($request);
            Log::info('contact delete succeeded');
        } catch (\Exception $e) {
            report($e);
            return false;
        }

        return redirect()->back()->with('success', '削除しました');
    }
}
