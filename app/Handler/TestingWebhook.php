<?php

namespace App\Handler;

use Spatie\WebhookClient\ProcessWebhookJob;

class TestingWebhook extends ProcessWebhookJob
{
    public function handle()
    {
        logger($this->webhookCall);
    }
}
