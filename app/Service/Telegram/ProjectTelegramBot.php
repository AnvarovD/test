<?php

namespace App\Service\Telegram;

use App\Service\Telegram\AbstractTelegram\Telegram;

class ProjectTelegramBot extends Telegram
{
    protected function getBot(): string
    {
        return 'bot6440715640:AAFaEK5x7H2EnQnlib9gX4pjRHTxgoHU3gE';
    }

    public function setWebHook(): array
    {
       return [];
    }
}
