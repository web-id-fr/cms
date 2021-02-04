<?php

namespace Webid\Cms\Modules\Faq\Repositories;

use Webid\Cms\Modules\Faq\Models\Faq;

class FaqRepository
{
    /** @var Faq */
    protected $model;

    /**
     * FaqRepository constructor.
     *
     * @param Faq $model
     */
    public function __construct(Faq $model)
    {
        $this->model = $model;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[]
     */
    public function getPublishedFaqs()
    {
        return $this->model->orderBy('order')
            ->where('status', Faq::_STATUS_PUBLISHED)
            ->with('faqTheme')
            ->get();
    }
}
