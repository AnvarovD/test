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

	public static string $title = 'PostWorks';

	public function fields(): array
	{
		return [
		    ID::make()->sortable(),
            Column::make([
                Block::make('Создание Статьи', [

                    Tabs::make([
                        Tab::make('Описания ru', [
                            TinyMce::make('description_uz')
                                ->fieldContainer(false)->required(),
                        ]),

                        Tab::make('Описания en', [
                            TinyMce::make('description_en')
                                ->fieldContainer(false)
                                ->hideOnIndex()->required(),
                        ]),

                        Tab::make('Описания uz', [
                            TinyMce::make('description_ru')
                                ->fieldContainer(false)
                                ->hideOnIndex()->required(),
                        ]),
                    ]),


                    BelongsTo::make(
                        'Work',
                        'work_id',
                        'title_ru'
                    ),

                    Image::make('Файл','image'),
                ]),
            ]),
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
