<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Contact;
use App\Events\ContactFormSubmitted;
use App\Services\Admin\AdminImageService;

class ContactService
{
    protected $adminImageService;

    public function __construct(AdminImageService $adminImageService)
    {
        $this->adminImageService = $adminImageService;
    }

    public function createContact(array $data, Request $request)
    {
        $filenames = $this->adminImageService->handleContactImageUploads($request);

        $chua_mobile = $request->header('Sec-Ch-Ua-Mobile');
        $device = ($chua_mobile === '?0') ? "desktop" : (($chua_mobile === null) ? "unknown" : "mobile");

        try {
            DB::transaction(function () use ($data, $request, $filenames, $device) {
                Contact::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'message' => $data['message'],
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
            });
        } catch (\Exception $e) {
            $this->adminImageService->deleteUploadImages($filenames, 'key');
            throw $e;
        }

        event(new ContactFormSubmitted($data));
    }
}
