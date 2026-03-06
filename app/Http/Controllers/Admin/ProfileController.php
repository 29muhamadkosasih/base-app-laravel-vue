<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = $request->user()->loadMissing('roles');

        return inertia('Admin/Profile/Show', [
            'profile' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'primary_role' => $user->primary_role,
                'roles' => $user->getRoleNames()->values(),
                'permissions' => $user->getAllPermissions()->pluck('name')->values(),
                'created_at' => optional($user->created_at)?->translatedFormat('d F Y, H:i'),
                'updated_at' => optional($user->updated_at)?->translatedFormat('d F Y, H:i'),
                'email_verified_at' => optional($user->email_verified_at)?->translatedFormat('d F Y, H:i'),
            ],
        ]);
    }
}
