<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);
Route::get('logout', [LogoutController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return view('home');
    })->name('home');
});
Route::middleware(['auth', 'is_admin'])->group(function () {
});
Route::get('/admin', function () {
    return view('admin.container');
})->name('admin.dashboard');

Route::get('/admin/stock', function () {
    return view('admin.stock');
})->name('admin.stock');

Route::get('/admin/kategori', function () {
    return view('admin.categoryList');
})->name('admin.kategori');

Route::get('/admin/ekipmanÖzelikleri', function () {
    return view('admin.equipmentFeatures');
})->name('admin.ekipman');

Route::get('/admin/gidenGelen', function () {
    return view('admin.comingGoing');
})->name('admin.gidenGelen');

Route::get('/admin/ekipmanDurumu', function () {
    return view('admin.equipmentStatus');
})->name('admin.equipmentStatus');

Route::get('/admin/durumKontrol', function () {
    return view('admin.statusCheck');
})->name('admin.statusCheck');

Route::get('/admin/arizabildirimi', function () {
    return view('admin.fault');
})->name('admin.fault');

Route::get('/admin/konumTakibi', function () {
    return view('admin.location');
})->name('admin.location');

Route::get('/admin/onaySureci', function () {
    return view('admin.approvalProcces');
})->name('admin.approvalProcces');

Route::get('/admin/raporlama', function () {
    return view('admin.reporting');
})->name('admin.reporting');

Route::get('/admin/veriAnalizi', function () {
    return view('admin.dataAnalysis');
})->name('admin.dataAnalysis');

    Route::get('/admin/ekipmanAnalizi', function () {
    return view('admin.equipmentAnalysis');
})->name('admin.equipmentAnalysis');

Route::get('/admin/uyeAnalizi', function () {
    return view('admin.memberAnalysis');
})->name('admin.memberAnalysis');

Route::get('/admin/kullanicilar', function () {
    return view('admin.users');
})->name('admin.users');

Route::get('/admin/isEkle', function () {
    return view('admin.isEkle');
})->name('admin.isEkle');

Route::get('/admin/ekipmanlar', function () {
    return view('admin.equipment');
})->name('admin.equipments');

