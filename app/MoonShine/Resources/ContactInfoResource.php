<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\ContactInfo;

use MoonShine\Fields\Text;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class ContactInfoResource extends Resource
{
    public static string $model = ContactInfo::class;

    public static string $title = 'Контактная информация';

    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Заголовок ru', 'title_ru'),
            Text::make('Заголовок uz', 'title_uz'),
            Text::make('Заголовок en', 'title_en'),
            Text::make('Имя и фамилия', 'person_name'),
            Text::make('Email', 'email'),
            Text::make('Номер телефона', 'phone'),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'title_uz' => ['required', 'string'],
            'title_ru' => ['required', 'string'],
            'title_en' => ['required', 'string'],
            'person_name' => ['required', 'string'],
            'email' => ['required', 'string'],
            'phone' => ['required', 'string'],
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
