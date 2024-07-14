<?php

namespace App\Services\File;

use App\Jobs\DeleteFile;
use App\Models\TelegramUpdate;
use Illuminate\Support\Facades\Http;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Telegram\Bot\Laravel\Facades\Telegram;

class File
{
    /**
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     */
    public function saveFile(TelegramUpdate $telegramUpdate, string $fileId, string $fileName): string
    {
        $token = config('services.telegram.bot_token');
        $filePath = $this->getFilePath($fileId, $token);
        if (!$filePath) {
            throw new FileDoesNotExist();
        }

        $fileUrl = "https://api.telegram.org/file/bot{$token}/{$filePath}";
        $fileContent = Http::get($fileUrl)->body();
        $filePath = storage_path('app/public/' . $fileName);
        file_put_contents($filePath, $fileContent);

        $telegramUpdate->addMedia($filePath)->toMediaCollection();
        DeleteFile::dispatch($telegramUpdate)->delay(now()->addHour());
        return $telegramUpdate->getFirstMediaUrl();
    }

    private function getFilePath($fileId, $token): ?string
    {
        $response = Http::get("https://api.telegram.org/bot{$token}/getFile", [
            'file_id' => $fileId,
        ]);
        $result = $response->json();
        if ($result['ok']) {
            return $result['result']['file_path'];
        }

        return null;
    }
}
