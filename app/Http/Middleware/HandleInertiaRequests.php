<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * 最初のページ訪問時に読み込まれるルートテンプレート
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * 現在のアセットバージョンを決定する
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * デフォルトで共有されるプロップを定義する
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request), 
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success')
            ],
        ];
    }
}
