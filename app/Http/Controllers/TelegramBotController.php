<?php

namespace App\Http\Controllers;

use App\Models\TelegramUpdate;
use App\Services\File\File;
use Illuminate\Http\Request;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

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
        $telegramUpdateModel = TelegramUpdate::query()->create(['request' => $request->all()]);

        $update = $this->telegram->getWebhookUpdate();

        if (!$update->has('message')) {
            return;
        }

        $message = $update->getMessage();

        if ($message->has('document')) {
            $fileId = $message->getDocument()->getFileId();
            $file = $this->telegram->getFile(['file_id' => $fileId]);
            $filePath = $file->getFilePath();

            // Check if file size exceeds 500 MB
            $fileSize = $message->getDocument()->getFileSize();
            if ($fileSize > 500 * 1024 * 1024) {
                $this->telegram->sendMessage([
                    'chat_id' => $message->getChat()->getId(),
                    'text' => "The file size exceeds the maximum limit of 500 MB.",
                ]);
                return;
            }

            $fileUrl = "https://api.telegram.org/file/bot" . config('services.telegram.bot_token') . "/" . $filePath;
            $link = $this->fileService->saveFile($telegramUpdateModel, $fileUrl, $message->getDocument()->getFileName());

            $this->telegram->sendMessage([
                'chat_id' => $message->getChat()->getId(),
                'text' => "Your file link: " . $link,
            ]);
        } else {
            $this->telegram->sendMessage([
                'chat_id' => $message->getChat()->getId(),
                'text' => "Please send a document.",
            ]);
        }
    }
}
