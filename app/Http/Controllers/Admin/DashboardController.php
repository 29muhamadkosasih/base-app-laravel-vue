<?php

namespace App\Http\Controllers\Admin;

use App\Models\AppSetting;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $summary = [
            'users' => User::count(),
            'roles' => Role::count(),
            'permissions' => Permission::count(),
            'settings' => AppSetting::count(),
        ];

        return inertia('Admin/Dashboard/Index', [
            'summary' => $summary,
        ]);
    }
}
