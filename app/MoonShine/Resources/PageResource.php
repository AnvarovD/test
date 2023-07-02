<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Page;

use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
use MoonShine\Fields\BelongsTo;
use MoonShine\Fields\BelongsToMany;
use MoonShine\Fields\Slug;
use MoonShine\Fields\Text;
use MoonShine\Fields\TinyMce;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class PageResource extends Resource
{
    public static string $model = Page::class;

    public static string $title = 'Категории';


    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            Column::make([
                Block::make('Создание Страницы', [
                    Tabs::make([
                        Tab::make('Заголовок ru', [
                            Text::make('Заголовок ru', 'title_ru')
                                ->fieldContainer(false),
                        ]),
                        Tab::make('Заголовок en', [
                            Text::make('Заголовок en','title_en')
                                ->fieldContainer(false)
                                ->hideOnIndex(),
                        ]),

                        Tab::make('Заголовок uz', [
                            Text::make('Заголовок uz','title_uz')
                                ->fieldContainer(false)
                                ->hideOnIndex(),
                        ]),
                    ]),
                ]),
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

//                BelongsTo::make('Родительская Страница', 'parent_id', 'title_ru')
//                    ->nullable()
            ]),

            Slug::make('slug')
                ->from('title_en')
                ->unique()
                ->separator('-')
                ->hideOnIndex()
                ->hideOnCreate()
                ->hideOnDetail()
            ->hideOnUpdate(),

        ];
    }

    public function rules(Model $item): array
    {
        return [
            'title_ru' => 'required|string',
            'title_en' => 'required|string',
            'title_uz' => 'required|string',
            'meta_title_uz' => ['required', 'string'],
            'meta_title_ru' => ['required', 'string'],
            'meta_title_en' => ['required', 'string'],
            'meta_description_uz' => ['required', 'string'],
            'meta_description_ru' => ['required', 'string'],
            'meta_description_en' => ['required', 'string'],
        ];
    }

    public function validationMessages(): array
    {
        return [
            'title_ru.required' => 'Заголовок ru обязательный',
            'title_uz.required' => 'Заголовок uz обязательный',
            'title_en.required' => 'Заголовок en обязательный'
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
}
