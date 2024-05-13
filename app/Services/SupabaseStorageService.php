<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class SupabaseStorageService
{

    protected $baseUrl;
    protected $apiKey;
    protected $bucketName;
  
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
        $this->baseUrl = env('SUPABASE_URL');
        $this->apiKey = env('SUPABASE_KEY');
        $this->bucketName = env('SUPABASE_BUCKET');
    }
  
    /**
     * HttpファサードでのPOSTリクエスト
     */
    public function uploadImage($file, $path=null)
    {
      //Log::info("uploading file to supabase: {$file}");
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
          "{$this->baseUrl}/storage/v1/object/{$this->bucketName}/{$filepath}"
        );

      if ($response->successful()) {
        return $response->json();
      } else {
        throw new \Exception('Failed to upload image to Supabase: ' . $response->body());
      }
    }

    /**
     * Deleteリクエスト
     */
    public function deleteImage($path){
      $response = Http::withHeaders([
          'Authorization' => 'Bearer ' . $this->apiKey,
      ])
        ->delete("{$this->baseUrl}/storage/v1/object/{$path}");

      if($response->successful()){
        return $response->json();
      } else {
        throw new \Exception('Failed to upload image to Supabase: ' . $response->body());
      }
    }
}
