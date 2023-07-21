<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\JobApplication;
use MoonShine\Fields\File;
use MoonShine\Fields\Image;
use MoonShine\Fields\Select;
use MoonShine\Fields\Text;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class JobApplicationResource extends Resource
{
    public static string $model = JobApplication::class;

    public static string $title = 'JobApplications';


    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Ф.И.О', 'F_I_O')->fieldContainer(false)->readonly(),
            Text::make('Номер телефона/email', 'contact')->fieldContainer(false)->readonly(),
            File::make('Файл', 'file')->fieldContainer(false)->readonly(),
            Text::make('Дата заявки', 'created_at')->fieldContainer()->readonly(),
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
            'F_I_O' => ['required'],
        ];
    }

    public function validationMessages(): array
    {
        return [
            'F_I_O.required' => 'Вы не можете создать запись',
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
