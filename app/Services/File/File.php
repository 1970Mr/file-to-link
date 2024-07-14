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
    public function saveFile(TelegramUpdate $telegramUpdate, string $fileUrl, string $fileName): string
    {
        $fileContent = Http::get($fileUrl)->body();
        $filePath = storage_path('app/public/' . $fileName);
        file_put_contents($filePath, $fileContent);

        $telegramUpdate->addMedia($filePath)->toMediaCollection();

        DeleteFile::dispatch($telegramUpdate)->delay(now()->addHour());

        return $telegramUpdate->getFirstMediaUrl();
    }
}
