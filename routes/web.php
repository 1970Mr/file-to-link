<?php

use App\Http\Controllers\SetTelegramWebhookController;
use App\Http\Controllers\TelegramBotController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::post('/telegram/webhook', [TelegramBotController::class, 'handle'])
    ->withoutMiddleware(VerifyCsrfToken::class);

Route::get('/set-webhook/{token}', SetTelegramWebhookController::class)->name('set-webhook');
