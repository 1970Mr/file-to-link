<?php

namespace App\Support\Telegram\Handlers;

use App\Exceptions\FileSizeExceededException;
use App\Models\TelegramUpdate;
use App\Support\Telegram\Contracts\Handler;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

class FileSizeHandler extends Handler
{
    public function __construct(private readonly Api $telegram) {}

    public function handle(Update $update, TelegramUpdate $telegramUpdate): void
    {
        try {
            $fileSize = $update->getMessage()->getDocument()->getFileSize();
            if ($fileSize > 500 * 1024 * 1024) {
                throw new FileSizeExceededException();
            }

            $this->next($update, $telegramUpdate);
        } catch (FileSizeExceededException $e) {
            $this->telegram->sendMessage([
                'chat_id' => $update->getMessage()->getChat()->getId(),
                'text' => "The file size exceeds the maximum limit of 500 MB.",
            ]);
        }
    }
}
