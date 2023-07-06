<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\SocialNetwork;

use MoonShine\Fields\Slug;
use MoonShine\Fields\Text;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class SocialNetworkResource extends Resource
{
    public static string $model = SocialNetwork::class;

    public static string $title = 'Социальная сети';

    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Названия', 'name'),
            Text::make('Линк', 'link'),
            Slug::make('slug')
                ->from('name')
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
        return [
            'name' => ['required', 'string'],
            'link' => ['required', 'string'],
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
