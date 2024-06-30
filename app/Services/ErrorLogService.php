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
        Log::error('Error report: ', [
            "message" => $e->getMessage(),
            "user"    => auth()->user() ? auth()->user()->id : 'Guest',
            "session" => session()->getId(),
            'method'  => $request->method(),
            'headers' => $request->headers->all(),
            'params'  => $request->all(),
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
        ]);
    }

    public static function Redirect(){
        return redirect()->back()->withErrors(['error' => 'Failed to update warehouse. Please try again.']);
    }
}
