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
                            Text::make('Заголовок uz', 'banner_title_uz')
                                ->fieldContainer(false)
                                ->hideOnIndex(),
                        ]),

                        Tab::make('Заголовок ru', [
                            Text::make('Заголовок ru','banner_title_ru')
                                ->fieldContainer(false),
                        ]),

                        Tab::make('Заголовок en', [
                            Text::make('Заголовок en','banner_title_en')
                                ->fieldContainer(false)
                            ->hideOnIndex(),
                        ]),
                    ]),



                    Tabs::make([

                        Tab::make('Описание uz', [
                            TinyMce::make('Описание uz', 'banner_description_uz')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Описание ru', [
                            TinyMce::make('Описание ru', 'banner_description_ru')
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
                        Tab::make('Заголовок uz', [
                            Text::make('Заголовок uz','content_title_uz')
                                ->fieldContainer(false)
                                ->hideOnIndex(),
                        ]),

                        Tab::make('Заголовок ru', [
                            Text::make('Заголовок ru','content_title_ru')
                                ->fieldContainer(false),
                        ]),

                        Tab::make('Заголовок en', [
                            Text::make('Заголовок en','content_title_en')
                                ->fieldContainer(false)
                            ->hideOnIndex(),
                        ]),
                    ]),



                    Tabs::make([

                        Tab::make('Описание uz', [
                            TinyMce::make('Описание uz', 'content_description_uz')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Описание ru', [
                            TinyMce::make('Описание ru', 'content_description_ru')
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
                        Tab::make('Заголовок uz', [
                            Text::make('Заголовок uz','footer_title_uz')
                                ->fieldContainer(false)
                                ->hideOnIndex(),
                        ]),

                        Tab::make('Заголовок ru', [
                            Text::make('Заголовок ru','footer_title_ru')
                                ->fieldContainer(false),
                        ]),

                        Tab::make('Заголовок en', [
                            Text::make('Заголовок en','footer_title_en')
                                ->fieldContainer(false)
                            ->hideOnIndex(),
                        ]),
                    ]),

                    Tabs::make([

                        Tab::make('Описание uz', [
                            TinyMce::make('Описание uz', 'footer_description_uz')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Описание ru', [
                            TinyMce::make('Описание ru', 'footer_description_ru')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Описание en', [
                            TinyMce::make('Описание en', 'footer_description_en')
                                ->hideOnIndex()->required()
                        ]),


                    ]),
                    Image::make('Рисунок для фотора', 'footer_image')
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
