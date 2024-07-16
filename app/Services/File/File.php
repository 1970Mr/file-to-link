<?php

namespace App\Services\File;

use App\Jobs\DeleteFile;
use App\Models\TelegramUpdate;
use Illuminate\Support\Facades\Http;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

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

        $fileUrl = "https://api.telegram.org/file/bot{$token}/{$filePath}";
        $fileContent = Http::get($fileUrl)->body();
        $filePath = storage_path('app/public/' . $fileName);
        file_put_contents($filePath, $fileContent);

        $telegramUpdate->addMedia($filePath)->toMediaCollection();
        DeleteFile::dispatch($telegramUpdate)->delay(config('services.telegram.file_expire_time'));
        return $telegramUpdate->getFirstMediaUrl();
    }

    /**
     * @throws FileDoesNotExist
     */
    private function getFilePath($fileId, $token): string
    {
        $response = Http::get("https://api.telegram.org/bot{$token}/getFile", [
            'file_id' => $fileId,
        ]);
        $result = $response->json();

        if (!$result['ok']) {
            throw new FileDoesNotExist();
        }

        return $result['result']['file_path'];
    }
}
