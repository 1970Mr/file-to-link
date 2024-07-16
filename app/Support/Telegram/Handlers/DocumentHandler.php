<?php

namespace App\Support\Telegram\Handlers;

use App\Exceptions\NotDocumentException;
use App\Models\TelegramUpdate;
use App\Support\Telegram\Contracts\Handler;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

class DocumentHandler extends Handler
{
    public function __construct(private readonly Api $telegram) {}

    public function handle(Update $update, TelegramUpdate $telegramUpdate): void
    {
        try {
            $message = $update->getMessage();
            if (!$message->has('document')) {
                throw new NotDocumentException();
            }

            $this->next($update, $telegramUpdate);
        } catch (NotDocumentException $e) {
            $this->telegram->sendMessage([
                'chat_id' => $update->getMessage()->getChat()->getId(),
                'text' => "Please send a document.",
            ]);
        }
    }
}
