<?php

namespace App\MoonShine\Resources;

use App\Models\Work;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\WorkContent;

use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
use MoonShine\Fields\BelongsTo;
use MoonShine\Fields\Checkbox;
use MoonShine\Fields\Image;
use MoonShine\Fields\Text;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class WorkContentResource extends Resource
{
	public static string $model = WorkContent::class;

	public static string $title = 'WorkContents';

    /**
     * @throws \Throwable
     */
    public function fields(): array
	{
		return [
		    ID::make()->sortable(),
            Column::make([
                Block::make('Создание Статьи', [

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

                        Tab::make('Под описание uz', [
                            Text::make("", 'sub_title_uz')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Под описание en', [
                            Text::make("", 'sub_title_en')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Под описание ru', [
                            Text::make("", 'sub_title_ru')
                                ->hideOnIndex()->required()
                        ]),
                    ]),

                    BelongsTo::make(
                        'Work',
                        'work_id',
                        'title_ru'
                    ),

                    Image::make('Файл','file'),

                    Checkbox::make('Это видео ?', 'is_video')
                ]),
            ]),
        ];
	}

	public function rules(Model $item): array
	{
	    return [];
    }

    public function query(): \Illuminate\Contracts\Database\Eloquent\Builder
    {
        return parent::query()
            ->where('is_video', 1);
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
