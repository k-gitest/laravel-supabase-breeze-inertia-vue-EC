<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Image;
use App\Http\Controllers\Controller;

class AdminImageController extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id): RedirectResponse
    {
        DB::transaction(function () use ($request, $id) {
            $imageInfo = app()->make("SbStorage")->deleteImage($request->query("path"));
            $result = Image::find($request->query("image_id"));
            $result->delete();
        });
        
        return redirect()->route('admin.product.edit', $id);
    }
}
