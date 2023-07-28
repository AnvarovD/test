<?php

namespace App\Service\TelegramWebHookService;

use App\Jobs\AddCommentToContact;
use App\Jobs\CreateDealForCourse;
use App\Jobs\UpdateDealForCourse;
use App\Models\Course;
use App\Models\TelegramUser;
use App\Service\Telegram\VacancyTelegramBot;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

class TelegramWebHookService
{
    public function __construct(
        private readonly VacancyTelegramBot $telegramBot
    )
    {
    }

    private array $buttons = [
        'inline_keyboard' => [
            [
                [
                    'text' => 'Курсга ёзилиш',
                    'callback_data' => 'subscribeToCourse'
                ],
                [
                    'text' => 'Курслар хакида батафсил маълумот',
                    'callback_data' => 'infoAboutCourse'
                ]
            ]
        ]
    ];

    /**
     * @throws RequestException
     */
    public function startBot(string $text, int $chat_id, array $message = []): void
    {
        if ($text == '/start') {
            $user = TelegramUser::query()->where('chat_id', $chat_id)->first();
            if ($user == null) {
                $user = new TelegramUser();
                $user->chat_id = $chat_id;
                $user->telegram_username = $message['username'];
                $user->telegram_first_name = $message['first_name'];
                $user->save();
                $this->enterPhone($chat_id);
            }
            if ($user && $user->phone != null){
                $this->startBuy($chat_id);
            }
        }
    }


    /**
     * @throws RequestException
     */
    public function startBuy(int $chat_id): void
    {
        $data = [
            'chat_id' => $chat_id,
            'text' => '
Ассалому алайкум!
“Booket qizlar academiyasi” онлайн курслар платформасига хуш келибсиз!
Бахтли хаётга кадам куйиш учун барча кизлар билиши керак булган билимларни жамлаган курсларда укиб,бахтли аёл
булишни хохлайсизми?
Унда сиз тугри йул танладингиз.
Бахт бу тасодиф ёки омад эмас,бахт калити чексиз ва мунтазам илмда!
Илмингиз учун сарфлаган маблагингиз сизга бу дунёда бахт ва саодат,охиратда эса савобга элтишини унутманг!
Курсга обуна бўлиш учун патсдаги менюдан фойдаланинг:
              ',
            'reply_markup' => json_encode([
                "one_time_keyboard" => true,
                "keyboard" => [
                    [
                        "text" => 'My keyboard',
                        "request_location" => true
                    ]
                ]
            ])
        ];
       $data = $this->telegramBot->sendButtons($this->getButton());
       Log::debug("error_telegram", $data);
    }

    /**
     * @throws RequestException
     */
    public function enterName(int $chat_id): void
    {
        $data = [
            'chat_id' => $chat_id,
            'text' => 'Исмингизни критинг',
        ];

        $this->telegramBot->sendMessage($data);
    }


    /**
     * @throws RequestException
     */
    public function enterPhone(int $chat_id): void
    {
        $data = [
            'chat_id' => $chat_id,
            'text' => 'Телефон рак,амингизни критинг:
Намуна: 90 1234567',
        ];

        $this->telegramBot->sendMessage($data);
    }


    public function enterValidPhone(int $chat_id): void
    {
        $data = [
            'chat_id' => $chat_id,
            'text' => 'Телефон рак,амингизни тогри критинг:
Намуна: 90 1234567',
        ];

        $this->telegramBot->sendMessage($data);
    }

    /**
     * @throws RequestException
     */
    public function home(int $chat_id): void
    {
        $data = [
            'chat_id' => $chat_id,
            'text' => '
Ассалому алайкум!
“Booket qizlar academiyasi” онлайн курслар платформасига хуш келибсиз!
Бахтли хаётга кадам куйиш учун барча кизлар билиши керак булган билимларни жамлаган курсларда укиб,бахтли аёл
булишни хохлайсизми?
Унда сиз тугри йул танладингиз.
Бахт бу тасодиф ёки омад эмас,бахт калити чексиз ва мунтазам илмда!
Илмингиз учун сарфлаган маблагингиз сизга бу дунёда бахт ва саодат,охиратда эса савобга элтишини унутманг!
Курсга обуна бўлиш учун патсдаги менюдан фойдаланинг:
              ',
            'reply_markup' => json_encode($this->buttons)
        ];
        $this->telegramBot->sendButtons($data);
    }


    /**
     * @throws RequestException
     */
    public function sendCourses($chat_id)
    {
        $data = [
            'chat_id' => $chat_id,
            'text' => 'Курслар',
            'reply_markup' => $this->getCoursesArray()
        ];
        $this->telegramBot->sendButtons($data);
    }


