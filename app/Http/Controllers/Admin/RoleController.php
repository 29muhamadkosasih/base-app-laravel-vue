<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $search = (string) request('q', '');

        $roles = Role::query()
            ->withCount('permissions')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(10)
            ->through(function (Role $role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'guard_name' => $role->guard_name,
                    'permissions_count' => $role->permissions_count,
                ];
            });

        $roles->appends(['q' => $search]);

        return inertia('Admin/Roles/Index', [
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        return inertia('Admin/Roles/Create', [
            'permissions' => Permission::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request)
    {
        $name = $this->normalizeName($request->input('name'));
        $request->merge(['name' => $name]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ], [
            'name.required' => 'Nama role wajib diisi.',
            'name.unique' => 'Role sudah tersedia.',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil ditambahkan.');
    }

    public function edit(Role $role)
    {
        return inertia('Admin/Roles/Edit', [
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $role->permissions()->pluck('name')->values(),
            ],
            'permissions' => Permission::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, Role $role)
    {
        if ($role->name === 'admin' && $request->input('name') !== $role->name) {
            return redirect()->route('admin.roles.index')->with('error', 'Role sistem admin tidak dapat diubah namanya.');
        }

        $name = $this->normalizeName($request->input('name'));
        $request->merge(['name' => $name]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,' . $role->id],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ], [
            'name.required' => 'Nama role wajib diisi.',
            'name.unique' => 'Role sudah tersedia.',
        ]);

        $role->update([
            'name' => $validated['name'],
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'admin') {
            return redirect()->route('admin.roles.index')->with('error', 'Role sistem admin tidak dapat dihapus.');
        }

        if (User::role($role->name)->exists()) {
            return redirect()->route('admin.roles.index')->with('error', 'Role masih dipakai oleh user dan tidak bisa dihapus.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil dihapus.');
    }

    private function normalizeName(?string $name): string
    {
        return (string) Str::of($name)
            ->trim()
            ->lower()
            ->replace(' ', '-')
            ->replaceMatches('/-+/', '-');
    }
}
