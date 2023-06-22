<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PostWork;
use App\Models\Work;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $limit = $request->query->get('limit') ?? 4;
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
        ])->limit($limit)->get();

        $works->map(function (Work $work) {
            $work->macro_image = env('APP_URL') . '/storage/' . $work->macro_image;
            $work->medium_image = env('APP_URL') . '/storage/' . $work->medium_image;
            $work->micro_image = env('APP_URL') . '/storage/' . $work->micro_image;
        });

        $news = Page::query()->where('slug', 'news')->with('posts')
            ->limit(3)->latest()->first();

        return new JsonResponse(["works" => $works, "news" => $news->posts]);
    }


    public function works()
    {
        $works = Work::query()->latest()->get();

        $works->map(function (Work $work) {
            $work->macro_image = env('APP_URL') . '/storage/' . $work->macro_image;
            $work->medium_image = env('APP_URL') . '/storage/' . $work->medium_image;
            $work->micro_image = env('APP_URL') . '/storage/' . $work->micro_image;
        });

        return new JsonResponse(["works" => $works]);
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
            'slug',
            'id'
            ])->where('slug', $slug)
            ->with(
                [
                    'workPosts' => function ($query) use ($limit) {
                        $query->limit($limit)->get();
                    }
                ]
            )
            ->first();

        if($work->file) {
            $work->file = is_null($work->file) ? '' : env('APP_URL') . '/storage/' . $work->file;
        }

        $work->workPosts->map(function (PostWork $post){
           $post->imageLink =   is_null($post->image) ? '' : env('APP_URL') . '/storage/' . $post->image;
        });


        $works = Work::query()->where('id', '!=', $work->id)->limit(3)->inRandomOrder()->get();

        $works->map(function (Work $work) {
            $work->file = is_null($work->file) ? '' : env('APP_URL') . '/storage/' . $work->file;
            $work->macro_image = is_null($work->macro_image) ? '' : env('APP_URL') . '/storage/' . $work->macro_image;
            $work->medium_image = is_null($work->medium_image) ? '' : env('APP_URL') . '/storage/' . $work->medium_image;
            $work->micro_image = is_null($work->micro_image) ? '' : env('APP_URL') . '/storage/' . $work->micro_image;
        });



        $work->other_projects = $works;

        return new JsonResponse($work);
    }

    public function showWorkContent(string $slug, Request $request): JsonResponse
    {
        $work = Work::query()->where('slug', $slug)->with(['workPosts'])->first();

        if ($work === null) {
            return new JsonResponse(["message" => "NOT_FOUND"], 404);
        }

        $work->macro_image = is_null($work->macro_image) ? '' : env('APP_URL') . '/storage/' . $work->macro_image;
        $work->medium_image = is_null($work->medium_image) ? '' : env('APP_URL') . '/storage/' . $work->medium_image;
        $work->micro_image = is_null($work->micro_image) ? '' : env('APP_URL') . '/storage/' . $work->micro_image;

        if($work->file) {
            $work->file = env('APP_URL') . '/storage/' . $work->file;
        }

        $work->workPosts->map(function (PostWork $post){
            $post->imageLink =   is_null($post->image) ? '' : env('APP_URL') . '/storage/' . $post->image;
        });

        $works = Work::query()->where('id', '!=', $work->id)->inRandomOrder()->get();

        $works->map(function (Work $work) {
            $work->file = is_null($work->file) ? '' : env('APP_URL') . '/storage/' . $work->file;
            $work->macro_image = is_null($work->macro_image) ? '' : env('APP_URL') . '/storage/' . $work->macro_image;
            $work->medium_image = is_null($work->medium_image) ? '' : env('APP_URL') . '/storage/' . $work->medium_image;
            $work->micro_image = is_null($work->micro_image) ? '' : env('APP_URL') . '/storage/' . $work->micro_image;
        });

        $work->other_projects = $works;

        return new JsonResponse($work);
    }
}
