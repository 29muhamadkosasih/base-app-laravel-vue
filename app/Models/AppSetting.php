<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AppSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_app',
        'slug',
        'image',
    ];

    protected $appends = [
        'image_url',
    ];

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        if (file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        return asset('storage/' . ltrim($this->image, '/'));
    }

    public static function singleton(): self
    {
        /** @var self $setting */
        $setting = static::query()->firstOrCreate(
            ['id' => 1],
            [
                'name_app' => config('app.name', 'Laravel'),
                'slug' => 'laravel',
            ],
        );

        return $setting;
    }
}
