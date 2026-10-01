# Sidebar Manager

پکیج مدیریت سایدبار پنل کاربری برای لاراول — مرحلهٔ ۱: بارگذاری و مدیریت تصویر سایدبار.

## نصب

```bash
composer require arghavan/sidebar-manager
php artisan migrate
php artisan storage:link
```

انتشار کانفیگ (اختیاری):

```bash
php artisan vendor:publish --tag=sidebar-manager-config
```

## آدرس صفحه

```
GET    /sidebar-manager/image    → فرم بارگذاری تصویر
POST   /sidebar-manager/image    → ذخیره / جایگزینی تصویر
DELETE /sidebar-manager/image    → حذف تصویر
```

نام روت‌ها: `sidebar-manager.image.index` / `.store` / `.destroy`

## استفاده در سایدبار (مرحلهٔ بعد)

تصویر با کلید `default` ذخیره می‌شود (از `sidebar-manager.active_key` قابل تغییر است).
برای نمایش آن در سایدبار، کافی است این کامپوننت را صدا بزنید:

```blade
<x-sidebar-manager::image />
```

یا فقط مدل را بخوانید:

```blade
@php($img = \SidebarManager\Models\SidebarImage::active())
@if ($img)
    <img src="{{ $img->url }}" alt="{{ $img->title }}">
@endif
```

## کانفیگ

| کلید | توضیح | پیش‌فرض |
| --- | --- | --- |
| `route_prefix` | پیشوند مسیرها | `sidebar-manager` |
| `middleware` | میدل‌ورهای مسیرها | `['web', 'auth']` |
| `disk` | دیسک ذخیره فایل | `public` |
| `path` | مسیر ذخیره در دیسک | `sidebar` |
| `mimes` | فرمت‌های مجاز | `jpg,jpeg,png,gif,webp,svg` |
| `max_size` | حداکثر حجم (KB) | `2048` |
| `title` | عنوان پنل | `مدیریت سایدبار` |
| `active_key` | کلید تصویر فعال | `default` |

## سرویس

```php
use SidebarManager\Services\SidebarImageService;

$service = app(SidebarImageService::class);
$service->store($request->file('image'), 'لوگوی سایدبار'); // ذخیره/جایگزینی
$service->deleteByKey('default');                            // حذف
$service->pruneOrphans();                                    // حذف فایل‌های یتیم دیسک
```

## نکات

- تصویر قبلی با آپلود جدید، به‌صورت خودکار حذف می‌شود (هم رکورد دیتابیس و هم فایل).
- برای نمایش تصاویر روی پروژه‌ای که دیسک `public` ندارد، `disk` را در کانفیگ تغییر دهید.
