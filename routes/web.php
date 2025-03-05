<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\UserInformationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
});

Auth::routes();

// Rutas públicas
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    // Rutas de perfil
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
    });

    // Rutas de asistencia para empleados
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::get('/list', [AttendanceController::class, 'list'])->name('list');
        Route::post('/check-in', [AttendanceController::class, 'checkIn'])->name('check-in');
        Route::post('/check-out', [AttendanceController::class, 'checkOut'])->name('check-out');
        Route::post('/break-start', [AttendanceController::class, 'breakStart'])->name('break-start');
        Route::post('/break-end', [AttendanceController::class, 'breakEnd'])->name('break-end');
    });

    // Rutas de información de usuario
    Route::get('/user/edit', [UserInformationController::class, 'edit'])->name('user.edit');
    Route::put('/user/update', [UserInformationController::class, 'update'])->name('user.update');

    // Rutas de administrador
    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => [\App\Http\Middleware\AdminMiddleware::class]], function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        
        // Rutas de usuarios
        Route::resource('users', UserController::class);
        
        // Rutas de asistencias
        Route::get('/attendances', [AdminAttendanceController::class, 'index'])->name('attendances.index');
        
        // Rutas de reportes
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });
});
