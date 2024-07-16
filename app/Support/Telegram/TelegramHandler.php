<?php

namespace App\Support\Telegram;

use App\Exceptions\NoMessageException;
use App\Models\TelegramUpdate;
use App\Services\File\File;
use App\Support\Telegram\Handlers\DocumentHandler;
use App\Support\Telegram\Handlers\FileSaveHandler;
use App\Support\Telegram\Handlers\FileSizeHandler;
use App\Support\Telegram\Handlers\MessageHandler;
use Illuminate\Http\Request;
use Telegram\Bot\Api;

readonly class TelegramHandler
{
    public function __construct(
        private Api  $telegram,
    )
    {
    }

    /**
     * @throws NoMessageException
     */
    public function handle(Request $request): void
    {
        $update = $this->telegram->getWebhookUpdate();
        $telegramUpdate = TelegramUpdate::query()->create(['request' => $request->all()]);

        $messageHandler = resolve(MessageHandler::class);
        $documentHandler = resolve(DocumentHandler::class);
        $fileSizeHandler = resolve(FileSizeHandler::class);
        $fileSaveHandler = resolve(FileSaveHandler::class);

        $messageHandler
            ->setNext($documentHandler)
            ->setNext($fileSizeHandler)
            ->setNext($fileSaveHandler);

        $messageHandler->handle($update, $telegramUpdate);
    }
}
