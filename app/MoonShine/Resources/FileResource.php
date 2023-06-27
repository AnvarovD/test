<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;

use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
use MoonShine\Fields\File;
use MoonShine\Fields\Text;
use MoonShine\Fields\TinyMce;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;
use App\Models\File as FileModel;

class FileResource extends Resource
{
    public static string $model = FileModel::class;

    public static string $title = 'Публичная оферта';

    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            Tabs::make([
                Tab::make('Заголовок ru', [
                    Text::make('Заголовок ru', 'title_ru')
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
            Tabs::make([
                Tab::make('Описание uz', [
                    TinyMce::make('description_uz')
                        ->hideOnIndex(),
                ]),

                Tab::make('Описание ru', [
                    TinyMce::make('Описание ru','description_ru'),
                ]),

                Tab::make('Описание en', [
                    TinyMce::make('description_en')
                        ->hideOnIndex(),
                ]),
            ]),

        ];
    }

    public function rules(Model $item): array
    {
        return [
            'title_ru' => ['required', 'string'],
            'title_en' => ['required', 'string'],
            'title_uz' => ['required', 'string'],
            'description_uz' => ['required', 'string'],
            'description_ru' => ['required', 'string'],
            'description_en' => ['required', 'string'],
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
