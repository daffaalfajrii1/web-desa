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
use App\Http\Controllers\Admin\PpidSectionController;
use App\Http\Controllers\Admin\PpidDocumentController;
use App\Http\Controllers\Admin\PpidRequestController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\ShopCategoryController;

Route::get('/', function () {
    return view('frontend.home');
})->name('home');

Route::middleware(['auth'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::post('/editor/upload', [EditorUploadController::class, 'upload'])->name('editor.upload');

        Route::middleware('permission:manage settings')->group(function () {
            Route::prefix('settings')->as('settings.')->group(function () {
                Route::get('/desa', [VillageSettingController::class, 'edit'])->name('desa.edit');
                Route::put('/desa', [VillageSettingController::class, 'update'])->name('desa.update');
            });
        });

        Route::middleware('permission:manage profil-desa')->prefix('profil-desa')->as('profil-desa.')->group(function () {
            Route::resource('halaman', PageController::class);
            Route::resource('menu', ProfileMenuController::class);
        });

        Route::middleware('permission:manage berita')->group(function () {
            Route::resource('kategori-berita', CategoryController::class)->except(['show']);
            Route::resource('berita', PostController::class);
        });

        Route::middleware('permission:manage produk-hukum')->group(function () {
            Route::resource('produk-hukum', LegalProductController::class);

            Route::get('kategori-produk-hukum', [CategoryController::class, 'legalProductIndex'])->name('kategori-produk-hukum.index');
            Route::get('kategori-produk-hukum/create', [CategoryController::class, 'legalProductCreate'])->name('kategori-produk-hukum.create');
            Route::post('kategori-produk-hukum', [CategoryController::class, 'legalProductStore'])->name('kategori-produk-hukum.store');
            Route::get('kategori-produk-hukum/{category}/edit', [CategoryController::class, 'legalProductEdit'])->name('kategori-produk-hukum.edit');
            Route::put('kategori-produk-hukum/{category}', [CategoryController::class, 'legalProductUpdate'])->name('kategori-produk-hukum.update');
            Route::delete('kategori-produk-hukum/{category}', [CategoryController::class, 'legalProductDestroy'])->name('kategori-produk-hukum.destroy');
        });

        Route::middleware('permission:manage informasi-publik')->group(function () {
            Route::resource('informasi-publik', PublicInformationController::class);
        });

        Route::middleware('permission:manage pengumuman')->group(function () {
            Route::resource('pengumuman', AnnouncementController::class);
        });

        Route::middleware('permission:manage agenda')->group(function () {
            Route::resource('agenda', AgendaController::class);
        });

        Route::middleware('permission:manage ppid')->group(function () {
            Route::resource('ppid-section', PpidSectionController::class);
            Route::resource('ppid-document', PpidDocumentController::class);
            Route::resource('ppid-request', PpidRequestController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
        });

        Route::middleware('permission:manage users')->group(function () {
            Route::resource('users', UserController::class)->except(['show']);
            Route::put('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        });

        Route::middleware('permission:manage roles')->group(function () {
            Route::resource('roles', RoleController::class)->except(['show']);
        });

        Route::middleware('permission:manage sotk')->group(function () {
    Route::resource('pegawai', EmployeeController::class);
});

Route::middleware('permission:manage lapak')->group(function () {
    Route::resource('lapak', ShopController::class);
    Route::resource('kategori-lapak', ShopCategoryController::class)->except(['show']);
});
    });

require __DIR__.'/auth.php';