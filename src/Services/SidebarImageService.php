<?php

namespace SidebarManager\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SidebarManager\Models\SidebarImage;

class SidebarImageService
{
    /**
     * ذخیره (یا جایگزینی) تصویر سایدبار
     *
     * @throws \RuntimeException
     */
    public function store(UploadedFile $file, ?string $title = null, ?string $key = null): SidebarImage
    {
        $key = $key ?: SidebarImage::activeKey();
        $disk = config('sidebar-manager.disk', 'public');
        $path = trim(config('sidebar-manager.path', 'sidebar'), '/');

        // اگر قبلاً تصویری برای این کلید وجود دارد، فایل و رکورد قبلی پاک شود.
        $this->deleteByKey($key);

        $name = Str::random(20).'.'.$file->getClientOriginalExtension();

        $file->storeAs($path, $name, ['disk' => $disk]);

        return SidebarImage::create([
            'key' => $key,
            'title' => $title,
            'path' => $path.'/'.$name,
            'disk' => $disk,
            'size' => $file->getSize(),
        ]);
    }

    /**
     * حذف تصویر بر اساس کلید
     */
    public function deleteByKey(string $key): void
    {
        SidebarImage::query()->where('key', $key)->get()->each(function (SidebarImage $image) {
            $image->deleteFile();
            $image->delete();
        });
    }

    /**
     * حذف یک رکورد مشخص
     */
    public function delete(SidebarImage $image): void
    {
        $image->deleteFile();
        $image->delete();
    }

    /**
     * حذف فایل‌های یتیم از دیسک که دیگر در دیتابیس نیستند
     */
    public function pruneOrphans(): int
    {
        $disk = config('sidebar-manager.disk', 'public');
        $path = trim(config('sidebar-manager.path', 'sidebar'), '/');
        $storage = Storage::disk($disk);

        if (! $storage->exists($path)) {
            return 0;
        }

        $known = SidebarImage::query()->where('disk', $disk)->pluck('path')->all();

        $deleted = 0;
        foreach ($storage->files($path) as $file) {
            if (! in_array($file, $known, true)) {
                $storage->delete($file);
                $deleted++;
            }
        }

        return $deleted;
    }
}
