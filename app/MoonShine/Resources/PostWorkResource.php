<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\PostWork;

use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
use MoonShine\Fields\BelongsTo;
use MoonShine\Fields\Checkbox;
use MoonShine\Fields\Image;
use MoonShine\Fields\Text;
use MoonShine\Fields\TinyMce;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class PostWorkResource extends Resource
{
    public static string $model = PostWork::class;

    public static string $title = 'Посты для проектов';

    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            Column::make([
                Block::make('Создание поста', [

                    Tabs::make([
                        Tab::make('Заголовок ru', [
                            Text::make('Заголовок ru', 'title_ru')
                                ->fieldContainer(false),
                        ]),

                        Tab::make('Заголовок en', [
                            Text::make('Заголовок en', 'title_en')
                                ->fieldContainer(false)
                                ->hideOnIndex(),
                        ]),

                        Tab::make('Заголовок uz', [
                            Text::make('Заголовок uz', 'title_uz')
                                ->fieldContainer(false)
                                ->hideOnIndex(),
                        ]),
                    ]),

                    Tabs::make([
                        Tab::make('Описания uz', [
                            TinyMce::make('Описания uz', 'description_uz')
                                ->fieldContainer(false)
                                ->hideOnIndex()
                                ->required(),
                        ]),

                        Tab::make('Описания en', [
                            TinyMce::make('Описания en', 'description_en')
                                ->fieldContainer(false)
                                ->hideOnIndex()->required(),
                        ]),

                        Tab::make('Описания ru', [
                            TinyMce::make('Описания ru', 'description_ru')
                                ->fieldContainer(false)
                                ->required(),
                        ]),
                    ]),


                    BelongsTo::make(
                        'Проект',
                        'work_id',
                        'title_ru'
                    ),

                    Image::make('Файл', 'image'),
                ]),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'title_ru' => ['required', 'title_ru'],
            'title_en' => ['required', 'title_en'],
            'title_uz' => ['required', 'title_uz'],
            'description_uz' => ['required', 'description_uz'],
            'description_en' => ['required', 'description_en'],
            'description_ru' => ['required', 'description_ru'],
            'image' => ['required', 'image'],
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
