<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\LicenseAgreement;

use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
use MoonShine\Fields\Text;
use MoonShine\Fields\TinyMce;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class LicenseAgreementResource extends Resource
{
    public static string $model = LicenseAgreement::class;

    public static string $title = 'Лицензионное соглашение';

    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            Tabs::make([
                Tab::make('Описание uz', [
                    TinyMce::make('Описание uz','description_uz')
                        ->hideOnIndex(),
                ]),

                Tab::make('Описание ru', [
                    TinyMce::make('Описание ru','description_ru'),
                ]),

                Tab::make('Описание en', [
                    TinyMce::make('Описание en','description_en')
                        ->hideOnIndex(),
                ]),
            ]),
        ];
    }

    public function rules(Model $item): array
    {
        return [
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
