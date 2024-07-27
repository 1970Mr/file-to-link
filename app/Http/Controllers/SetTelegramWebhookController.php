<?php

namespace App\Http\Controllers;

use Telegram\Bot\Laravel\Facades\Telegram;

class SetTelegramWebhookController extends Controller
{
    public function __invoke(string $token): string
    {
        abort_if($token !== config('services.telegram.bot_token'), 404);

        $response = Telegram::setWebhook(['url' => config('app.url') . '/telegram/webhook']);
        return $response ? 'Webhook is set successfully.' : 'Webhook setting failed!';
    }
}
