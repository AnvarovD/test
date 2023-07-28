<?php

namespace App\Service\Telegram;

use App\Service\Telegram\AbstractTelegram\Telegram;
use Illuminate\Support\Facades\Http;

class VacancyTelegramBot extends Telegram
{
    protected function getBot(): string
    {
       return 'bot6613484164:AAGOCwaIadwXCEDA2qM1KCI3t9UTdgygByU';
    }

    public function setWebHook(): array
    {
       return Http::get('https://api.telegram.org/'. $this->getBot() . '/setWebhook?url=https://da80-84-54-94-170.ngrok-free.app/webhook')->json();
    }
}
