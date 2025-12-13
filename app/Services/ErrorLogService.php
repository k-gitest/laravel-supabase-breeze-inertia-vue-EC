<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class ErrorLogService
{
    public static function logError(Exception $e)
    {
        $request = app(Request::class);
        $params = $request->all();

        // **機密情報マスキング処理**
        $keysToMask = ['password', 'secret', 'token', 'key', 'pass', 'customer_id', 'payment_method', 'source_id', 'id'];

        $filtered_params = collect($params)->map(function ($value, $key) use ($keysToMask) {
            if (is_string($key) && collect($keysToMask)->some(fn ($maskKey) => stripos($key, $maskKey) !== false)) {
                return '******** (Masked)';
            }
            return $value;
        })->toArray();

        Log::error('Error report: ', [
            "message" => $e->getMessage(),
            "user"    => auth()->user() ? auth()->user()->id : 'Guest',
            "session" => session()->getId(),
            'method'  => $request->method(),
            'headers' => $request->headers->all(),
            'referrer'=> $request->headers->get('referer'),
            "url"     => $request->url(),
            "ip"      => $request->ip(),
            "code"    => $e->getCode(),
            "line"    => $e->getLine(),
            "file"    => $e->getFile(),
            "trace"   => $e->getTraceAsString(),
            'laravel_version' => app()->version(),
            'php_version' => phpversion(),
            'environment' => app()->environment(),
            'params'  => $filtered_params, // フィルタリング済み
        ]);
    }

    public static function Redirect(){
        return redirect()->back()->withErrors(['error' => 'Failed to action. Please try again.']);
    }
}
