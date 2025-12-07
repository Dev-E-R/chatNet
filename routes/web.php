<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserChatController;
use App\Http\Controllers\GlobalChatController;

// Redirigir la raíz al login
Route::get('/', function () {
    return redirect('/login');
});

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas (requieren autenticación)
Route::middleware('auth')->group(function () {
    // Rutas de chat privado
    Route::get('/chatNet', [UserChatController::class, 'index'])->name('chatNet');
    Route::get('/chat/{id}/messages', [UserChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/{id}/send', [UserChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/chat/create', [UserChatController::class, 'createChat'])->name('chat.create');
    Route::post('/room/create', [UserChatController::class, 'createRoom'])->name('room.create');
    
    // Rutas de chat global
    Route::get('/global-chat', [GlobalChatController::class, 'index'])->name('global-chat');
    Route::get('/global-chat/messages', [GlobalChatController::class, 'getMessages'])->name('global-chat.messages');
    Route::post('/global-chat/send', [GlobalChatController::class, 'sendMessage'])->name('global-chat.send');
});
