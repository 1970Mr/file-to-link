<?php

namespace App\Http\Controllers;

use App\Support\Telegram\TelegramHandler;
use Illuminate\Http\Request;
use Exception;

class TelegramBotController extends Controller
{
    public function __construct(private readonly TelegramHandler $telegramHandler) {}

    public function handle(Request $request): void
    {
        try {
            $this->telegramHandler->handle($request);
        } catch (Exception $e) {
            logger('The program has encountered an error:' . $e->getMessage());
        }
    }
}
