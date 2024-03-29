<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Contact;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
use MoonShine\Fields\Text;
use MoonShine\Fields\TinyMce;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;
use MoonShine\Resources\SingletonResource;

class ContactResource extends SingletonResource
{
    public static string $model = Contact::class;

    public static string $title = 'Контакты';

    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('адрес ru', 'address_ru'),
            Text::make('адрес uz', 'address_uz'),
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
                                ->hideOnIndex()
                        ]),

                        Tab::make('Mata Описание ru', [
                            TinyMce::make('Mata Описание ru', 'meta_description_ru')
                                ->hideOnIndex()
                        ]),

                        Tab::make('Mata Описание en', [
                            TinyMce::make('Mata Описание en', 'meta_description_en')
                                ->hideOnIndex()
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
            'meta_title_uz' => ['nullable', 'string'],
            'meta_title_ru' => ['nullable', 'string'],
            'meta_title_en' => ['nullable', 'string'],
            'meta_description_uz' => ['nullable', 'string'],
            'meta_description_ru' => ['nullable', 'string'],
            'meta_description_en' => ['nullable', 'string'],
        ];
    }

    public function search(): array
    {
        return [
//            'id'
        ];
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

    public function getId(): int|string
    {
        return 1;
    }
}
