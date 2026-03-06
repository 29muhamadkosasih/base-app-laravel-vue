<?php

namespace App\Http\Middleware;

use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $appSetting = Schema::hasTable('app_settings')
            ? AppSetting::query()->first()
            : null;

        return array_merge(parent::share($request), [

            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error'   => fn () => $request->session()->get('error'),
                'status'  => fn () => $request->session()->get('status'),
            ],

            'auth' => [
                'user' => fn () => $request->user()
                    ? [
                        'primaryRole' => $request->user()->primary_role,
                        'id' => $request->user()->id,
                        'name' => $request->user()->name,
                        'email' => $request->user()->email,
                        'role' => $request->user()->primary_role,
                        'primary_role' => $request->user()->primary_role,
                        'roles' => $request->user()->getRoleNames()->values(),
                        'permissions' => $request->user()->getAllPermissions()->pluck('name')->values(),
                    ]
                    : null,
            ],

            'appSettings' => [
                'name_app' => $appSetting?->name_app ?? config('app.name', 'Laravel'),
                'slug' => $appSetting?->slug,
                'image_url' => $appSetting?->image_url,
            ],

        ]);
    }
}
