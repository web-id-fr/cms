<?php

namespace Webid\Cms\Modules\Faq\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Webid\Cms\Modules\Faq\Tests\FaqTestCase;
use Webid\Cms\Modules\Faq\Tests\Helpers\FaqCreator;
use Webid\Cms\Tests\Helpers\Traits\TestsNovaResource;

class FaqTest extends FaqTestCase
{
    use FaqCreator, TestsNovaResource;

    /**
     * @return string
     */
    protected function getResourceName(): string
    {
        return 'faqs';
    }

    /**
     * @return Model
     */
    protected function getModel(): Model
    {
        return $this->createFaq();
    }
}
