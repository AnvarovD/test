<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Work;

use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
use MoonShine\Fields\Checkbox;
use MoonShine\Fields\Image;
use MoonShine\Fields\Slug;
use MoonShine\Fields\Text;
use MoonShine\Fields\TinyMce;
use MoonShine\Fields\Url;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class WorkResource extends Resource
{
    public static string $model = Work::class;

    public static string $title = 'Проекты';

    public function fields(): array
    {
        $edit = false;
        if (request()->route()->uri == "admin/resource/work-resource/{resourceItem}/edit"){
            $edit = true;
        }

        $item = $this->getItem();
//        dd(request()->route()->getName());
        return [
            ID::make()->sortable(),

            Column::make([
                Block::make('Создание Проекта', [

                    Tabs::make([
                        Tab::make('Заголовок ru', [
                            Text::make('Заголовок ru', 'title_ru')
                                ->fieldContainer(false)->required(),
                        ]),

                        Tab::make('Заголовок uz', [
                            Text::make('Заголовок uz', 'title_uz')
                                ->fieldContainer(false)
                                ->hideOnIndex()->required(),
                        ]),

                        Tab::make('Заголовок en', [
                            Text::make('Заголовок en', 'title_en')
                                ->fieldContainer(false)
                                ->hideOnIndex()->required(),
                        ]),


                    ]),

                    Tabs::make([
                        Tab::make('Под описание ru', [
                            Text::make('Под описание ru', 'sub_title_ru')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Под описание uz', [
                            Text::make('Под описание uz', 'sub_title_uz')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Под описание en', [
                            Text::make('Под описание en', 'sub_title_en')
                                ->hideOnIndex()->required()
                        ]),


                    ]),

                    Checkbox::make('Показать на главной странице', 'is_main'),

                    Image::make('Большой рисунок', 'macro_image')
                        ->required(function () use ($item) {
                            if (request()->route()->uri == "admin/resource/work-resource/create"){
                                return true;
                            }else {
                                return false;
                            }
                        })->hideOnIndex(),
                    Image::make('Средний рисунок', 'medium_image')
                        ->required(function () use ($item) {
                            if (request()->route()->uri == "admin/resource/work-resource/create"){
                                return true;
                            }else {
                                return false;
                            }
                        })->hideOnIndex(),
                    Image::make('Маленкий рисунок', 'micro_image')
                        ->required(function () use ($item) {
                            if (request()->route()->uri == "admin/resource/work-resource/create"){
                                return true;
                            }else {
                                return false;
                            }
                        })->hideOnIndex(),
                ]),
            ]),

            Column::make([
                Block::make('Описания проекта', [

                    Tabs::make([
                        Tab::make('Заголовок ru', [
                            Text::make('Заголовок ru', 'work_title_ru')
                                ->fieldContainer(false)
                                ->required()->hideOnIndex(),
                        ]),

                        Tab::make('Заголовок uz', [
                            Text::make('Заголовок uz', 'work_title_uz')
                                ->fieldContainer(false)
                                ->hideOnIndex()->required(),
                        ]),

                        Tab::make('Заголовок en', [
                            Text::make('Заголовок en', 'work_title_en')
                                ->fieldContainer(false)
                                ->hideOnIndex()->required(),
                        ]),


                    ]),

                    Tabs::make([
                        Tab::make('Под описание ru', [
                            Text::make('Под описание ru', 'work_sub_title_ru')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Под описание uz', [
                            Text::make('Под описание uz', 'work_sub_title_uz')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Под описание en', [
                            Text::make('Под описание en', 'work_sub_title_en')
                                ->hideOnIndex()->required()
                        ]),


                    ]),

//                    Tabs::make([
//                        Tab::make('рисунок', [
//                            Image::make('Загрузить рисунок', 'file'),
//                        ]),
//
//                        Tab::make('видео', [
//                            Url::make('Линк на видео', 'video_link')
//                                ->showWhen('video_link', '!=', null),
//                        ]),
//
//                    ]),
                ]),
            ]),

            Column::make([
                Block::make('Медиа файли',[

                Tabs::make([
                    Tab::make('рисунок', [
                        Image::make('Загрузить рисунок', 'file')->removable(),

                    ]),

                    Tab::make('видео', [
                        Url::make('Линк на видео', 'video_link'),
                    ]),
                ])
            ])
            ]),

            Column::make([
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

            Slug::make('slug')
                ->from('title_en')
                ->unique()
                ->separator('-')
                ->hideOnIndex()
                ->hideOnDetail()
                ->hideOnCreate()
                ->hideOnUpdate(),
        ];
    }

    public function rules(Model $item): array
    {
        $id = null;
        if (request()->id){
            $id = request()->id;
        }
        return [
            'title_ru' => ['required'],
            'title_en' => ['required'],
            'title_uz' => ['required'],
            'sub_title_uz' => ['required'],
            'sub_title_en' => ['required'],
            'sub_title_ru' => ['required'],
            'macro_image' => ['nullable', 'image'],
            'medium_image' => ['nullable', 'image'],
            'micro_image' => ['nullable', 'image'],
            'work_title_ru' => ['required'],
            'work_title_en' => ['required'],
            'work_title_uz' => ['required'],
            'work_sub_title_uz' => ['required'],
            'work_sub_title_en' => ['required'],
            'work_sub_title_ru' => ['required'],
            'file' => ['image',
                Rule::excludeIf(function () {
                    if (request()->video_link && request()->file) {
                        throw ValidationException::withMessages([
                            'file' => 'В Медиа файлах вы должны выбрать либо рисунок либо линк на видео.',
                        ]);
                    }
                }), Rule::requiredIf(!request()->video_link && request()->route()->getName() == "moonshine.works.store"), Rule::requiredIf(function () use($id) {
                    if ($id){
                        $work = Work::query()->find((int)$id);
                        if (request()->file && $work->video_link){
                            throw ValidationException::withMessages([
                                'file' => 'В Медиа файлах вы должны выбрать либо рисунок либо линк на видео.',
                            ]);
                        }
                    }
                }),
            ],
            'video_link' => ['nullable','string',  Rule::excludeIf(function () {
                if (request()->video_link && request()->file) {
                    throw ValidationException::withMessages([
                        'video_link' => 'В Медиа файлах вы должны выбрать либо рисунок либо линк на видео.',
                    ]);
                }

            }), Rule::requiredIf(!request()->file && request()->route()->getName() == "moonshine.works.store"), Rule::requiredIf(function () use($id) {
                if ($id){
                    $work = Work::query()->find((int)$id);
                    if (request()->video_link && $work->file){
                        throw ValidationException::withMessages([
                            'file' => 'В Медиа файлах вы должны выбрать либо рисунок либо линк на видео.',
                        ]);
                    }
                }
            }),
            ],
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
