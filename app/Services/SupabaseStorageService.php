<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Log;

class SupabaseStorageService
{

    protected $baseUrl;
    protected $apiKey;
    protected $contactsBucket;
    protected $productsBucket;
    protected $storageEndpoint;
    protected $product_path;
  
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->baseUrl = config('services.supabase.url');
        $this->apiKey = config('services.supabase.key');
        $this->contactsBucket = config('services.supabase.contacts_bucket');
        $this->productsBucket = config('services.supabase.products_bucket');
        $this->storageEndpoint = config('services.supabase.storage_endpoint');
        $this->product_path = config('services.supabase.product_path');
    }

    public function uploadImageToContacts($file, $i)
    {
      $originalName = $file->getClientOriginalName($file);
      $originalNameParts = explode(".", $originalName);
      $imageName = reset($originalNameParts);
      $imageType = getimagesize($file)["mime"];
      $parts = explode("/", $imageType);
      $extension = end($parts);
      $folder = date('Y-m-d');
      $date = date('Y-m-d-H-i-s');
      $path = "/{$folder}/{$imageName}_{$i}_{$date}.{$extension}";
      
      return $this->uploadImage($file, $path, $this->contactsBucket);
    }
  
    public function uploadImageToProducts($file, $product_id, $fileNumber)
    {
      $imageType = getimagesize($file)["mime"];
      $parts = explode("/", $imageType);
      $extension = end($parts);
      $path = $this->product_path . "/{$product_id}/{$product_id}_{$fileNumber}.{$extension}";

      return $this->uploadImage($file, $path, $this->productsBucket);
    }
  
    /**
     * HttpファサードでのPOSTリクエスト
     */
    private function uploadImage($file, $path=null, $backetName)
    {
      Log::info("uploading file to supabase: {$file}");
      
      if($path){
        $filepath = $path;
      } else {
        $filepath = $file->getClientOriginalName();
      }

      $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->apiKey,
      ])
        ->attach('file', $file->get(), $file->getClientOriginalName())
        ->post(
          $this->baseUrl . $this->storageEndpoint .  "{$backetName}/{$filepath}"
        );

      if ($response->successful()) {
        Log::info("upload success: {$filepath}");
        return $response->json();
      } else {
        Log::error('Failed to upload image to Supabase');
        throw new \Exception('Failed to upload image to Supabase' . $response->body());
      }
    }

    /**
     * Deleteリクエスト
     */
    public function deleteImage(array $fileKeys){
      Log::info("deleting file to supabase: ", $fileKeys);
      
      foreach( $fileKeys as $key){

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])
          ->delete($this->baseUrl . $this->storageEndpoint . $key);

        if($response->successful()){
          Log::info("Deleted file to Supabase: " . $key);
        } else {
          Log::error('Failed to delete image to Supabase');
          throw new \Exception('Failed to delete image to Supabase: ' . $response->body());
        }
      }
      return true;
    }
}
