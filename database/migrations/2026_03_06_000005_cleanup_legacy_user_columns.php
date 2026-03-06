<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'classroom_id')) {
                    try {
                        $table->dropForeign(['classroom_id']);
                    } catch (\Throwable $e) {
                    }

                    $table->dropColumn('classroom_id');
                }

                foreach (['role', 'nisn', 'gender'] as $column) {
                    if (Schema::hasColumn('users', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('roles')) {
            $adminRole = Role::findOrCreate('admin', 'web');
            $settingsPermission = Permission::findOrCreate('settings.index', 'web');
            $adminRole->givePermissionTo($settingsPermission);

            Permission::query()
                ->whereIn('name', [
                    'lessons.view',
                    'lessons.create',
                    'lessons.edit',
                    'lessons.delete',
                    'classrooms.view',
                    'classrooms.create',
                    'classrooms.edit',
                    'classrooms.delete',
                ])
                ->delete();

            $studentRole = Role::query()->where('name', 'student')->where('guard_name', 'web')->first();

            if ($studentRole) {
                $tableNames = config('permission.table_names');

                DB::table($tableNames['model_has_roles'])
                    ->where('role_id', $studentRole->id)
                    ->delete();

                DB::table($tableNames['role_has_permissions'])
                    ->where('role_id', $studentRole->id)
                    ->delete();

                $studentRole->delete();
            }
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->nullable()->after('email');
            }

            if (!Schema::hasColumn('users', 'nisn')) {
                $table->string('nisn')->nullable()->unique()->after('role');
            }

            if (!Schema::hasColumn('users', 'classroom_id')) {
                $table->unsignedBigInteger('classroom_id')->nullable()->after('nisn');
            }

            if (!Schema::hasColumn('users', 'gender')) {
                $table->string('gender', 1)->nullable()->after('classroom_id');
            }
        });

        if (Schema::hasTable('roles')) {
            $adminRole = Role::findOrCreate('admin', 'web');
            $adminRole->givePermissionTo(Permission::findOrCreate('settings.index', 'web'));
            Role::findOrCreate('student', 'web');
        }
    }
};
