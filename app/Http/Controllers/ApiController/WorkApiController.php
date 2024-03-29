<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobApplicationCreateRequest;
use App\Models\About;
use App\Models\Application;
use App\Models\Client;
use App\Models\Contact;
use App\Models\ContactInfo;
use App\Models\File;
use App\Models\JobApplication;
use App\Models\LicenseAgreement;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostWork;
use App\Models\SocialNetwork;
use App\Models\Vacancy;
use App\Models\Work;
use App\Service\Telegram\ProjectTelegramBot;
use App\Service\Telegram\VacancyTelegramBot;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class WorkApiController extends Controller
{


    public function filesAndNetworks(): JsonResponse
    {
        $descriptions = File::all();
        $networks = SocialNetwork::all();
        return new JsonResponse([
            'descriptions' => $descriptions,
            'socialNetworks' => $networks
        ]);
    }

    public function post(string $slug): JsonResponse
    {
        $post = Post::query()->whereNull('page_id')->where('slug', $slug)->first();

        if ($post == null) {
            return new JsonResponse(["message" => "NOT_FOUND"], 404);
        }

        $images = [];
        if (!empty($post->images)) {
            $post->imageWithLink = $post->images->map(function ($image) use ($images) {
                return env("APP_URL") . '/storage/' . $image;
            })->first();
        }

        return new JsonResponse($post);
    }

    public function about(): JsonResponse
    {
        $about = About::query()->first();
        $posts = Post::query()->whereNull('page_id')->get();
        $posts = $this->getPostImagesWithLinks($posts);
        $clients = $this->getClientIconWithLinks(Client::all());

        $data = [
            "meta" => [
                "meta_title_uz" => $about->meta_title_uz,
                "meta_title_ru" => $about->meta_title_ru,
                "meta_title_en" => $about->meta_title_en,
                "meta_description_uz" => $about->meta_description_uz,
                "meta_description_ru" => $about->meta_description_ru,
                "meta_description_en" => $about->meta_description_en,
            ],
            "banner" => [
                'banner_title_en' => $about->banner_title_en,
                'banner_subtitle_en' => $about->banner_subtitle_en,
                'banner_description_en' => $about->banner_description_en,
                'banner_title_uz' => $about->banner_title_uz,
                'banner_subtitle_uz' => $about->banner_subtitle_uz,
                'banner_description_uz' => $about->banner_description_uz,
                'banner_title_ru' => $about->banner_title_ru,
                'banner_subtitle_ru' => $about->banner_subtitle_ru,
                'banner_description_ru' => $about->banner_description_ru,
                'banner_image' => $about->banner_image,
            ],
            "content" => [
                'content_title_en' => $about->content_title_en,
                'content_subtitle_en' => $about->content_subtitle_en,
                'content_description_en' => $about->content_description_en,
                'content_title_uz' => $about->content_title_uz,
                'content_subtitle_uz' => $about->content_subtitle_uz,
                'content_description_uz' => $about->content_description_uz,
                'content_title_ru' => $about->content_title_ru,
                'content_subtitle_ru' => $about->content_subtitle_ru,
                'content_description_ru' => $about->content_description_ru,
                'posts' => $posts
            ],
            "clients" => $clients,
            "footer" => [
                'footer_title_en' => $about->footer_title_en,
                'footer_subtitle_en' => $about->footer_subtitle_en,
                'footer_description_en' => $about->footer_description_en,
                'footer_title_uz' => $about->footer_title_uz,
                'footer_subtitle_uz' => $about->footer_subtitle_uz,
                'footer_description_uz' => $about->footer_description_uz,
                'footer_title_ru' => $about->footer_title_ru,
                'footer_subtitle_ru' => $about->footer_subtitle_ru,
                'footer_description_ru' => $about->footer_description_ru,
                'imageWithLink' => $this->getFooterImage($about->footer_image),
            ]
        ];

        return new JsonResponse($data);
    }


    public function contacts()
    {
        $contact = Contact::query()->first();
        $contact->location = "<iframe src='{$contact->location}'
        width='600' height='450' style='border:0;'
         allowfullscreen='' loading='lazy'
         referrerpolicy='no-referrer-when-downgrade'></iframe>";

        $contactInfos = ContactInfo::query()->get();

        $data = [
            'contact' => $contact,
            'contactInfos' => $contactInfos
        ];

        return new JsonResponse($data);
    }

    public function getLicenseAgreement(): JsonResponse
    {
        return new JsonResponse(LicenseAgreement::query()->first());
    }

    public function getPublicOffer(string $slug): JsonResponse
    {
        $offer = File::query()
            ->where('slug', $slug)
            ->first();

        if ($offer == null) {
            return new JsonResponse(["message" => "NOT_FOUND"], 404);
        }
        return new JsonResponse($offer);
    }


    public function getSocialNetwork(string $slug): JsonResponse
    {
        $offer = SocialNetwork::query()
            ->where('slug', $slug)
            ->first();

        if ($offer == null) {
            return new JsonResponse(["message" => "NOT_FOUND"], 404);
        }
        return new JsonResponse($offer);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws RequestException
     */
    public function applications(Request $request): JsonResponse
    {
        $validated = $request->validate(
            [
                'name' => ['required', 'string'],
                'phone_or_email' => ['required', 'string'],
                'organization' => ['required', 'string'],
                'description' => ['required', 'string'],
            ]
        );

        $application = new Application();
        $application->name = $validated['name'];
        $application->phone = $validated['phone_or_email'];
        $application->organization = $validated['organization'];
        $application->description = $validated['description'];
        $application->status = 'Новая';
        $application->save();

        $bot = new ProjectTelegramBot();

        $data = [
            'chat_id' => -1001965438185,
            'text' => "Заявки на проекты
Имя: {$application->name}
Номер телефона или Emal: {$application->phone}
Организация: {$application->organization}
Описания: {$application->description}",
        ];

        $bot->sendMessage($data);

        return new JsonResponse(["message" => "Заявка создана успешно"]);
    }

    public function jobApplicationCreate(JobApplicationCreateRequest $request): JsonResponse
    {
        $name = now()->timestamp.".{$request->file->getClientOriginalName()}";
        $path = $request->file('file')->storeAs('files', $name, 'public');

        $jobApplication = new JobApplication();
        $jobApplication->F_I_O = $request->input('F_I_O');
        $jobApplication->contact = $request->input('contact');
        $jobApplication->file = "/{$path}";
        $jobApplication->status = "новая";
        $jobApplication->save();

        $bot = new VacancyTelegramBot();
        $data = [
            'chat_id' => -1001965438185,
            'caption' => "Заявки на вакансии
Имя: {$jobApplication->F_I_O}
Номер телефона: {$jobApplication->contact}",
        ];
        $bot->sendDocument($data, $jobApplication->file, $name);

        return new JsonResponse(["message" => "Заявка создана успешно"]);
    }

    public function vacancys(): JsonResponse
    {
        return new JsonResponse(Vacancy::all());
    }

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
            'slug',
            "meta_title_uz",
            "meta_title_ru",
            "meta_title_en",
            "meta_description_uz",
            "meta_description_ru",
            "meta_description_en",
            "is_main"
        ])->where('is_main', '=', 1)->limit($limit)->get();

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
            $work->file = is_null($work->file) ? '' : env('APP_URL') . '/storage/' . $work->file;
            $work->macro_image = is_null($work->macro_image) ? '' : env('APP_URL') . '/storage/' . $work->macro_image;
            $work->medium_image = is_null($work->medium_image) ? '' : env('APP_URL') . '/storage/' . $work->medium_image;
            $work->micro_image = is_null($work->micro_image) ? '' : env('APP_URL') . '/storage/' . $work->micro_image;
        });

        return new JsonResponse(["works" => $works]);
    }

    public function show(string $slug, Request $request): JsonResponse
    {
        $limit = $request->query->get('limit') ?? null;
        $work = Work::query()->select([
            'work_title_uz',
            'work_title_en',
            'work_title_ru',
            'work_sub_title_uz',
            'work_sub_title_en',
            'work_sub_title_ru',
            'file',
            'video_link',
            'slug',
            'id',
            "meta_title_uz",
            "meta_title_ru",
            "meta_title_en",
            "meta_description_uz",
            "meta_description_ru",
            "meta_description_en",
        ])->where('slug', $slug)
            ->with(
                [
                    'workPosts' => function ($query) use ($limit) {
                        if ($limit) {
                            $query->limit($limit)->get();
                        } else {
                            $query->get();
                        }
                    }
                ]
            )
            ->first();

        if ($work->file) {
            $work->file = is_null($work->file) ? '' : env('APP_URL') . '/storage/' . $work->file;
        }

        $work->workPosts->map(function (PostWork $post) {
            $post->imageLink = is_null($post->image) ? '' : env('APP_URL') . '/storage/' . $post->image;
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

        if ($work->file) {
            $work->file = env('APP_URL') . '/storage/' . $work->file;
        }

        $work->workPosts->map(function (PostWork $post) {
            $post->imageLink = is_null($post->image) ? '' : env('APP_URL') . '/storage/' . $post->image;
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


    /**
     * @param Collection $posts
     * @return Collection|Post[]
     */
    public function getPostImagesWithLinks(Collection $posts): array|Collection
    {
        if ($posts) {
            $posts->map(function ($post) use (&$images) {
                if (!empty($post->images)) {
                    $post->imageWithLink = $post->images->map(function ($image) use ($images) {
                        $images[] = env("APP_URL") . '/storage/' . $image;
                        return $images;
                    })->first();
                }
            });
        }
        return $posts;
    }

    /**
     * @param Collection $clients
     * @return Collection|Post[]
     */
    public function getClientIconWithLinks(Collection $clients): array|Collection
    {
        if ($clients) {
            $clients->map(function (Client $client) {
                $client->icon = env("APP_URL") . '/storage/' . $client->icon;
            });
        }
        return $clients;
    }


    public function getFooterImage(string $image): string
    {
        return env("APP_URL") . '/storage/' . $image;
    }

    public function getFilePath(string $path)
    {
        return env("APP_URL") . '/storage/' . $path;
    }
}
