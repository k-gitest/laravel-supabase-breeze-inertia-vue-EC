<?php

namespace App\Services\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Image;

class AdminImageService
{
    public function getLatestImageNumber($product_id)
    {
      $latestImage = Image::where('product_id', $product_id)->latest('created_at')->orderBy('id', 'desc')->first();
      if ($latestImage === null) {
        $latestnumber = 0;
      } else {
        $filename = pathinfo($latestImage->path, PATHINFO_FILENAME);
        $filenameParts = explode('_', $filename);
        $latestnumber = end($filenameParts) + 1;
      }
      return $latestnumber;
    }

    public function handleImageProductUploads($product_id, Request $request, $latestNumber=0)
    {
        $filenames = [];

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $i => $file) {
                $fileNumber = $i + $latestNumber;
                $imageInfo = app()->make("SbStorage")->uploadImageToProducts($file, $product_id, $fileNumber);
                //$imageInfo = array_change_key_case($imageInfo, CASE_LOWER);
                $filenames[] = [
                  "name" => $imageInfo["Id"],
                  "path" => $imageInfo["Key"],
                  "product_id" => $product_id,
                ];
            }
        }

        return $filenames;
    }

    public function handleContactImageUploads(Request $request)
    {
        $filenames = [];
    
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $i => $file) {
                  $imageInfo = app()->make("SbStorage")->uploadImageToContacts($file, $i);
                  $imageInfo = array_change_key_case($imageInfo, CASE_LOWER);
                  $filenames[] = $imageInfo;
            }
        }
    
        return $filenames;
    }
    
    public function deleteUploadImages(array $filenames, string $column = '')
    {
        if (empty($column)) {
            throw new \InvalidArgumentException('Column name must be provided.');
        }
        
        $imagePaths = array_column($filenames, $column);
        foreach ($imagePaths as $imagePath) {
            try {
                app()->make('SbStorage')->deleteImage([$imagePath]);
            } catch (\Exception $e) {
                throw $e;
            }
        }
    }

    public function deleteImages(array $images): void
    {
        if ($images) {
            try {
                app()->make('SbStorage')->deleteImage($images);
                Log::info('product image delete successed');
            } catch (\Exception $e) {
                throw $e;
            }
        }
    }

    public function saveImages(array $filenames)
    {
        try {
            foreach ($filenames as $filename) {
                Image::create([
                    'name' => $filename['name'],
                    'path' => $filename['path'],
                    'product_id' => $filename['product_id'],
                ]);
            }
        } 
        catch (\Exception $e) {
            $this->deleteUploadImages($filenames);
            throw $e;
        }
    }
    
}
