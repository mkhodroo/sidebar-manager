<?php

namespace SidebarManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SidebarImage extends Model
{
    protected $table = 'sidebar_images';

    protected $fillable = [
        'key',
        'title',
        'path',
        'disk',
        'size',
    ];

    /**
     * کلید فعال پکیج (از کانفیگ)
     */
    public static function activeKey(): string
    {
        return config('sidebar-manager.active_key', 'default');
    }

    /**
     * تصویر فعال سایدبار
     */
    public static function active(): ?self
    {
        return static::query()
            ->where('key', static::activeKey())
            ->first();
    }

    /**
     * آدرس کامل تصویر (برای نمایش در سایدبار)
     */
    public function getUrlAttribute(): ?string
    {
        if (! $this->path) {
            return null;
        }

        return Storage::disk($this->disk ?: 'public')->url($this->path);
    }

    /**
     * حجم فایل به کیلوبایت
     */
    public function getSizeInKbAttribute(): float
    {
        return round(($this->size ?: 0) / 1024, 2);
    }

    /**
     * حذف فایل فیزیکی از دیسک
     */
    public function deleteFile(): void
    {
        if ($this->path && Storage::disk($this->disk ?: 'public')->exists($this->path)) {
            Storage::disk($this->disk ?: 'public')->delete($this->path);
        }
    }
}
