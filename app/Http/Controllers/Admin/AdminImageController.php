<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Image;
use Illuminate\Support\Facades\DB;

class AdminImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        dd($id, $request->name);
        $files = $request->hasFile('image');
        if(isset($files)){
            dd($files);
        }
        $imageInfo = app()->make('SbStorage')->uploadImage();
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id): RedirectResponse
    {
        //
        DB::transaction(function () use ($request, $id) {
            $imageInfo = app()->make("SbStorage")->deleteImage($request->query("path"));
            $result = Image::find($request->query("image_id"));
            $result->delete();
        });
        return redirect()->route('admin.product.edit', $id);
    }
}
