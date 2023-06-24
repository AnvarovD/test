<?php

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Work;

use MoonShine\Decorations\Block;
use MoonShine\Decorations\Column;
use MoonShine\Decorations\Tab;
use MoonShine\Decorations\Tabs;
use MoonShine\Fields\BelongsTo;
use MoonShine\Fields\BelongsToMany;
use MoonShine\Fields\Checkbox;
use MoonShine\Fields\File;
use MoonShine\Fields\Image;
use MoonShine\Fields\Slug;
use MoonShine\Fields\Text;
use MoonShine\Fields\TinyMce;
use MoonShine\Resources\Resource;
use MoonShine\Fields\ID;
use MoonShine\Actions\FiltersAction;

class WorkResource extends Resource
{
	public static string $model = Work::class;

	public static string $title = 'Works';

	public function fields(): array
	{

        $file = Image::make("Рисунки", "images")
            ->dir("images")
            ->multiple()
            ->removable();
        if ($this->getModel()->is_video){
            $file = Image::make("Рисунки", "images")
                ->dir("1QU6obou6x4qOWF9rHlg15yJMnasU2q0y8gENW2V.png");
        }

		$data = [
		    ID::make()->sortable(),

            Column::make([
                Block::make('Создание Проекта', [

                    Tabs::make([
                        Tab::make('Заголовок ru', [
                            Text::make('title_ru')
                                ->fieldContainer(false)->required(),
                        ]),

                        Tab::make('Заголовок en', [
                            Text::make('title_en')
                                ->fieldContainer(false)
                                ->hideOnIndex()->required(),
                        ]),

                        Tab::make('Заголовок uz', [
                            Text::make('title_uz')
                                ->fieldContainer(false)
                                ->hideOnIndex()->required(),
                        ]),
                    ]),

                    Tabs::make([

                        Tab::make('Под описание uz', [
                            Text::make("", 'sub_title_uz')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Под описание en', [
                            Text::make("", 'sub_title_en')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Под описание ru', [
                            Text::make("", 'sub_title_ru')
                                ->hideOnIndex()->required()
                        ]),
                    ]),

                    Image::make('macro_image','macro_image')->hideOnIndex(),
                    Image::make('medium_image','medium_image')->hideOnIndex(),
                    Image::make('micro_image','micro_image')->hideOnIndex(),
                ]),
            ]),

            Column::make([
                Block::make('Описания проекта', [

                    Tabs::make([
                        Tab::make('Заголовок ru', [
                            Text::make('work_title_ru')
                                ->fieldContainer(false)
                                ->required()->hideOnIndex(),
                        ]),

                        Tab::make('Заголовок en', [
                            Text::make('work_title_en')
                                ->fieldContainer(false)
                                ->hideOnIndex()->required(),
                        ]),

                        Tab::make('Заголовок uz', [
                            Text::make('work_title_uz')
                                ->fieldContainer(false)
                                ->hideOnIndex()->required(),
                        ]),
                    ]),

                    Tabs::make([

                        Tab::make('Под описание uz', [
                            Text::make("", 'work_sub_title_uz')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Под описание en', [
                            Text::make("", 'work_sub_title_en')
                                ->hideOnIndex()->required()
                        ]),

                        Tab::make('Под описание ru', [
                            Text::make("", 'work_sub_title_ru')
                                ->hideOnIndex()->required()
                        ]),
                    ]),



                    File::make('Файл','file'),

                    Checkbox::make('Это видео ?', 'is_video')
                ]),
            ]),
            Slug::make('slug')
                ->from('title_en')
                ->unique()
                ->separator('-')
                ->hideOnIndex()
                ->hideOnDetail()
                ->hideOnCreate()
                ->hideOnUpdate(),
        ];
        return $data;
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
