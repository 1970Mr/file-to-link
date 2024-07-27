<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class SetTelegramWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:set-webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the Telegram bot webhook';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $token = config('services.telegram.bot_token');
        $appUrl = config('app.url');
        $webhookUrl = $appUrl . '/telegram/webhook';

        try {
            if (!$token) {
                throw new \InvalidArgumentException('Telegram bot token is not set in the configuration.');
            }

            if (!$appUrl) {
                throw new \InvalidArgumentException('App URL is not set in the configuration.');
            }

            $response = Telegram::setWebhook(['url' => $webhookUrl]);
            if (!$response) {
                throw new \RuntimeException('Webhook setting failed!');
            }

            $this->info('Webhook is set successfully.');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
