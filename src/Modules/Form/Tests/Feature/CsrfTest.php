<?php

namespace Webid\Cms\Modules\Form\Tests\Feature;

use Webid\Cms\Modules\Form\Providers\FormServiceProvider;
use Webid\Cms\Modules\Form\Tests\FormTestCase;

class CsrfTest extends FormTestCase
{
    /** @test */
    public function route_works()
    {
        $response = $this->get(route('csrf.index'))->assertSuccessful();

        $this->assertEquals(csrf_token(), $response->content());
    }
}
