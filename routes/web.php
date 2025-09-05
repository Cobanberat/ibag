<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Admin\EquipmentStockController;
use App\Http\Controllers\Admin\AssignmentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);
Route::post('logout', [LogoutController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return view('home');
    })->name('home');
});

// Tüm admin route'ları auth middleware ile korunuyor
Route::middleware(['auth'])->prefix('admin')->group(function () {
    
    Route::get('/', function () {
        return view('admin.home.index');
    })->name('admin.dashboard');
    
    // Stock routes
    Route::get('/stock', [App\Http\Controllers\Admin\EquipmentStockController::class, 'index'])->name('admin.stock');
    Route::post('/stock', [App\Http\Controllers\Admin\EquipmentStockController::class, 'store'])->name('admin.stock.store');
    Route::get('/stock/data', [App\Http\Controllers\Admin\EquipmentStockController::class, 'getStockData'])->name('admin.stock.data');
    Route::get('/stock/statistics', [App\Http\Controllers\Admin\EquipmentStockController::class, 'getStatistics'])->name('admin.stock.statistics');
    Route::get('/stock/validate-code', [App\Http\Controllers\Admin\EquipmentStockController::class, 'validateCode'])->name('admin.stock.validate-code');
    Route::get('/stock/validate-reference-code', [App\Http\Controllers\Admin\EquipmentStockController::class, 'validateReferenceCode'])->name('admin.stock.validate-reference-code');
    Route::get('/stock/{id}/info', [App\Http\Controllers\Admin\EquipmentStockController::class, 'getEquipmentInfo'])->name('admin.stock.info');
    Route::post('/stock/bulk-delete', [App\Http\Controllers\Admin\EquipmentStockController::class, 'bulkDestroy'])->name('admin.stock.bulk-destroy');
    Route::post('/stock/{id}/operation', [App\Http\Controllers\Admin\EquipmentStockController::class, 'stockOperation'])->name('admin.stock.operation');
    Route::get('/stock/excel-template', [App\Http\Controllers\Admin\EquipmentStockController::class, 'downloadExcelTemplate'])->name('admin.stock.excel-template');
    Route::post('/stock/preview-excel', [App\Http\Controllers\Admin\EquipmentStockController::class, 'previewExcel'])->name('admin.stock.preview-excel');
    Route::post('/stock/import-excel', [App\Http\Controllers\Admin\EquipmentStockController::class, 'importExcel'])->name('admin.stock.import-excel');
    Route::get('/stock/{id}', [App\Http\Controllers\Admin\EquipmentStockController::class, 'show'])->name('admin.stock.show');
    Route::put('/stock/{id}', [App\Http\Controllers\Admin\EquipmentStockController::class, 'update'])->name('admin.stock.update');
    Route::delete('/stock/{id}', [App\Http\Controllers\Admin\EquipmentStockController::class, 'destroy'])->name('admin.stock.destroy');
    Route::post('/stock/{id}/repair', [App\Http\Controllers\Admin\EquipmentStockController::class, 'repair'])->name('admin.stock.repair');
    Route::post('/stock/{id}/complete-maintenance', [App\Http\Controllers\Admin\EquipmentStockController::class, 'completeMaintenance'])->name('admin.stock.complete-maintenance');

    // Stok ekleme formunu göster
    Route::get('/Ekle', function () {
        $categories = \App\Models\EquipmentCategory::orderBy('name')->get();
        return view('admin.stock.create', compact('categories'));
    })->name('stock.create');

    // Stok ekleme işlemi - admin.stock.store route'u yukarıda tanımlı

    // Category routes
    Route::get('/kategori', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('admin.kategori');
    Route::get('/kategori/data', [App\Http\Controllers\Admin\CategoryController::class, 'getCategoryData'])->name('admin.kategori.data');
    Route::post('/kategori', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('admin.kategori.store');
    Route::get('/kategori/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'show'])->name('admin.kategori.show');
    Route::put('/kategori/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('admin.kategori.update');
    Route::delete('/kategori/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('admin.kategori.destroy');
    Route::post('/kategori/bulk-delete', [App\Http\Controllers\Admin\CategoryController::class, 'bulkDestroy'])->name('admin.kategori.bulk-destroy');
    Route::get('/kategori/export/csv', [App\Http\Controllers\Admin\CategoryController::class, 'exportCsv'])->name('admin.kategori.export.csv');

    // Equipment Status routes
    Route::get('/ekipmanDurumu', [App\Http\Controllers\Admin\EquipmentStatusController::class, 'index'])->name('admin.equipmentStatus');

    // Fault routes
    Route::prefix('fault')->name('admin.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\FaultController::class, 'index'])->name('fault');
        Route::post('/', [App\Http\Controllers\Admin\FaultController::class, 'store'])->name('fault.store');
        Route::get('/status', [App\Http\Controllers\Admin\FaultController::class, 'status'])->name('fault.status');
        Route::get('/{id}', [App\Http\Controllers\Admin\FaultController::class, 'show'])->name('fault.show');
        Route::post('/resolve', [App\Http\Controllers\Admin\FaultController::class, 'resolve'])->name('fault.resolve');
        Route::patch('/{id}/status', [App\Http\Controllers\Admin\FaultController::class, 'updateStatus'])->name('fault.updateStatus');
        Route::get('/{id}/resolved', [App\Http\Controllers\Admin\FaultController::class, 'getResolvedFault'])->name('fault.resolved');
    });

    // Status Check routes
    Route::get('/durumKontrol', [App\Http\Controllers\Admin\StatusCheckController::class, 'index'])->name('admin.statusCheck');
    Route::get('/durumKontrol/detail', [App\Http\Controllers\Admin\StatusCheckController::class, 'getDetail'])->name('admin.statusCheck.detail');
    Route::post('/durumKontrol/update-status', [App\Http\Controllers\Admin\StatusCheckController::class, 'updateStatus'])->name('admin.statusCheck.updateStatus'); 

    // Requests routes
    Route::get('/Talep', function () {
        return view('admin.requests.index');
    })->name('requests.index');

    // Fault Status routes
    Route::get('/ArizaDurumu', function () {
        return view('admin.fault.Status');
    })->name('fault.status');

    // Reporting routes
    Route::get('/raporlama', function () {
        return view('admin.reporting.index');
    })->name('admin.reporting');

    // Users routes
    Route::get('/kullanicilar', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users');
    Route::post('/kullanicilar', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.users.store');
    Route::get('/kullanicilar/{id}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.users.show');
    Route::put('/kullanicilar/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/kullanicilar/{id}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::patch('/kullanicilar/{id}/status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('admin.users.toggleStatus');
    Route::post('/kullanicilar/bulk-delete', [App\Http\Controllers\Admin\UserController::class, 'bulkDelete'])->name('admin.users.bulkDelete');
    Route::get('/kullanicilar/export/excel', [App\Http\Controllers\Admin\UserController::class, 'exportExcel'])->name('admin.users.exportExcel');

    // Assignment routes
    Route::get('/teslim', [App\Http\Controllers\Admin\AssignmentController::class, 'myAssignments'])->name('admin.teslimEt');
    Route::put('/teslim-et/{id}', [App\Http\Controllers\Admin\AssignmentController::class, 'returnAssignment'])->name('admin.teslimAl');
    Route::get('/gidenGelen', [AssignmentController::class, 'comingGoing'])->name('admin.gidenGelen');
    Route::post('/finish/{id}', [AssignmentController::class, 'finish'])->name('assignments.finish');
    Route::get('/zimmet', [App\Http\Controllers\Admin\AssignmentController::class, 'create'])->name('admin.zimmetAl');
    Route::post('/zimmet', [App\Http\Controllers\Admin\AssignmentController::class, 'store'])->name('admin.zimmetAl.store');

    // Equipment routes
    Route::get('/ekipmanlar', [App\Http\Controllers\Admin\EquipmentController::class, 'index'])->name('admin.equipments');
    Route::get('/ekipmanlar/data', [App\Http\Controllers\Admin\EquipmentController::class, 'getEquipmentData'])->name('admin.equipments.data');
    Route::get('/ekipmanlar/{id}', [App\Http\Controllers\Admin\EquipmentController::class, 'show'])->name('admin.equipments.show');
    Route::put('/ekipmanlar/{id}', [App\Http\Controllers\Admin\EquipmentController::class, 'update'])->name('admin.equipments.update');
    Route::delete('/ekipmanlar/{id}', [App\Http\Controllers\Admin\EquipmentController::class, 'destroy'])->name('admin.equipments.destroy');
    Route::get('/ekipmanlar/export/csv', [App\Http\Controllers\Admin\EquipmentController::class, 'exportCsv'])->name('admin.equipments.export.csv');
    Route::get('/ekipmanlar/{id}/qr-download', [App\Http\Controllers\Admin\EquipmentStockController::class, 'downloadQrCode'])->name('admin.equipment.qr-download');
    
    // Equipment Stock inline editing
    Route::post('/equipment-stock/{id}/update-field', [App\Http\Controllers\Admin\EquipmentController::class, 'updateField'])->name('admin.equipment-stock.update-field');

    // Profile routes
    Route::get('/profilim', function () {
        return view('admin.profile.index');
    })->name('admin.profile');
});

