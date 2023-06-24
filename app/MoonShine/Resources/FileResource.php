<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;

use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
use MoonShine\Fields\File;
use MoonShine\Fields\Text;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;
use App\Models\File as FileModel;

class FileResource extends Resource
{
	public static string $model = FileModel::class;

	public static string $title = 'Файлы';

	public function fields(): array
	{
		return [
		    ID::make()->sortable(),
            Tabs::make([
                Tab::make('Заголовок ru', [
                    Text::make('title_ru')
                        ->fieldContainer(false),
                ]),

                Tab::make('Заголовок en', [
                    Text::make('title_en')
                        ->fieldContainer(false)
                        ->hideOnIndex(),
                ]),

                Tab::make('Заголовок uz', [
                    Text::make('title_uz')
                        ->fieldContainer(false)
                        ->hideOnIndex(),
                ]),
            ]),
            File::make('Файл', 'file_path'),

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
