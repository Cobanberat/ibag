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
   
Route::get('/admin/stock', function () {
    return view('admin.stock.index');
})->name('admin.stock');
Route::get('/admin/Ekle', function () {
    return view('admin.stock.create');
})->name('stock.create');

Route::get('/admin/kategori', function () {
    return view('admin.category.index');
})->name('admin.kategori');

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

Route::get('/admin/ekipmanlar', function () {
    return view('admin.equipment.index');
})->name('admin.equipments');

Route::get('/admin/profilim', function () {
    return view('admin.profile.index');
})->name('admin.profile');

