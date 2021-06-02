<?php

namespace Webid\Cms\Modules\Faq\Repositories;

use Illuminate\Support\Collection;
use Webid\Cms\Modules\Faq\Models\FaqTheme;

class FaqThemeRepository
{
    /** @var FaqTheme */
    protected $model;

    /**
     * FaqThemeRepository constructor.
     *
     * @param FaqTheme $model
     */
    public function __construct(FaqTheme $model)
    {
        $this->model = $model;
    }

    /**
     * @return Collection<FaqTheme>
     */
    public function getPublishedFaqThemes()
    {
        return $this->model
            ->where('status', FaqTheme::_STATUS_PUBLISHED)
            ->with('faqs')
            ->get();
    }
}
