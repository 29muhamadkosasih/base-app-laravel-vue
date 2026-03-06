<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $search = (string) request('q', '');

        $permissions = Permission::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(10)
            ->through(function (Permission $permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'guard_name' => $permission->guard_name,
                    'group' => Str::before($permission->name, '.'),
                ];
            });

        $permissions->appends(['q' => $search]);

        return inertia('Admin/Permissions/Index', [
            'permissions' => $permissions,
        ]);
    }

    public function create()
    {
        return inertia('Admin/Permissions/Create');
    }

    public function store(Request $request)
    {
        $name = $this->normalizeName($request->input('name'));

        $request->merge(['name' => $name]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
        ], [
            'name.required' => 'Nama permission wajib diisi.',
            'name.unique' => 'Permission sudah tersedia.',
        ]);

        Permission::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission berhasil ditambahkan.');
    }

    public function edit(Permission $permission)
    {
        return inertia('Admin/Permissions/Edit', [
            'permission' => [
                'id' => $permission->id,
                'name' => $permission->name,
            ],
        ]);
    }

    public function update(Request $request, Permission $permission)
    {
        $name = $this->normalizeName($request->input('name'));

        $request->merge(['name' => $name]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name,' . $permission->id],
        ], [
            'name.required' => 'Nama permission wajib diisi.',
            'name.unique' => 'Permission sudah tersedia.',
        ]);

        $permission->update([
            'name' => $validated['name'],
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission berhasil diperbarui.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('admin.permissions.index')->with('success', 'Permission berhasil dihapus.');
    }

    private function normalizeName(?string $name): string
    {
        return (string) Str::of($name)
            ->trim()
            ->lower()
            ->replace(' ', '.')
            ->replaceMatches('/\.+/', '.');
    }
}
