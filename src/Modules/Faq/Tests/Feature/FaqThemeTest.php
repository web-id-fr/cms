<?php

namespace Webid\Cms\Modules\Faq\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Webid\Cms\Modules\Faq\Tests\FaqTestCase;
use Webid\Cms\Modules\Faq\Tests\Helpers\FaqThemeCreator;
use Webid\Cms\Tests\Helpers\Traits\TestsNovaResource;

class FaqThemeTest extends FaqTestCase
{
    use FaqThemeCreator, TestsNovaResource;

    /**
     * @return string
     */
    protected function getResourceName(): string
    {
        return 'faq-themes';
    }

    /**
     * @return Model
     */
    protected function getModel(): Model
    {
        return $this->createFaqTheme();
    }
}
