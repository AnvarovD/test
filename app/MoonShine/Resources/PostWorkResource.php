<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\PostWork;

use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
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
//        dd(request()->route()->getName());
        return [
            ID::make()->sortable(),
            Column::make([
                Block::make('Создание поста', [

                    Tabs::make([
                        Tab::make('Заголовок ru', [
                            Text::make('Заголовок ru', 'title_ru')
                                ->fieldContainer(false),
                        ]),

                        Tab::make('Заголовок uz', [
                            Text::make('Заголовок uz', 'title_uz')
                                ->fieldContainer(false)
                                ->hideOnIndex(),
                        ]),

                        Tab::make('Заголовок en', [
                            Text::make('Заголовок en', 'title_en')
                                ->fieldContainer(false)
                                ->hideOnIndex(),
                        ]),


                    ]),

                    Tabs::make([
                        Tab::make('Описания ru', [
                            TinyMce::make('Описания ru', 'description_ru')
                                ->fieldContainer(false)
                                ->required(),
                        ]),

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

                    ]),


                    BelongsTo::make(
                        'Проект',
                        'work_id',
                        'title_ru'
                    ),

                    Image::make('Файл', 'image'),
                ]),
                Block::make('Meta', [
                    Tabs::make([
                        Tab::make('Mata Заголовок ru', [
                            Text::make('Mata Заголовок ru', 'meta_title_ru')
                                ->fieldContainer(false),
                        ]),

                        Tab::make('Mata Заголовок uz', [
                            Text::make('Mata Заголовок uz', 'meta_title_uz')
                                ->fieldContainer(false)
                                ->hideOnIndex(),
                        ]),



                        Tab::make('Mata Заголовок en', [
                            Text::make('Mata Заголовок en', 'meta_title_en')
                                ->fieldContainer(false)
                                ->hideOnIndex(),
                        ]),
                    ]),

                    Tabs::make([
                        Tab::make('Mata Описание ru', [
                            TinyMce::make('Mata Описание ru', 'meta_description_ru')
                                ->hideOnIndex()
                        ]),

                        Tab::make('Mata Описание uz', [
                            TinyMce::make('Mata Описание uz', 'meta_description_uz')
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
            'title_ru' => ['nullable', 'string'],
            'title_en' => ['nullable', 'string'],
            'title_uz' => ['nullable', 'string'],
            'description_uz' => ['required', 'string'],
            'description_en' => ['required', 'string'],
            'description_ru' => ['required', 'string'],
            'image' => ["nullable", "image",Rule::requiredIf(function (){
                if (
                    request()->route()->getName() === "moonshine.postWorks.store"
                    && !request()->image
                ){
                    throw  ValidationException::withMessages([
                        "Рисунок обязательный для заполнения"
                    ]);
                }
            })],
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
}
