<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Image;
use App\Http\Controllers\Controller;
use App\Services\Admin\AdminImageService;
use Log;

class AdminImageController extends Controller
{
    protected $adminImageService;

    public function __construct(AdminImageService $adminImageService)
    {
        $this->adminImageService = $adminImageService;
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id): RedirectResponse|bool
    {
        $request->validate([
            'image_id' => 'required|exists:images,id',
            'path' => 'required|string',
        ]);

        try {
            $this->adminImageService->deleteImage($request);
        } catch (\Exception $e) {
            report($e);
            return false;
        }

        return redirect()->route('admin.product.edit', $id)->with('success', '削除しました');
    }
}
