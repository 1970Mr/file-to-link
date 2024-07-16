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
        $fileSize = $update->getMessage()->getDocument()->getFileSize();
        $chatId = $update->getMessage()->getChat()->getId();

        try {
            if ($fileSize > config('services.telegram.max_file_size')) {
                throw new FileSizeExceededException();
            }

            $this->next($update, $telegramUpdate);
        } catch (FileSizeExceededException $e) {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => "The file size exceeds the maximum limit of 500 MB.",
            ]);
        }
    }
}
