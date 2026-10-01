@php
    /**
     * نمایش تصویر ذخیره‌شده سایدبار.
     *
     * @param string|null $key کلید تصویر برای انتخاب تصویر فعال
     */
    $sidebarImage = \SidebarManager\Models\SidebarImage::query()
        ->where('key', $key ?: \SidebarManager\Models\SidebarImage::activeKey())
        ->first();
@endphp

@if ($sidebarImage)
    <img src="{{ $sidebarImage->url }}"
         alt="{{ $sidebarImage->title ?: config('sidebar-manager.title', 'سایدبار') }}"
         class="{{ $class }}" style="{{ $style }}">
@endif
