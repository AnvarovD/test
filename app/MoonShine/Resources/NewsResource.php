<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
use MoonShine\Fields\BelongsTo;
use MoonShine\Fields\File;
use MoonShine\Fields\Image;
use MoonShine\Fields\Slug;
use MoonShine\Fields\StackFields;
use MoonShine\Fields\Text;
use MoonShine\Fields\TinyMce;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class NewsResource extends Resource
{
    public static string $model = Post::class;

    public static string $title = 'Новости';

    public function fields(): array
    {

        return [
            ID::make()->sortable(),
            Column::make([
                Block::make('Новости', [

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

                        Tab::make('Описание ru', [
                            TinyMce::make("", 'description_ru')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Описание en', [
                            TinyMce::make("", 'description_en')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Описание uz', [
                            TinyMce::make("", 'description_uz')
                                ->hideOnIndex()->required()
                        ]),

                    ]),
                ]),

                BelongsTo::make(
                    'Родительская Страница',
                    'page_id',
                    'title_ru')
            ]),

            Slug::make('slug')
                ->from('title_en')
                ->unique()
                ->separator('-')
                ->hideOnIndex()
                ->hideOnDetail()
                ->hideOnCreate()
                ->hideOnUpdate(),

            Image::make("Рисунки", "images")
                ->dir("images")
                ->multiple()
                ->removable()

        ];
    }

    public function query(): \Illuminate\Contracts\Database\Eloquent\Builder
    {
        return parent::query()
            ->whereHas('page', function (Builder $query){
                $query->where('slug', 'news');
            });
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

