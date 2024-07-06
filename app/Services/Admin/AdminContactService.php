<?php

namespace App\Services\Admin;

use App\Models\Contact;
use Illuminate\Support\Facades\DB;

class AdminContactService
{
    public function getAllContacts()
    {
        return Contact::orderBy('created_at', 'desc')->paginate(10);
    }

    public function getContactById(string $id)
    {
        return Contact::findOrFail($id);
    }

    public function deleteContact($request)
    {
        DB::transaction(function () use ($request)
        {
            $contact = Contact::lockForUpdate()->findOrFail($request->id);

            if ($contact->attachments) {
                $attachments = $contact->attachments;
            }

            $contact->delete();

            if (isset($attachments)) {
                $fileKeys = array_column($attachments, 'key');
                app()->make('SbStorage')->deleteImage($fileKeys);
            }
        });
    }
}
