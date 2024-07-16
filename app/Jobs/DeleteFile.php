<?php

namespace App\Jobs;

use App\Models\TelegramUpdate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly TelegramUpdate $telegramUpdate)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->telegramUpdate->getFirstMedia()?->delete();
    }
}
