<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\About;

use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
use MoonShine\Fields\BelongsTo;
use MoonShine\Fields\Image;
use MoonShine\Fields\Slug;
use MoonShine\Fields\Text;
use MoonShine\Fields\TinyMce;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;
use MoonShine\Resources\SingletonResource;

class AboutResource extends SingletonResource
{
    public static string $model = About::class;

    public static string $title = 'О нас';

    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            Column::make([
                Block::make('Banner', [
                    Tabs::make([
                        Tab::make('Заголовок ru', [
                            Text::make('Заголовок ru', 'banner_title_ru')
                                ->fieldContainer(false),
                        ]),

                        Tab::make('Заголовок uz', [
                            Text::make('Заголовок uz', 'banner_title_uz')
                                ->fieldContainer(false)
                                ->hideOnIndex(),
                        ]),



                        Tab::make('Заголовок en', [
                            Text::make('Заголовок en', 'banner_title_en')
                                ->fieldContainer(false)
                                ->hideOnIndex(),
                        ]),
                    ]),


                    Tabs::make([

                        Tab::make('Описание ru', [
                            TinyMce::make('Описание ru', 'banner_description_ru')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Описание uz', [
                            TinyMce::make('Описание uz', 'banner_description_uz')
                                ->hideOnIndex()->required()
                        ]),



                        Tab::make('Описание en', [
                            TinyMce::make('Описание en', 'banner_description_en')
                                ->hideOnIndex()->required()
                        ]),


                    ]),
                ]),

                Block::make('Content', [
                    Tabs::make([
                        Tab::make('Заголовок ru', [
                            Text::make('Заголовок ru', 'content_title_ru')
                                ->fieldContainer(false),
                        ]),

                        Tab::make('Заголовок uz', [
                            Text::make('Заголовок uz', 'content_title_uz')
                                ->fieldContainer(false)
                                ->hideOnIndex(),
                        ]),



                        Tab::make('Заголовок en', [
                            Text::make('Заголовок en', 'content_title_en')
                                ->fieldContainer(false)
                                ->hideOnIndex(),
                        ]),
                    ]),


                    Tabs::make([

                        Tab::make('Описание ru', [
                            TinyMce::make('Описание ru', 'content_description_ru')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Описание uz', [
                            TinyMce::make('Описание uz', 'content_description_uz')
                                ->hideOnIndex()->required()
                        ]),



                        Tab::make('Описание en', [
                            TinyMce::make('Описание en', 'content_description_en')
                                ->hideOnIndex()->required()
                        ]),


                    ]),
                ]),


                Block::make('Footer', [
                    Tabs::make([
                        Tab::make('Заголовок ru', [
                            Text::make('Заголовок ru', 'footer_title_ru')
                                ->fieldContainer(false),
                        ]),

                        Tab::make('Заголовок uz', [
                            Text::make('Заголовок uz', 'footer_title_uz')
                                ->fieldContainer(false)
                                ->hideOnIndex(),
                        ]),



                        Tab::make('Заголовок en', [
                            Text::make('Заголовок en', 'footer_title_en')
                                ->fieldContainer(false)
                                ->hideOnIndex(),
                        ]),
                    ]),

                    Tabs::make([
                        Tab::make('Описание ru', [
                            TinyMce::make('Описание ru', 'footer_description_ru')
                                ->hideOnIndex()->required()
                        ]),


                        Tab::make('Описание uz', [
                            TinyMce::make('Описание uz', 'footer_description_uz')
                                ->hideOnIndex()->required()
                        ]),



                        Tab::make('Описание en', [
                            TinyMce::make('Описание en', 'footer_description_en')
                                ->hideOnIndex()->required()
                        ]),


                    ]),
                    Image::make('Рисунок для фотора', 'footer_image')
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

//            Slug::make('slug')
//                ->from('title_en')
//                ->unique()
//                ->separator('-')
//                ->hideOnIndex()
//                ->hideOnDetail()
//                ->hideOnCreate()
//                ->hideOnUpdate(),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'banner_title_uz' => ['required', 'string'],
            'banner_title_ru' => ['required', 'string'],
            'banner_title_en' => ['required', 'string'],
            'banner_description_uz' => ['required', 'string'],
            'banner_description_ru' => ['required', 'string'],
            'banner_description_en' => ['required', 'string'],
            'content_title_uz' => ['required', 'string'],
            'content_title_ru' => ['required', 'string'],
            'content_title_en' => ['required', 'string'],
            'content_description_uz' => ['required', 'string'],
            'content_description_ru' => ['required', 'string'],
            'content_description_en' => ['required', 'string'],
            'footer_title_uz' => ['required', 'string'],
            'footer_title_ru' => ['required', 'string'],
            'footer_title_en' => ['required', 'string'],
            'footer_description_uz' => ['required', 'string'],
            'footer_description_ru' => ['required', 'string'],
            'footer_description_en' => ['required', 'string'],
            'footer_image' => ['nullable', 'image'],
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
