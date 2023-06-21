<?php

namespace App\Providers;

use App\MoonShine\Resources\PageResource;
use App\MoonShine\Resources\PostResource;
use App\MoonShine\Resources\PostWorkResource;
use App\MoonShine\Resources\WorkContentResource;
use App\MoonShine\Resources\WorkResource;
use Illuminate\Support\ServiceProvider;
use MoonShine\MoonShine;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
use MoonShine\Resources\MoonShineUserResource;
use MoonShine\Resources\MoonShineUserRoleResource;

class MoonShineServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        app(MoonShine::class)->menu([
            MenuItem::make('Admins', new MoonShineUserResource()),
        ]);

        app(MoonShine::class)->menu([
            MenuGroup::make('moonshine::ui.resource.system', [
                MenuItem::make('moonshine::ui.resource.admins_title', new MoonShineUserResource())
                    ->translatable()
                    ->icon('users'),
                MenuItem::make('moonshine::ui.resource.role_title', new MoonShineUserRoleResource())
                    ->translatable()
                    ->icon('bookmark'),
            ])->translatable(),

            MenuItem::make('Категории', new PageResource())
                ->translatable()
                ->icon('app'),

            MenuItem::make('Стати', new PostResource())
                ->translatable()
                ->icon('app'),

            MenuItem::make('Проекты', new WorkResource())
                ->translatable()
                ->icon('app'),

            MenuItem::make('Посты для проектов', new PostWorkResource())
                ->translatable()
                ->icon('app'),


//            MenuItem::make('Documentation', 'https://laravel.com')
//                ->badge(fn() => 'Check'),
        ]);
    }
}
