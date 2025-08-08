<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Admin\EquipmentStockController;

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
Route::middleware(['auth', 'is_admin'])->group(function () {
});
Route::get('/admin', function () {
    return view('admin.home.index');
})->name('admin.dashboard');
   
Route::get('/admin/stock', [App\Http\Controllers\Admin\EquipmentStockController::class, 'index'])->name('admin.stock');
Route::get('/admin/stock/data', [App\Http\Controllers\Admin\EquipmentStockController::class, 'getStockData'])->name('admin.stock.data');
Route::get('/admin/stock/statistics', [App\Http\Controllers\Admin\EquipmentStockController::class, 'getStatistics'])->name('admin.stock.statistics');
Route::get('/admin/stock/validate-code', [App\Http\Controllers\Admin\EquipmentStockController::class, 'validateCode'])->name('admin.stock.validate-code');
Route::get('/admin/stock/validate-reference-code', [App\Http\Controllers\Admin\EquipmentStockController::class, 'validateReferenceCode'])->name('admin.stock.validate-reference-code');
Route::get('/admin/stock/{id}/info', [App\Http\Controllers\Admin\EquipmentStockController::class, 'getEquipmentInfo'])->name('admin.stock.info');
Route::post('/admin/stock/bulk-delete', [App\Http\Controllers\Admin\EquipmentStockController::class, 'bulkDestroy'])->name('admin.stock.bulk-destroy');

Route::post('/admin/stock/{id}/operation', [App\Http\Controllers\Admin\EquipmentStockController::class, 'stockOperation'])->name('admin.stock.operation');
Route::get('/admin/stock/{id}', [App\Http\Controllers\Admin\EquipmentStockController::class, 'show'])->name('admin.stock.show');
Route::put('/admin/stock/{id}', [App\Http\Controllers\Admin\EquipmentStockController::class, 'update'])->name('admin.stock.update');
Route::delete('/admin/stock/{id}', [App\Http\Controllers\Admin\EquipmentStockController::class, 'destroy'])->name('admin.stock.destroy');

// Stok ekleme formunu göster
Route::get('/admin/Ekle', function () {
    $categories = \App\Models\EquipmentCategory::orderBy('name')->get();
    return view('admin.stock.create', compact('categories'));
})->name('stock.create');

// Stok ekleme işlemi
Route::post('/admin/stock', [EquipmentStockController::class, 'store'])->name('stock.store');

Route::get('/admin/kategori', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('admin.kategori');
Route::get('/admin/kategori/data', [App\Http\Controllers\Admin\CategoryController::class, 'getCategoryData'])->name('admin.kategori.data');
Route::post('/admin/kategori', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('admin.kategori.store');
Route::get('/admin/kategori/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'show'])->name('admin.kategori.show');
Route::put('/admin/kategori/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('admin.kategori.update');
Route::delete('/admin/kategori/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('admin.kategori.destroy');
Route::post('/admin/kategori/bulk-delete', [App\Http\Controllers\Admin\CategoryController::class, 'bulkDestroy'])->name('admin.kategori.bulk-destroy');
Route::get('/admin/kategori/export/csv', [App\Http\Controllers\Admin\CategoryController::class, 'exportCsv'])->name('admin.kategori.export.csv');

// Route::get('/admin/ekipmanÖzelikleri', function () {
//     return view('admin.equipment.Features');
// })->name('admin.ekipman');

Route::get('/admin/gidenGelen', function () {
    return view('admin.comingGoing.index');
})->name('admin.gidenGelen');

Route::get('/admin/ekipmanDurumu', function () {
    return view('admin.equipment.Status');
})->name('admin.equipmentStatus');

Route::get('/admin/durumKontrol', function () {
    return view('admin.statusCheck.index');
})->name('admin.statusCheck'); 

Route::get('/admin/ArizaBildirimi', function () {
    return view('admin.fault.index');
})->name('admin.fault');

Route::get('/admin/Talepler', function () {
    return view('admin.requests.index');
})->name('requests.index');

Route::get('/admin/ArizaDurumu', function () {
    return view('admin.fault.Status');
})->name('fault.status');

Route::get('/admin/raporlama', function () {
    return view('admin.reporting.index');
})->name('admin.reporting');

// Route::get('/admin/veriAnalizi', function () {
//     return view('admin.analysis.dataAnalysis');
// })->name('admin.dataAnalysis');

//     Route::get('/admin/ekipmanAnalizi', function () {
//     return view('admin.equipment.Analysis');
// })->name('admin.equipmentAnalysis');

// Route::get('/admin/uyeAnalizi', function () {
//     return view('admin.analysis.memberAnalysis');
// })->name('admin.memberAnalysis');

Route::get('/admin/kullanicilar', function () {
    return view('admin.users.index');
})->name('admin.users');

Route::get('/admin/isEkle', function () {
    return view('admin.works.index');
})->name('admin.isEkle');

Route::get('/admin/ekipmanlar', [App\Http\Controllers\Admin\EquipmentController::class, 'index'])->name('admin.equipments');
Route::get('/admin/ekipmanlar/data', [App\Http\Controllers\Admin\EquipmentController::class, 'getEquipmentData'])->name('admin.equipments.data');
Route::get('/admin/ekipmanlar/{id}', [App\Http\Controllers\Admin\EquipmentController::class, 'show'])->name('admin.equipments.show');
Route::put('/admin/ekipmanlar/{id}', [App\Http\Controllers\Admin\EquipmentController::class, 'update'])->name('admin.equipments.update');
Route::delete('/admin/ekipmanlar/{id}', [App\Http\Controllers\Admin\EquipmentController::class, 'destroy'])->name('admin.equipments.destroy');
Route::get('/admin/ekipmanlar/export/csv', [App\Http\Controllers\Admin\EquipmentController::class, 'exportCsv'])->name('admin.equipments.export.csv');

Route::get('/admin/profilim', function () {
    return view('admin.profile.index');
})->name('admin.profile');

