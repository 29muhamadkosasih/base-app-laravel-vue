<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function edit()
    {
        $setting = AppSetting::singleton();

        return inertia('Admin/Settings/Index', [
            'setting' => [
                'name_app' => $setting->name_app,
                'slug' => $setting->slug,
                'image' => $setting->image,
                'image_url' => $setting->image_url,
            ],
        ]);
    }

    public function update(Request $request)
    {
        $setting = AppSetting::singleton();

        $validated = $request->validate([
            'name_app' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
        ], [
            'name_app.required' => 'Nama aplikasi wajib diisi.',
            'image.image' => 'File logo harus berupa gambar.',
            'image.mimes' => 'Logo harus berformat jpg, jpeg, png, webp, atau svg.',
            'image.max' => 'Ukuran logo maksimal 2 MB.',
        ]);

        $data = [
            'name_app' => trim($validated['name_app']),
            'slug' => Str::slug($validated['slug'] ?: $validated['name_app']),
        ];

        if ($request->hasFile('image')) {
            if ($setting->image) {
                $existingPublicPath = public_path($setting->image);

                if (File::exists($existingPublicPath)) {
                    File::delete($existingPublicPath);
                } elseif (Storage::disk('public')->exists($setting->image)) {
                    Storage::disk('public')->delete($setting->image);
                }
            }

            $directory = public_path('uploads/settings');

            if (!File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $file = $request->file('image');
            $filename = time() . '-' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $filename .= '.' . $file->getClientOriginalExtension();

            $file->move($directory, $filename);

            $data['image'] = 'uploads/settings/' . $filename;
        }

        $setting->update($data);

        return redirect()->route('admin.settings.edit')->with('success', 'Setting aplikasi berhasil diperbarui.');
    }
}
