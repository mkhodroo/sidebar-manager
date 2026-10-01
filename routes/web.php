<?php

use Illuminate\Support\Facades\Route;
use SidebarManager\Http\Controllers\SidebarImageController;

Route::group([
    'prefix' => config('sidebar-manager.route_prefix', 'sidebar-manager'),
    'middleware' => config('sidebar-manager.middleware', ['web', 'auth']),
    'as' => 'sidebar-manager.',
], function () {
    Route::get('/image', [SidebarImageController::class, 'index'])->name('image.index');
    Route::post('/image', [SidebarImageController::class, 'store'])->name('image.store');
    Route::delete('/image', [SidebarImageController::class, 'destroy'])->name('image.destroy');
});
