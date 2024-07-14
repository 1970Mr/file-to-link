<?php

namespace App\Http\Controllers;

use App\Models\TelegramUpdate;
use App\Services\File\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotController extends Controller
{
    public function __construct(
        private readonly Api  $telegram,
        private readonly File $fileService,
    )
    {
    }

    /**
     * @throws TelegramSDKException
     */
    public function handle(Request $request): void
    {
        $update = $this->telegram->getWebhookUpdate();

        if (!$update->has('message')) {
            return;
        }

        $telegramUpdateModel = TelegramUpdate::query()->create(['request' => $request->all()]);

        $message = $update->getMessage();

        if ($message->has('document')) {
            $fileId = $message->getDocument()->getFileId();
            $fileName = $message->getDocument()->getFileName();

            // Check if file size exceeds 500 MB
            $fileSize = $message->getDocument()->getFileSize();
            if ($fileSize > 500 * 1024 * 1024) {
                $this->telegram->sendMessage([
                    'chat_id' => $message->getChat()->getId(),
                    'text' => "The file size exceeds the maximum limit of 500 MB.",
                ]);
                return;
            }

            $link = $this->fileService->saveFile($telegramUpdateModel, $fileId, $fileName);

            $this->telegram->sendMessage([
                'chat_id' => $message->getChat()->getId(),
                'text' => "Your file link: " . $link . PHP_EOL . 'This link will be available in one hour.',
            ]);
        } else {
            $this->telegram->sendMessage([
                'chat_id' => $message->getChat()->getId(),
                'text' => "Please send a document.",
            ]);
        }
    }
}
