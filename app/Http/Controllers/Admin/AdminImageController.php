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
        $images = [$request->path];

        if($images){
            try{
                $imageInfo = app()->make("SbStorage")->deleteImage($images);
            }
            catch(\Exception $e){
                Log::error('Failed to delete image.', ['error' => $e->getMessage()]);
                return redirect()->back()->withErrors(['error' => 'Failed to delete image. Please try again.']);
            }
        }
        
        try{
            DB::transaction(function () use ($request, $id) {
                $result = Image::find($request->image_id);
                $result->delete();
            });
        }
        catch(\Exception $e){
            Log::error('Failed to delete image.', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Failed to delete image. Please try again.']);
        }

        return redirect()->route('admin.product.edit', $id);
    }
}
