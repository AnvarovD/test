<?php

namespace App\MoonShine;

use App\Models\Application;
use App\Models\PostWork;
use App\MoonShine\Resources\PostWorkResource;
use MoonShine\Dashboard\DashboardBlock;
use MoonShine\Dashboard\DashboardScreen;
use MoonShine\Dashboard\ResourcePreview;
use MoonShine\Metrics\DonutChartMetric;

class Dashboard extends DashboardScreen
{
	public function blocks(): array
	{
		return [
            DashboardBlock::make([
                DonutChartMetric::make('заявки')
                    ->values(
                        [
                            'Новый' => Application::query()->where('status', 'Новая')->count(),
                            'В обработке' => Application::query()->where('status', 'В обработке')->count(),
                            'Закрыто' => Application::query()->where('status', 'Закрыто')->count(),
                        ]
                    ),
            ]),
            DashboardBlock::make([
                ResourcePreview::make(
                    new PostWorkResource(), // Обязательный параметр с ресурсом MoonShine
                    'Посты для проектов', // Опционально - заголовок блока
                    PostWork::query()->latest()->limit(2) // Опционально QueryBuilder
                )
            ])
        ];
	}
}
