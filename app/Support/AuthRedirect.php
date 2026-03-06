<?php

namespace App\Support;

use App\Models\User;

class AuthRedirect
{
    public static function routeName(User $user): ?string
    {
        if ($user->can('dashboard.view')) {
            return 'admin.dashboard';
        }

        return null;
    }
}
