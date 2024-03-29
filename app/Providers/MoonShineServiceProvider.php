<?php

namespace App\Providers;

use App\MoonShine\Resources\AboutPostResource;
use App\MoonShine\Resources\AboutResource;
use App\MoonShine\Resources\ApplicationResource;
use App\MoonShine\Resources\BlogResource;
use App\MoonShine\Resources\ClientResource;
use App\MoonShine\Resources\ContactInfoResource;
use App\MoonShine\Resources\ContactResource;
use App\MoonShine\Resources\FileResource;
use App\MoonShine\Resources\JobApplicationResource;
use App\MoonShine\Resources\LicenseAgreementResource;
use App\MoonShine\Resources\NewsResource;
use App\MoonShine\Resources\PageResource;
use App\MoonShine\Resources\PostResource;
use App\MoonShine\Resources\PostWorkResource;
use App\MoonShine\Resources\SocialNetworkResource;
use App\MoonShine\Resources\VacancyResource;
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
//        app(MoonShine::class)->menu([
//            MenuItem::make('Admins', new MoonShineUserResource()),
//        ]);

        app(MoonShine::class)->menu([
//            MenuGroup::make('moonshine::ui.resource.system', [
//                MenuItem::make('moonshine::ui.resource.admins_title', new MoonShineUserResource())
//                    ->translatable()
//                    ->icon('users'),
//                MenuItem::make('moonshine::ui.resource.role_title', new MoonShineUserRoleResource())
//                    ->translatable()
//                    ->icon('bookmark'),
//            ])->translatable(),

//            MenuItem::make('Категории', new PageResource())
//                ->translatable()
//                ->icon('app'),

            MenuItem::make('Вакансии', new VacancyResource())
                ->translatable()
                ->icon('app'),

            MenuItem::make('Заявки на вакансии', new JobApplicationResource())
                ->translatable()
                ->icon('app'),

            MenuItem::make('Новости', new NewsResource())
                ->translatable()
                ->icon('app'),

            MenuItem::make('Блог', new BlogResource())
                ->translatable()
                ->icon('app'),

            MenuItem::make('Проекты', new WorkResource())
                ->translatable()
                ->icon('app'),

            MenuItem::make('Посты для проектов', new PostWorkResource())
                ->translatable()
                ->icon('app'),

            MenuItem::make('Наши клиенты', new ClientResource())
                ->translatable()
                ->icon('app'),

            MenuItem::make('О нас', new AboutResource())
                ->translatable()
                ->icon('app'),

            MenuItem::make('Что мы можем - посты', new AboutPostResource())
                ->translatable()
                ->icon('app'),

            MenuItem::make('Заявки', new ApplicationResource())
                ->translatable()
                ->icon('app'),

            MenuItem::make('Публичная оферта', new FileResource())
                ->translatable()
                ->icon('app'),

            MenuItem::make('Пользовательское соглашение', new LicenseAgreementResource())
                ->translatable()
                ->icon('app'),

            MenuItem::make('Социальнее сети', new SocialNetworkResource())
                ->translatable()
                ->icon('app'),

            MenuItem::make('Контакты', new ContactResource())
                ->translatable()
                ->icon('app'),

            MenuItem::make('Контактная информация', new ContactInfoResource())
                ->translatable()
                ->icon('app'),

//            MenuItem::make('Documentation', 'https://laravel.com')
//                ->badge(fn() => 'Check'),
        ]);
    }
}
