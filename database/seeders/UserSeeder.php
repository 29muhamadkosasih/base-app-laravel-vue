<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $permissionTables = config('permission.table_names');

        DB::table($permissionTables['model_has_roles'])
            ->where('model_type', User::class)
            ->delete();

        DB::table($permissionTables['model_has_permissions'])
            ->where('model_type', User::class)
            ->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $users = [
            [
                'name' => 'Administrator',
                'email' => 'admin@gmail.com',
                'password' => 'password',
                'role' => 'admin',
            ],
        ];

        foreach ($users as $seededUser) {
            $data = [
                'name' => $seededUser['name'],
                'password' => Hash::make($seededUser['password']),
            ];

            $user = User::updateOrCreate([
                'email' => $seededUser['email'],
            ], $data);

            $user->syncPermissions([]);
            $user->syncRoles([$seededUser['role']]);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}