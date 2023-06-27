<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Contact;
use Illuminate\Support\Facades\Route;
use MoonShine\Fields\Text;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class ContactResource extends Resource
{
    public static string $model = Contact::class;

    public static string $title = 'Контакты';

    protected string $routeAfterSave = 'show'; // index, show, edit
    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('адрес uz', 'address_uz'),
            Text::make('адрес ru', 'address_ru'),
            Text::make('адрес en', 'address_en'),
            Text::make('Линк для локации', 'location')->hideOnIndex(),
            Text::make('Email', 'email'),
            Text::make('Номер телефона', 'phone'),
        ];
    }

    public function rules(Model $item): array
    {
        return [
            'address_uz' => ['required', 'string'],
            'address_ru' => ['required', 'string'],
            'address_en' => ['required', 'string'],
            'location' => ['required', 'string'],
            'email' => ['required', 'string'],
            'phone' => ['required', 'string'],
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

//    public function resolveRoutes(): void
//    {
//        parent::resolveRoutes();
//       $this->route('show', Contact::query()->first()->id);
//    }
//        parent::resolveRoutes();
//
//        Route::prefix('resource')->group(function (): void {
//            Route::get("{$this->uriKey()}/index", function (Contact $item) {
//                dd($this->uriKey());
//
//                dd($item);
//                $item->restore();
//
//                return redirect()->back();
//            });
//        });
//    }
}
