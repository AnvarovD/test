<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Contact;
use Illuminate\Support\Facades\Route;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
use MoonShine\Fields\Text;
use MoonShine\Fields\TinyMce;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;
use MoonShine\Resources\SingletonResource;

class ContactResource extends SingletonResource
{
    public static string $model = Contact::class;

    public static string $title = 'Контакты';

    protected string $routeAfterSave = 'show'; // index, show, edit

    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('адрес uz', 'address_uz'),
            Text::make('адрес ru', 'address_ru'),
            Text::make('адрес en', 'address_en'),
            Text::make('Линк для локации', 'location')->hideOnIndex(),
            Text::make('Email', 'email'),
            Text::make('Номер телефона', 'phone'),
            Column::make([
                Block::make('Meta', [
                    Tabs::make([
                        Tab::make('Mata Заголовок uz', [
                            Text::make('Mata Заголовок uz', 'meta_title_uz')
                                ->fieldContainer(false)
                                ->hideOnIndex(),
                        ]),

                        Tab::make('Mata Заголовок ru', [
                            Text::make('Mata Заголовок ru', 'meta_title_ru')
                                ->fieldContainer(false),
                        ]),

                        Tab::make('Mata Заголовок en', [
                            Text::make('Mata Заголовок en', 'meta_title_en')
                                ->fieldContainer(false)
                                ->hideOnIndex(),
                        ]),
                    ]),

                    Tabs::make([

                        Tab::make('Mata Описание uz', [
                            TinyMce::make('Mata Описание uz', 'meta_description_uz')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Mata Описание ru', [
                            TinyMce::make('Mata Описание ru', 'meta_description_ru')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Mata Описание en', [
                            TinyMce::make('Mata Описание en', 'meta_description_en')
                                ->hideOnIndex()->required()
                        ]),
                    ]),
                ]),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'address_uz' => ['required', 'string'],
            'address_ru' => ['required', 'string'],
            'address_en' => ['required', 'string'],
            'location' => ['required', 'string'],
            'email' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'meta_title_uz' => ['required', 'string'],
            'meta_title_ru' => ['required', 'string'],
            'meta_title_en' => ['required', 'string'],
            'meta_description_uz' => ['required', 'string'],
            'meta_description_ru' => ['required', 'string'],
            'meta_description_en' => ['required', 'string'],
        ];
    }

    public function search(): array
    {
        return ['id'];
    }

    public function filters(): array
    {
        return [];
    }

    public function actions(): array
    {
        return [
            FiltersAction::make(trans('moonshine::ui.filters')),
        ];
    }

//    public function resolveRoutes(): void
//    {
//        parent::resolveRoutes();
//       $this->route('show', Contact::query()->first()->id);
//    }
//        parent::resolveRoutes();
//
//        Route::prefix('resource')->group(function (): void {
//            Route::get("{$this->uriKey()}/index", function (Contact $item) {
//                dd($this->uriKey());
//
//                dd($item);
//                $item->restore();
//
//                return redirect()->back();
//            });
//        });
//    }
    public function getId(): int|string
    {
        return 1;
    }
}
