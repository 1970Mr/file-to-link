<?php

namespace App\Support\Telegram\Handlers;

use App\Exceptions\NoMessageException;
use App\Models\TelegramUpdate;
use App\Support\Telegram\Contracts\Handler;
use Telegram\Bot\Objects\Update;

class MessageHandler extends Handler
{
    /**
     * @throws NoMessageException
     */
    public function handle(Update $update, TelegramUpdate $telegramUpdate): void
    {
        if (!$update->has('message')) {
            throw new NoMessageException();
        }
        $this->next($update, $telegramUpdate);
    }
}
