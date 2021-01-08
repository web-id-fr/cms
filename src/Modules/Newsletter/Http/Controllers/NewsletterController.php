<?php

namespace Webid\Cms\Modules\Newsletter\Http\Controllers;

use Webid\Cms\App\Http\Controllers\BaseController;
use Webid\Cms\Modules\Newsletter\Http\Requests\StoreNewsletter;
use Webid\Cms\Modules\Newsletter\Repositories\NewsletterRepository;
use Illuminate\Support\Facades\App;

class NewsletterController extends BaseController
{
    /** @var NewsletterRepository  */
    protected $newsletterRepository;

    /**
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
        $this->newsletterRepository->store($data);

        return $this->jsonSuccess(__('template.newsletter_validation'));
    }
}