    /**
     * @throws RequestException
     */
    public function infoAboutCourse(int $chat_id): void
    {
        $buttons = [
            'inline_keyboard' => [
                [
                    [
                        'text' => 'Асосий меню',
                        'callback_data' => 'home'
                    ],
                ]
            ]
        ];

        $data = [
            'chat_id' => $chat_id,
            'text' => 'Ассалому алайкум!
“Booket qizlar academiyasi” онлайн курслар платформасига хуш келибсиз!
Бахтли хаётга кадам куйиш учун барча кизлар билиши керак булган билимларни
жамлаган курсларда укиб,бахтли аёл булишни хохлайсизми?
Унда сиз тугри йул танладингиз.
Бахт бу тасодиф ёки омад эмас,бахт калити чексиз ва мунтазам илмда!
Илмингиз учун сарфлаган маблагингиз сизга бу дунёда бахт ва саодат,охиратда эса
савобга элтишини унутманг!
Курсга обуна бўлиш учун патсдаги менюдан фойдаланинг:',
            'reply_markup' => json_encode($buttons)
        ];
        $this->telegramBot->sendButtons($data);
    }


    /**
     * @throws RequestException
     */
    public function getCourse(string $course_id, int $chat_id): void
    {

        $course = Course::query()->find((int)$course_id);

        if ($course == null) {
            $text = 'Topilmadi';
        } else {
            $text = $course->description;
            AddCommentToContact::dispatch($course, $chat_id);
            CreateDealForCourse::dispatch($course, $chat_id);
        }


        $buttons = [
            'inline_keyboard' => [
                [
                    [
                        'text' => 'Асосий меню',
                        'callback_data' => 'home'
                    ],
                    [
                        'text' => 'ТЎлов қилиш',
                        'callback_data' => 'makePay|'.$course->id
                    ],
                ]
            ]
        ];

        $data = [
            'chat_id' => $chat_id,
            'text' => $text,
            'reply_markup' => json_encode($buttons)
        ];
        Log::debug('response', $data);
        $this->telegramBot->sendButtons($data);
    }


    /**
     * @param $chat_id
     * @param string $course_id
     * @throws RequestException
     */
    public function makePay($chat_id, string $course_id): void
    {
        $course = Course::query()->find((int)$course_id);
        $buttons = [
            'inline_keyboard' => [
                [
                    [
                        'text' => 'Асосий меню',
                        'callback_data' => 'home'
                    ],
                    [
                        'text' => 'Толов чекини юбориш',
                        'callback_data' => "sendCheck|$course_id"
                    ]
                ]
            ]
        ];
        $data = [
            'chat_id' => $chat_id,
          'text' => 'Тўлов қилиш учун бизнинг 8600 хххх хххх хххх картамизга 1 000 000 утказинг вачекни юборинг.
Биз сизни каналга автоматик обуна қиламиз'  ,
            'reply_markup' => json_encode($buttons)
        ];
        UpdateDealForCourse::dispatch($course, $chat_id);
        $this->telegramBot->sendButtons($data);
    }

    /**
     * @throws RequestException
     */
    public function sendCheck(int $chat_id)
    {
        $buttons = [
            'inline_keyboard' => [
                [
                    [
                        'text' => 'Асосий меню',
                        'callback_data' => 'home'
                    ]
                ]
            ]
        ];
        $data = [
            'chat_id' => $chat_id,
            'text' => 'Чекни юборинг'  ,
            'reply_markup' => json_encode($buttons)
        ];
        $this->telegramBot->sendButtons($data);
    }
    public function getCoursesArray(): string
    {

        $courses = Course::query();
        $arrays = [];
        $courses->chunk(2, function ($course) use (&$arrays) {
            $arrays[] = $course->map(function ($course) {
                return ['text' => $course->title, 'callback_data' => 'course|'.$course->id];
            })->toArray();
        });

      return  json_encode(['inline_keyboard' => $arrays]);
    }

    public function getButton(): array
    {
        return array(
            "keyboard" => array(array(array(
                "text" => "/button"
            ),
                array(
                    "text" => "contact",
                    "request_contact" => true // Данный запрос необязательный telegram button для запроса номера телефона
                ),
                array(
                    "text" => "location",
                    "request_location" => true // Данный запрос необязательный telegram button для запроса локации пользователя
                )

            )),
            "text" => "tet",
            "one_time_keyboard" => true, // можно заменить на FALSE,клавиатура скроется после нажатия кнопки автоматически при True
            "resize_keyboard" => true // можно заменить на FALSE, клавиатура будет использовать компактный размер автоматически при True
        );
    }
}
