<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Application;

use MoonShine\Fields\Select;
use MoonShine\Fields\Text;
use MoonShine\Metrics\DonutChartMetric;
use MoonShine\Metrics\ValueMetric;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class ApplicationResource extends Resource
{
    public static string $model = Application::class;

    public static string $title = 'Заявки';

    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Имя', 'name')->readonly(),
            Text::make('Номер телефона/Email', 'phone')->readonly(),
            Text::make('Организация', 'organization')->readonly(),
            Text::make('Описания', 'description', function($item) {
                return mb_substr($item->description, 0, 50);
            })
                ->readonly(),

            Select::make('Статус', 'status')
                ->options([
                    'Новая' => 'Новая',
                    'В обработке' => 'В обработке',
                    'Закрыто' => 'Закрыто',
                ])
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'name' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'organization' => ['required', 'string'],
            'description' => ['required', 'string'],
            'status' => ['required', 'string'],
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

    public function metrics(): array
    {
        return [
     DonutChartMetric::make('заявки')
         ->values(
             [
                 'Новый' => Application::query()->where('status', 'Новая')->count(),
                 'В обработке' => Application::query()->where('status', 'В обработке')->count(),
                 'Закрыто' => Application::query()->where('status', 'Закрыто')->count(),
             ]
         ),
        ];
    }
}
