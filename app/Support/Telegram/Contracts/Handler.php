<?php

namespace App\Support\Telegram\Contracts;

use App\Models\TelegramUpdate;
use Telegram\Bot\Objects\Update;

abstract class Handler
{
    protected ?Handler $nextHandler = null;
    public function setNext(Handler $handler): Handler
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    abstract public function handle(Update $update, TelegramUpdate $telegramUpdate): void;

    protected function next(Update $update, TelegramUpdate $telegramUpdate): void
    {
        $this->nextHandler?->handle($update, $telegramUpdate);
    }
}
