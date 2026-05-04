<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\VillageSettingController;
use App\Http\Controllers\Admin\VillageBannerController;
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
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\SelfServiceController;
use App\Http\Controllers\Admin\SelfServiceFieldController;
use App\Http\Controllers\Admin\SelfServiceSubmissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\AttendanceCheckController;
use App\Http\Controllers\Admin\AttendanceHolidayController;
use App\Http\Controllers\Admin\AttendanceSettingController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\ShopCategoryController;
use App\Http\Controllers\Admin\TourismController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\HamletController;
use App\Http\Controllers\Admin\PopulationSummaryController;
use App\Http\Controllers\Admin\PopulationStatController;
use App\Http\Controllers\Admin\PopulationImportController;
use App\Http\Controllers\Admin\ApbdesController;
use App\Http\Controllers\Admin\SocialAssistanceProgramController;
use App\Http\Controllers\Admin\SocialAssistanceRecipientController;
use App\Http\Controllers\Admin\SocialAssistanceChartController;
use App\Http\Controllers\Admin\StuntingRecordController;
use App\Http\Controllers\Admin\StuntingChartController;
use App\Http\Controllers\Admin\IdmSummaryController;
use App\Http\Controllers\Admin\IdmIndicatorController;
use App\Http\Controllers\Admin\SdgsSummaryController;
use App\Http\Controllers\Admin\SdgsGoalValueController;
use App\Http\Controllers\BansosPublicController;

Route::get('/', function () {
    return view('frontend.home');
})->name('home');

Route::middleware(['auth'])->get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->name('dashboard');

Route::middleware(['auth'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::post('/editor/upload', [EditorUploadController::class, 'upload'])->name('editor.upload');

        Route::middleware('permission:manage settings')->group(function () {
            Route::prefix('settings')->as('settings.')->group(function () {
                Route::get('/desa', [VillageSettingController::class, 'edit'])->name('desa.edit');
                Route::put('/desa', [VillageSettingController::class, 'update'])->name('desa.update');
                Route::put('/desa-banners/{village_banner}/toggle', [VillageBannerController::class, 'toggle'])->name('desa-banners.toggle');
                Route::resource('/desa-banners', VillageBannerController::class)
                    ->parameters(['desa-banners' => 'village_banner'])
                    ->except(['show']);
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

        Route::middleware('role_or_permission:super_admin|manage layanan')->group(function () {
            Route::resource('pengaduan', ComplaintController::class)->only(['index', 'show', 'edit', 'update']);

            Route::resource('layanan-mandiri', SelfServiceController::class)
                ->parameters(['layanan-mandiri' => 'self_service'])
                ->except(['show']);

            Route::prefix('layanan-mandiri/{self_service}')
                ->as('layanan-mandiri.')
                ->group(function () {
                    Route::resource('fields', SelfServiceFieldController::class)
                        ->parameters(['fields' => 'self_service_field'])
                        ->except(['show']);

                    Route::resource('submissions', SelfServiceSubmissionController::class)
                        ->parameters(['submissions' => 'submission'])
                        ->only(['index', 'show', 'edit', 'update']);
                });
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

Route::middleware('permission:manage absensi')->prefix('absensi')->as('absensi.')->group(function () {
    Route::get('input', [AttendanceCheckController::class, 'index'])->name('input');
    Route::post('check-in', [AttendanceCheckController::class, 'checkIn'])->name('check-in');
    Route::post('check-out', [AttendanceCheckController::class, 'checkOut'])->name('check-out');

    Route::get('settings', [AttendanceSettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [AttendanceSettingController::class, 'update'])->name('settings.update');

    Route::get('holidays', [AttendanceHolidayController::class, 'index'])->name('holidays.index');
    Route::get('monthly', [AttendanceController::class, 'monthly'])->name('monthly');
    Route::get('yearly', [AttendanceController::class, 'yearly'])->name('yearly');
    Route::get('export/detail', [AttendanceController::class, 'exportDetail'])->name('export.detail');
    Route::get('export/monthly', [AttendanceController::class, 'exportMonthly'])->name('export.monthly');
    Route::get('export/yearly', [AttendanceController::class, 'exportYearly'])->name('export.yearly');
    Route::get('create', [AttendanceController::class, 'create'])->name('create');
    Route::post('/', [AttendanceController::class, 'store'])->name('store');
    Route::get('/', [AttendanceController::class, 'index'])->name('index');
    Route::get('{attendance}/edit', [AttendanceController::class, 'edit'])->name('edit');
    Route::put('{attendance}', [AttendanceController::class, 'update'])->name('update');
});

Route::middleware('permission:manage lapak')->group(function () {
    Route::resource('lapak', ShopController::class);
    Route::resource('kategori-lapak', ShopCategoryController::class)->except(['show']);
});
Route::middleware('permission:manage wisata')->group(function () {
    Route::resource('wisata', TourismController::class)->parameters([
        'wisata' => 'tourism',
    ]);
});

Route::middleware('permission:manage galeri')->group(function () {
    Route::resource('galeri', GalleryController::class)->parameters([
        'galeri' => 'gallery',
    ]);
});

Route::middleware('permission:manage infografis')->group(function () {
    Route::resource('hamlets', HamletController::class)->except(['show']);
    Route::resource('population-summaries', PopulationSummaryController::class)->except(['show']);
    Route::get('population-stats/chart-view', [PopulationStatController::class, 'chartView'])
        ->name('population-stats.chart-view');
    Route::resource('population-stats', PopulationStatController::class)->except(['show']);

    Route::get('population-summaries/template', [PopulationImportController::class, 'templateSummaries'])
        ->name('population-summaries.template');
    Route::post('population-summaries/import', [PopulationImportController::class, 'importSummaries'])
        ->name('population-summaries.import');

    Route::get('population-stats/template', [PopulationImportController::class, 'templateStats'])
        ->name('population-stats.template');
    Route::post('population-stats/import', [PopulationImportController::class, 'importStats'])
        ->name('population-stats.import');
    Route::resource('apbdes', ApbdesController::class)->except(['show']);

    Route::get('apbdes/export/excel', [ApbdesController::class, 'exportExcel'])
    ->name('apbdes.export-excel');

    Route::get('apbdes/chart-view', [ApbdesController::class, 'chartView'])
    ->name('apbdes.chart-view');

    Route::resource('bansos-program', SocialAssistanceProgramController::class)->except(['show']);
    Route::resource('bansos-recipient', SocialAssistanceRecipientController::class)->except(['show']);
    Route::get('bansos-chart', [SocialAssistanceChartController::class, 'index'])->name('bansos-chart.index');

    Route::resource('stunting-records', StuntingRecordController::class)->except(['show']);
Route::get('stunting-chart', [StuntingChartController::class, 'index'])->name('stunting-chart.index');
Route::get('stunting-records/export/excel', [StuntingRecordController::class, 'exportExcel'])->name('stunting-records.export-excel');
Route::resource('idm-summaries', IdmSummaryController::class);
Route::resource('idm-indicators', IdmIndicatorController::class)->except(['show']);
Route::resource('sdgs-summaries', SdgsSummaryController::class);
Route::resource('sdgs-goal-values', SdgsGoalValueController::class)->except(['show']);
});


    });

require __DIR__.'/auth.php';
