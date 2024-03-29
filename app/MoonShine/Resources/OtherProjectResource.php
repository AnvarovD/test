<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\OtherProject;

use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
use MoonShine\Fields\BelongsTo;
use MoonShine\Fields\BelongsToMany;
use MoonShine\Fields\Image;
use MoonShine\Fields\Slug;
use MoonShine\Fields\Text;
use MoonShine\Fields\TinyMce;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class OtherProjectResource extends Resource
{
	public static string $model = OtherProject::class;

	public static string $title = 'Другие проекты';

	public function fields(): array
	{
        return [
            ID::make()->sortable(),
            Column::make([
                Block::make('Создание другого проекта', [

                    Tabs::make([
                        Tab::make('Заголовок ru', [
                            Text::make('title_ru')
                                ->fieldContainer(false)->required(),
                        ]),

                        Tab::make('Заголовок en', [
                            Text::make('title_en')
                                ->fieldContainer(false)
                                ->hideOnIndex()->required(),
                        ]),

                        Tab::make('Заголовок uz', [
                            Text::make('title_uz')
                                ->fieldContainer(false)
                                ->hideOnIndex()->required(),
                        ]),
                    ]),

                    Tabs::make([

                        Tab::make('Описание ru', [
                            TinyMce::make("", 'description_ru')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Описание en', [
                            TinyMce::make("", 'description_en')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Описание uz', [
                            TinyMce::make("", 'description_uz')
                                ->hideOnIndex()->required()
                        ]),

                    ]),
                ]),
                Image::make("Рисунки", "images")
                    ->dir("images")
                    ->multiple()
                    ->removable(),

                BelongsToMany::make('Страницы', 'pages', 'title_ru')
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
        return [];
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
