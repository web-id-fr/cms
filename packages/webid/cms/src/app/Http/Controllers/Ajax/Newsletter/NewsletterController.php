<?php

namespace Webid\Cms\Src\App\Http\Controllers\Ajax\Newsletter;

use App\Http\Controllers\Controller;
use Webid\Cms\Src\App\Http\Requests\StoreNewsletter;
use Webid\Cms\Src\App\Repositories\Newsletter\NewsletterRepository;
use Illuminate\Support\Facades\App;

class NewsletterController extends Controller
{
    /** @var \App\Repositories\Newsletter\NewsletterRepository */
    protected $newsletterRepository;

    /**
     * NewsletterController constructor.
     *
     * @param \App\Repositories\Newsletter\NewsletterRepository $newsletterRepository
     */
    public function __construct(NewsletterRepository $newsletterRepository)
    {
        $this->newsletterRepository = $newsletterRepository;
    }

    /**
     * @param \App\Http\Requests\StoreNewsletter $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreNewsletter $request) {
        $data = $request->validated();
        $data['lang'] = App::getLocale();
        $this->newsletterRepository->create($data);

        return $this->jsonSuccess(__('template.newsletter_validation'));
    }
}
