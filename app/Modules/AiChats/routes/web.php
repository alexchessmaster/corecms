<?php

use App\Http\Middleware\LanguageAdminMiddleware;
use App\Modules\AiChats\Http\Controllers\AiChatController;
use App\Modules\AiChats\Http\Controllers\AiPersonaController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'web', LanguageAdminMiddleware::class, 'auth', 'verified',
])->prefix('admin')->name('admin.')->group(function () {
    // AI Personas management routes
    Route::resource('ai-personas', AiPersonaController::class);
    Route::get('my-personas', [AiPersonaController::class, 'myPersonas'])->name('ai-personas.my');
    Route::post('ai-personas/{aiPersona}/toggle', [AiPersonaController::class, 'toggleActive'])->name('ai-personas.toggle');
    Route::post('ai-personas/{aiPersona}/clone', [AiPersonaController::class, 'duplicate'])->name('ai-personas.clone');
    Route::get('popular-personas', [AiPersonaController::class, 'popular'])->name('ai-personas.popular');
    Route::get('search-personas', [AiPersonaController::class, 'search'])->name('ai-personas.search');

    // AI Chat session routes
    Route::resource('ai-chats', AiChatController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::get('ai-chats/{chat}/messages', [AiChatController::class, 'retrieveMessages'])->name('ai-chats.messages');
    Route::post('ai-chats/{chat}/send-message', [AiChatController::class, 'dispatchMessage'])->name('ai-chats.send');
    Route::delete('ai-chats/{chat}/clear', [AiChatController::class, 'purgeChat'])->name('ai-chats.clear');
    Route::put('ai-chats/{chat}/change-persona', [AiChatController::class, 'switchPersona'])->name('ai-chats.change-persona');
});
