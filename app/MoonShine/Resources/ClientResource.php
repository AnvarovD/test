<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client;

use MoonShine\Fields\Image;
use MoonShine\Fields\Text;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class ClientResource extends Resource
{
    public static string $model = Client::class;

    public static string $title = 'Наши партнёры';

    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            Image::make('Рисунок', 'icon'),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'icon' => ['image'],
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
