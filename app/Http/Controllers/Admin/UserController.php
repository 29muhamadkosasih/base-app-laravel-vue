<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = (string) request('q', '');

        $users = User::with('roles')
            ->when($search !== '', function ($users) use ($search) {
                $users->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhereHas('roles', function ($roleQuery) use ($search) {
                            $roleQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->through(function (User $user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->roles->pluck('name')->values(),
                ];
            });

        $users->appends(['q' => $search]);

        return inertia('Admin/Users/Index', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        return inertia('Admin/Users/Create', [
            'roles' => Role::query()->orderBy('name')->pluck('name')->values(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'roles' => ['required', 'array', 'min:1'],
                'roles.*' => ['string', 'exists:roles,name'],
            ],
            [
                'name.required' => 'Nama wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'roles.required' => 'Role wajib dipilih minimal satu.',
                'roles.min' => 'Role wajib dipilih minimal satu.',
                'roles.*.exists' => 'Role yang dipilih tidak ditemukan.',
            ],
        );

        try {
            DB::transaction(function () use ($validated) {
                $user = User::create([
                    'name' => trim($validated['name']),
                    'email' => strtolower(trim($validated['email'])),
                    'password' => Hash::make($validated['password']),
                ]);

                $user->syncRoles($validated['roles']);
                $user->syncPermissions([]);
            });

            return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan user.');
        }
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);

        return inertia('Admin/Users/Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name')->values(),
            ],
            'roles' => Role::query()->orderBy('name')->pluck('name')->values(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', "unique:users,email,{$user->id}"],
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
                'roles' => ['required', 'array', 'min:1'],
                'roles.*' => ['string', 'exists:roles,name'],
            ],
            [
                'name.required' => 'Nama wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah digunakan.',
                'password.min' => 'Password minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'roles.required' => 'Role wajib dipilih minimal satu.',
                'roles.min' => 'Role wajib dipilih minimal satu.',
                'roles.*.exists' => 'Role yang dipilih tidak ditemukan.',
            ],
        );

        try {
            $data = [
                'name' => trim($validated['name']),
                'email' => strtolower(trim($validated['email'])),
            ];

            if (!empty($validated['password'])) {
                $data['password'] = Hash::make($validated['password']);
            }

            DB::transaction(function () use ($user, $data, $validated) {
                $user->update($data);
                $user->syncRoles($validated['roles']);
                $user->syncPermissions([]);
            });

            return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui user.');
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
        }

        $user->syncRoles([]);
        $user->syncPermissions([]);
        $user->delete();

        return redirect()->route('admin.users.index');
    }
}
