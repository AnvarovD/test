<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Page;
use App\Models\PageContent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function indexPage(): JsonResponse
    {
        $page = Page::with(['posts', 'pageContent', 'otherProjects', 'parents'])->whereNull('parent_id')->first();

        /** @var Page $page */
        $page = $this->getPostImagesWithLinks($page);

        $this->getOtherProjectImagesWithLinks($page);

        $this->getPageContentImage($page);

        return new JsonResponse($page);
    }

    /**
     * @param string $slug
     * @return JsonResponse
     */
    public function showPage(string $slug): JsonResponse
    {
        $page = Page::with(['posts', 'pageContent', 'otherProjects'])->where('slug', $slug)->first();

        /** @var Page $page */
        $page = $this->getPostImagesWithLinks($page);

        return new JsonResponse($page);
    }

    public function showPost(string $slug, string $postSlug): JsonResponse
    {
        $page = Page::query()->where('slug', $slug)->whereHas('posts', function (Builder $query) use ($postSlug){
            $query->where('slug', $postSlug);
        })->first();

        /** @var Page $page */

        if ($page === null){
            return new JsonResponse(["message" => "NOT_FOUND"], 404);
        }

        $page = $this->getPostImagesWithLinks($page);

        return new JsonResponse($page->posts->first());
    }

    /**
     * @return JsonResponse
     */
    public function getClients(): JsonResponse
    {
        return new JsonResponse(Client::all());
    }

    /**
     * @param Page $page
     * @return Page
     */
    public function getPostImagesWithLinks(Page $page): Page
    {
        if ($page->posts) {
            $page->posts->map(function ($post) use (&$images) {
                if (!empty($post->images)) {
                  $post->imageWithLink =  $post->images->map(function ($image) use ($images) {
                        $images[] = env("APP_URL") . '/storage/' . $image;
                        return $images;
                    })->first();
                }
            });
        }
        return $page;
    }

    /**
     * @param Page $page
     * @return Page
     */
    public function getOtherProjectImagesWithLinks(Page $page): Page
    {
        if ($page->otherProjects) {
            $page->otherProjects->map(function ($otherProject) use (&$images) {
                if (!empty($otherProject->images)) {
                    $otherProject->imageWithLink =  $otherProject->images->map(function ($image) use ($images) {
                        $images[] = env("APP_URL") . '/storage/' . $image;
                        return $images;
                    })->first();
                }
            });
        }

        return $page;
    }

    /**
     * @param Page $page
     * @return void
     */
    public function getPageContentImage(Page $page): void
    {
        if ($page->pageContent) {
            $page->pageContent->file = env("APP_URL") . '/storage/' . $page->pageContent->file;
        }
    }
}
