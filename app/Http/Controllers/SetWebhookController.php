<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Telegram\Bot\Laravel\Facades\Telegram;

class SetWebhookController extends Controller
{
    public function __invoke(string $token)
    {
        abort_if($token !== config('services.telegram.bot_token'), 404);

        $response = Telegram::setWebhook(['url' => config('app.url') . '/telegram/webhook']);
        return $response ? 'Webhook is set' : 'Webhook setting failed';
    }
}
