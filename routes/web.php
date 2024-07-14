<?php

use App\Http\Controllers\TelegramBotController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

Route::post('/telegram/webhook', [TelegramBotController::class, 'handle'])
    ->withoutMiddleware(VerifyCsrfToken::class);

Route::get('/set-webhook/{token}', static function ($token) {
    abort_if(
        $token !== config('services.telegram.bot_token'),
        404
    );

    $response = Telegram::setWebhook(['url' => config('app.url') . '/telegram/webhook']);
    return $response ? 'Webhook is set' : 'Webhook setting failed';
});
