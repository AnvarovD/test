<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Application;

use MoonShine\Fields\Select;
use MoonShine\Fields\Text;
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
            Text::make('Имя', 'name'),
            Text::make('Номер телефона', 'phone'),
            Text::make('Организация', 'organization'),
            Text::make('Описания', 'description'),
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
