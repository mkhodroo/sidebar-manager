<?php

namespace SidebarManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use SidebarManager\Models\SidebarImage;
use SidebarManager\Services\SidebarImageService;

class SidebarImageController extends Controller
{
    public function __construct(protected SidebarImageService $service)
    {
    }

    /**
     * صفحه بارگذاری و مدیریت تصویر سایدبار
     */
    public function index()
    {
        $image = SidebarImage::active();

        return view('sidebar-manager::image', [
            'image' => $image,
            'title' => config('sidebar-manager.title', 'مدیریت سایدبار'),
        ]);
    }

    /**
     * ذخیره / جایگزینی تصویر
     */
    public function store(Request $request)
    {
        $config = config('sidebar-manager');

        $data = $request->validate([
            'image' => ['required', 'image', 'mimes:'.implode(',', $config['mimes']), 'max:'.$config['max_size']],
            'title' => ['nullable', 'string', 'max:255'],
        ], [], [
            'image' => 'تصویر',
            'title' => 'عنوان',
        ]);

        $this->service->store($data['image'], $data['title'] ?? null);

        return back()->with('status', 'تصویر سایدبار با موفقیت ذخیره شد.');
    }

    /**
     * حذف تصویر
     */
    public function destroy()
    {
        $this->service->deleteByKey(SidebarImage::activeKey());

        return back()->with('status', 'تصویر سایدبار حذف شد.');
    }
}
