<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Image;
use App\Http\Controllers\Controller;
use Log;

class AdminImageController extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'image_id' => 'required|exists:images,id',
            'path' => 'required|string',
        ]);
        
        $imagePaths = [$request->path];

        try{
            $imageInfo = app()->make("SbStorage")->deleteImage($imagePaths);
        }
        catch(\Exception $e){
            Log::error('Failed to delete image.', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Failed to delete image. Please try again.']);
        }
        
        try{
            DB::transaction(function () use ($request) {
                $image = Image::findOrFail($request->image_id);
                $image->delete();
            });
        }
        catch(\Exception $e){
            Log::error('Failed to delete image.', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Failed to delete image. Please try again.']);
        }

        return redirect()->route('admin.product.edit', $id)->with('success', '削除しました');
    }
}
