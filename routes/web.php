<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Admin\EquipmentStockController;
use App\Http\Controllers\Admin\AssignmentController;

// Ana sayfa login'e yönlendir
Route::get('/', function () {
    return redirect()->route('login');
});

// Test route for middleware
Route::get('/test-middleware', function () {
    return 'Middleware test successful';
})->middleware('role:admin');

// Login sayfaları
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LogoutController::class, 'logout'])->name('logout');

// Tüm admin route'ları auth middleware ile korunuyor
Route::middleware(['auth'])->prefix('admin')->group(function () {
    
    // Dashboard - Tüm roller erişebilir
    Route::get('/', [App\Http\Controllers\Admin\HomeController::class, 'index'])->name('admin.dashboard');
    Route::get('/stats', [App\Http\Controllers\Admin\HomeController::class, 'getStats'])->name('admin.stats');
    
    // ===== ADMIN VE EKİP YETKİLİSİ ROUTE'LARI =====
    Route::middleware(['role:admin,ekip_yetkilisi'])->group(function () {
        
        // Ekipman Yönetimi
        Route::get('/stock', [App\Http\Controllers\Admin\EquipmentStockController::class, 'index'])->name('admin.stock');
        Route::post('/stock', [App\Http\Controllers\Admin\EquipmentStockController::class, 'store'])->name('stock.store');
        Route::get('/stock/data', [App\Http\Controllers\Admin\EquipmentStockController::class, 'getStockData'])->name('admin.stock.data');
        Route::get('/stock/statistics', [App\Http\Controllers\Admin\EquipmentStockController::class, 'getStatistics'])->name('admin.stock.statistics');
        Route::get('/stock/validate-code', [App\Http\Controllers\Admin\EquipmentStockController::class, 'validateCode'])->name('admin.stock.validate-code');
        Route::get('/stock/validate-reference-code', [App\Http\Controllers\Admin\EquipmentStockController::class, 'validateReferenceCode'])->name('admin.stock.validate-reference-code');
        Route::get('/stock/{id}/info', [App\Http\Controllers\Admin\EquipmentStockController::class, 'getEquipmentInfo'])->name('admin.stock.info');
        Route::get('/stock/{id}/codes', [App\Http\Controllers\Admin\EquipmentStockController::class, 'getEquipmentCodes'])->name('admin.stock.codes');
        Route::get('/stock/{id}/detailed-codes', [App\Http\Controllers\Admin\EquipmentStockController::class, 'getDetailedStockCodes'])->name('admin.stock.detailed-codes');
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

        // Kategori Yönetimi
        Route::get('/kategori', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('admin.kategori');
        Route::get('/kategori/data', [App\Http\Controllers\Admin\CategoryController::class, 'getCategoryData'])->name('admin.kategori.data');
        Route::post('/kategori', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('admin.kategori.store');
        Route::get('/kategori/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'show'])->name('admin.kategori.show');
        Route::put('/kategori/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('admin.kategori.update');
        Route::delete('/kategori/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('admin.kategori.destroy');
        Route::post('/kategori/bulk-delete', [App\Http\Controllers\Admin\CategoryController::class, 'bulkDestroy'])->name('admin.kategori.bulk-destroy');
        Route::get('/kategori/export/csv', [App\Http\Controllers\Admin\CategoryController::class, 'exportCsv'])->name('admin.kategori.export.csv');

        // Ekipman Durumu
        Route::get('/ekipmanDurumu', [App\Http\Controllers\Admin\EquipmentStatusController::class, 'index'])->name('admin.equipmentStatus');

        // Arıza Yönetimi
        Route::prefix('fault')->name('admin.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\FaultController::class, 'index'])->name('fault');
            Route::get('/create', [App\Http\Controllers\Admin\FaultController::class, 'create'])->name('fault.create');
            Route::post('/', [App\Http\Controllers\Admin\FaultController::class, 'store'])->name('fault.store');
            Route::get('/status', [App\Http\Controllers\Admin\FaultController::class, 'status'])->name('fault.status');
            Route::get('/{id}', [App\Http\Controllers\Admin\FaultController::class, 'show'])->name('fault.show');
            Route::post('/resolve', [App\Http\Controllers\Admin\FaultController::class, 'resolve'])->name('fault.resolve');
            Route::patch('/{id}/status', [App\Http\Controllers\Admin\FaultController::class, 'updateStatus'])->name('fault.updateStatus');
            Route::get('/{id}/resolved', [App\Http\Controllers\Admin\FaultController::class, 'getResolvedFault'])->name('fault.resolved');
        });
    });

    // ===== SADECE ADMIN ROUTE'LARI =====
    Route::middleware(['role:admin'])->group(function () {
        
        // Kullanıcı Yönetimi
        Route::get('/kullanicilar', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users');
        Route::get('/kullanicilar/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.users.create');
        Route::post('/kullanicilar', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.users.store');
        Route::post('/kullanicilar/bulk-delete', [App\Http\Controllers\Admin\UserController::class, 'bulkDelete'])->name('admin.users.bulkDelete');
        Route::get('/kullanicilar/export/excel', [App\Http\Controllers\Admin\UserController::class, 'exportExcel'])->name('admin.users.exportExcel');
        Route::get('/kullanicilar/{id}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.users.show');
        Route::put('/kullanicilar/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/kullanicilar/{id}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');
        Route::patch('/kullanicilar/{id}/status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('admin.users.toggleStatus');
    });

    // ===== TÜM ROLLER İÇİN ROUTE'LAR =====
    
    // Arıza Bildirimi (Tüm roller bildirebilir)
    Route::get('/fault-report', [App\Http\Controllers\Admin\FaultController::class, 'create'])->name('admin.fault.create');
    Route::post('/fault-report', [App\Http\Controllers\Admin\FaultController::class, 'store'])->name('admin.fault.store');

    // Zimmet İşlemleri (Tüm roller)
    Route::get('/zimmet', [App\Http\Controllers\Admin\AssignmentController::class, 'create'])->name('admin.zimmetAl');
    Route::post('/zimmet', [App\Http\Controllers\Admin\AssignmentController::class, 'store'])->name('admin.zimmetAl.store');
    Route::get('/teslim', [App\Http\Controllers\Admin\AssignmentController::class, 'myAssignments'])->name('admin.teslimEt');
    Route::get('/teslim-al/{id}', [App\Http\Controllers\Admin\AssignmentController::class, 'showAssignment'])->name('admin.teslimAl.show');
    Route::put('/teslim-al/{id}', [App\Http\Controllers\Admin\AssignmentController::class, 'completeAssignment'])->name('admin.teslimAl');
    Route::put('/teslim-et/{id}', [App\Http\Controllers\Admin\AssignmentController::class, 'completeAssignment'])->name('admin.teslimEt.complete');
    Route::get('/assignments/item/{id}/photos', [App\Http\Controllers\Admin\AssignmentController::class, 'itemPhotos'])->name('admin.assignments.item.photos');
    Route::post('/assignments/item/{id}/return', [App\Http\Controllers\Admin\AssignmentController::class, 'returnItem'])->name('admin.assignments.item.return');
    Route::post('/finish/{id}', [App\Http\Controllers\Admin\AssignmentController::class, 'finishAssignment'])->name('assignments.finish');

    // Giden-Gelen İşlemleri - Admin ve Ekip Yetkilisi
    Route::middleware(['role:admin,ekip_yetkilisi'])->group(function () {
        Route::get('/gidenGelen', [App\Http\Controllers\Admin\AssignmentController::class, 'comingGoing'])->name('admin.gidenGelen');
    });

    // Profil
    Route::get('/profilim', [App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('admin.profile');
    Route::put('/profilim', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('admin.profile.update');
    Route::put('/profilim/password', [App\Http\Controllers\Admin\ProfileController::class, 'changePassword'])->name('admin.profile.password');

    // Raporlama - Sadece Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/raporlama', [App\Http\Controllers\Admin\ReportingController::class, 'index'])->name('admin.reporting');
        Route::get('/raporlama/export', [App\Http\Controllers\Admin\ReportingController::class, 'export'])->name('admin.reporting.export');
    });

    // Ekipman Listesi (Sadece görüntüleme)
    Route::get('/ekipmanlar', [App\Http\Controllers\Admin\EquipmentController::class, 'index'])->name('admin.equipments');
    Route::get('/ekipmanlar/data', [App\Http\Controllers\Admin\EquipmentController::class, 'getEquipmentData'])->name('admin.equipments.data');
    Route::get('/ekipmanlar/export/csv', [App\Http\Controllers\Admin\EquipmentController::class, 'exportCsv'])->name('admin.equipments.export.csv');
    Route::get('/ekipmanlar/{id}', [App\Http\Controllers\Admin\EquipmentController::class, 'show'])->name('admin.equipments.show');
    Route::get('/ekipmanlar/{id}/qr-download', [App\Http\Controllers\Admin\EquipmentController::class, 'downloadQR'])->name('admin.equipment.qr-download');
    Route::delete('/ekipmanlar/stock/{id}', [App\Http\Controllers\Admin\EquipmentController::class, 'destroyStock'])->name('admin.equipments.stock.destroy');
    Route::post('/ekipmanlar/bulk-delete', [App\Http\Controllers\Admin\EquipmentController::class, 'bulkDelete'])->name('admin.equipments.bulk-delete');

    // Ekipman Stok Güncelleme (Sadece görüntüleme)
    Route::get('/equipment-stock/{id}/update-field', [App\Http\Controllers\Admin\EquipmentStockController::class, 'updateField'])->name('admin.equipment-stock.update-field');
    
    // Zimmet Teslim Etme
    Route::put('/teslim-et/{id}', [App\Http\Controllers\Admin\AssignmentController::class, 'returnAssignment'])->name('admin.assignments.return');
});