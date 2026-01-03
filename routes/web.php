<?php

use App\Http\Controllers\GrupoController;
use App\Http\Controllers\TesisController;
use App\Http\Controllers\RevisionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReporteController; 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController; 

Route::get('/', function () {
    return view('welcome');
});

// Rutas de autenticación manuales
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/perfil/verificar-email/manual', [ProfileController::class, 'verifyManual'])->name('perfil.verify.manual');

// Dashboard principal
Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard')->middleware('auth');

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    
    // Grupos - Solo admin y profesores
    Route::resource('grupos', GrupoController::class)->middleware('role:admin,profesor');
    
    // Tesis - Todos los usuarios autenticados
    Route::resource('tesis', TesisController::class)->parameters(['tesis' => 'tesis']);
    Route::get('tesis/{tesis}/descargar', [TesisController::class, 'descargar'])->name('tesis.descargar');
    
    // Revisiones - Solo admin y profesores
    Route::resource('revisiones', RevisionController::class)->middleware('role:admin,profesor');
    
    // Usuarios - Solo admin
    Route::resource('usuarios', UserController::class)->middleware('role:admin');
});

// Rutas de reportes (protegidas)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/reportes/general', [ReporteController::class, 'reporteGeneral'])->name('reportes.general');
    Route::get('/reportes/tesis', [ReporteController::class, 'reporteTesis'])->name('reportes.tesis');
    Route::get('/reportes/usuarios', [ReporteController::class, 'reporteUsuarios'])->name('reportes.usuarios');
});

// Perfil (solo autenticados)
Route::middleware(['auth'])->group(function () {
    Route::get('/perfil', [ProfileController::class, 'show'])->name('perfil.show');
    Route::post('/perfil/email', [ProfileController::class, 'updateEmail'])->name('perfil.email');
    Route::get('/perfil/verificar-email/{token}', [ProfileController::class, 'verifyEmail'])->name('perfil.verify');
    Route::post('/perfil/password', [ProfileController::class, 'updatePassword'])->name('perfil.password');
    Route::post('/perfil/password-token', [ProfileController::class, 'sendPasswordToken'])->name('perfil.password.token');
});