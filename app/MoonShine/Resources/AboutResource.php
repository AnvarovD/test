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

class AboutResource extends Resource
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
                        Tab::make('Заголовок uz', [
                            Text::make('banner_title_uz')
                                ->fieldContainer(false)
                                ->hideOnIndex(),
                        ]),

                        Tab::make('Заголовок ru', [
                            Text::make('banner_title_ru')
                                ->fieldContainer(false)
                                ->hideOnIndex(),
                        ]),

                        Tab::make('Заголовок en', [
                            Text::make('banner_title_en')
                                ->fieldContainer(false),
                        ]),
                    ]),



                    Tabs::make([

                        Tab::make('Описание uz', [
                            TinyMce::make("", 'banner_description_uz')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Описание eu', [
                            TinyMce::make("", 'banner_description_ru')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Описание en', [
                            TinyMce::make("", 'banner_description_en')
                                ->hideOnIndex()->required()
                        ]),


                    ]),
                ]),

                Block::make('Content', [
                    Tabs::make([
                        Tab::make('Заголовок uz', [
                            Text::make('content_title_uz')
                                ->fieldContainer(false)
                                ->hideOnIndex(),
                        ]),

                        Tab::make('Заголовок ru', [
                            Text::make('content_title_ru')
                                ->fieldContainer(false)
                                ->hideOnIndex(),
                        ]),

                        Tab::make('Заголовок en', [
                            Text::make('content_title_en')
                                ->fieldContainer(false),
                        ]),
                    ]),



                    Tabs::make([

                        Tab::make('Описание uz', [
                            TinyMce::make("", 'content_description_uz')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Описание eu', [
                            TinyMce::make("", 'content_description_ru')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Описание en', [
                            TinyMce::make("", 'content_description_en')
                                ->hideOnIndex()->required()
                        ]),


                    ]),
                ]),


                Block::make('Footer', [
                    Tabs::make([
                        Tab::make('Заголовок uz', [
                            Text::make('footer_title_uz')
                                ->fieldContainer(false)
                                ->hideOnIndex(),
                        ]),

                        Tab::make('Заголовок ru', [
                            Text::make('footer_title_ru')
                                ->fieldContainer(false)
                                ->hideOnIndex(),
                        ]),

                        Tab::make('Заголовок en', [
                            Text::make('footer_title_en')
                                ->fieldContainer(false),
                        ]),
                    ]),

                    Tabs::make([

                        Tab::make('Описание uz', [
                            TinyMce::make("", 'footer_description_uz')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Описание eu', [
                            TinyMce::make("", 'footer_description_ru')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Описание en', [
                            TinyMce::make("", 'footer_description_en')
                                ->hideOnIndex()->required()
                        ]),


                    ]),
                    Image::make('Banner image', 'footer_image')
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
