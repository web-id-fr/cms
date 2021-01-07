<?php

namespace Webid\Cms\App\Http\Controllers\Ajax\Newsletter;

use Webid\Cms\App\Http\Controllers\BaseController;
use Webid\Cms\App\Http\Requests\StoreNewsletter;
use Webid\Cms\App\Repositories\Newsletter\NewsletterRepository;
use Illuminate\Support\Facades\App;

class NewsletterController extends BaseController
{
    /** @var NewsletterRepository */
    protected $newsletterRepository;

    /**
     * NewsletterController constructor.
     *
     * @param NewsletterRepository $newsletterRepository
     */
    public function __construct(NewsletterRepository $newsletterRepository)
    {
        $this->newsletterRepository = $newsletterRepository;
    }

    /**
     * @param StoreNewsletter $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreNewsletter $request)
    {
        $data = $request->validated();
        $data['lang'] = App::getLocale();
        $this->newsletterRepository->create($data);

        return $this->jsonSuccess(__('template.newsletter_validation'));
    }
}
