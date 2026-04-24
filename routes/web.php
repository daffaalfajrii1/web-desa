<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\VillageSettingController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProfileMenuController;
use App\Http\Controllers\Admin\EditorUploadController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\LegalProductController;
use App\Http\Controllers\Admin\PublicInformationController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\AgendaController;
Route::get('/', function () {
    return view('frontend.home');
})->name('home');

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::prefix('settings')->as('settings.')->group(function () {
            Route::get('/desa', [VillageSettingController::class, 'edit'])->name('desa.edit');
            Route::put('/desa', [VillageSettingController::class, 'update'])->name('desa.update');
        });

        Route::prefix('profil-desa')->as('profil-desa.')->group(function () {
            Route::resource('halaman', PageController::class);
            Route::resource('menu', ProfileMenuController::class);
        });

        Route::post('/editor/upload', [EditorUploadController::class, 'upload'])->name('editor.upload');

        Route::resource('kategori-berita', CategoryController::class)->except(['show']);
        Route::resource('berita', PostController::class);
        Route::resource('produk-hukum', LegalProductController::class);
        Route::get('kategori-produk-hukum', [CategoryController::class, 'legalProductIndex'])->name('kategori-produk-hukum.index');
    Route::get('kategori-produk-hukum/create', [CategoryController::class, 'legalProductCreate'])->name('kategori-produk-hukum.create');
    Route::post('kategori-produk-hukum', [CategoryController::class, 'legalProductStore'])->name('kategori-produk-hukum.store');
    Route::get('kategori-produk-hukum/{category}/edit', [CategoryController::class, 'legalProductEdit'])->name('kategori-produk-hukum.edit');
    Route::put('kategori-produk-hukum/{category}', [CategoryController::class, 'legalProductUpdate'])->name('kategori-produk-hukum.update');
    Route::delete('kategori-produk-hukum/{category}', [CategoryController::class, 'legalProductDestroy'])->name('kategori-produk-hukum.destroy');
    Route::resource('informasi-publik', PublicInformationController::class);
    Route::resource('pengumuman', AnnouncementController::class);
    Route::resource('agenda', AgendaController::class);
    });
    

require __DIR__.'/auth.php';