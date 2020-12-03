<?php

namespace Webid\Cms\Src\App\Modules\Newsletter\Http\Controllers\Newsletter;

use App\Http\Controllers\Controller;
use Webid\Cms\Src\App\Modules\Newsletter\Http\Requests\StoreNewsletter;
use Webid\Cms\Src\App\Modules\Newsletter\Repositories\NewsletterRepository;
use Illuminate\Support\Facades\App;

class NewsletterController extends Controller
{
    /** @var \Webid\Cms\Src\App\Modules\Newsletter\Repositories\  */
    protected $newsletterRepository;

    /**
     * NewsletterController constructor.
     *
     * @param \Webid\Cms\Src\App\Modules\Newsletter\Repositories\NewsletterRepository  $newsletterRepository
     */
    public function __construct(NewsletterRepository $newsletterRepository)
    {
        $this->newsletterRepository = $newsletterRepository;
    }

    /**
     * @param \Webid\Cms\Src\App\Modules\Newsletter\Http\Requests\StoreNewsletter $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreNewsletter $request) {
        $data = $request->validated();
        $data['lang'] = App::getLocale();
        $this->newsletterRepository->create($data);

        return $this->jsonSuccess(__('template.newsletter_validation'));
    }
}
