<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Models\Work;
use App\Models\WorkContent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkApiController extends Controller
{


    public function index(): JsonResponse
    {
        $works = Work::query()->select([
            'title_uz',
            'title_ru',
            'title_en',
            'sub_title_uz',
            'sub_title_ru',
            'sub_title_en',
            'macro_image',
            'medium_image',
            'micro_image',
            'slug'
        ])->get();

        return new JsonResponse($works);
    }


    public function show(string $slug, Request $request): JsonResponse
    {
        $limit = $request->query->get('limit') ?? 2;
        $work = Work::query()->select([
            'work_title_uz',
            'work_title_en',
            'work_title_ru',
            'work_sub_title_uz',
            'work_sub_title_en',
            'work_sub_title_ru',
            'file',
            'is_video',
            'slug'
            ])->where('slug', $slug)
            ->with(
                [
                    'workPosts' => function ($query) use ($limit) {
                        $query->limit($limit)->get();
                    }
                ]
            )
            ->first();


        $works = Work::query()->where('id', '!=', $work->id)->limit(3)->inRandomOrder()->get();

        $work->other_projects = $works;

        return new JsonResponse($work);
    }

    public function showWorkContent(string $slug, Request $request): JsonResponse
    {
        $work = Work::query()->where('slug', $slug)->with(['workPosts'])->first();

        if ($work === null) {
            return new JsonResponse(["message" => "NOT_FOUND"], 404);
        }

        $works = Work::query()->where('id', '!=', $work->id)->limit(3)->inRandomOrder()->get();

        $work->other_projects = $works;

        return new JsonResponse($work);
    }
}
