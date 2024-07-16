<?php

use App\Http\Controllers\SetWebhookController;
use App\Http\Controllers\TelegramBotController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::post('/telegram/webhook', [TelegramBotController::class, 'handle'])
    ->withoutMiddleware(VerifyCsrfToken::class);

Route::get('/set-webhook/{token}', SetWebhookController::class)->name('set-webhook');
