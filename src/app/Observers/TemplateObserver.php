<?php

namespace Webid\Cms\App\Observers;

use App\Models\Template;
use Webid\Cms\App\Observers\Traits\GenerateTranslatableSlugIfNecessary;
use Webid\Cms\App\Repositories\TemplateRepository;

class TemplateObserver
{
    use GenerateTranslatableSlugIfNecessary;

    public function __construct(TemplateRepository $templateRepository)
    {
        $this->repository = $templateRepository;
    }

    /**
     * @param Template $template
     */
    public function saving(Template $template): void
    {
        $titles = $template->getTranslations('title');
        $originalSlug = $template->getOriginal('slug') ?? [];
        $value = $template->getTranslations('slug') ?? [];

        $allSlug = $this->generateMissingSlugs($originalSlug, $value, $titles);

        $template->slug = $allSlug;
    }
}
