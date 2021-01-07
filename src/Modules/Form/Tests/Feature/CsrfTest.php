<?php

namespace Webid\Cms\Modules\Form\Tests\Feature;

use Webid\Cms\Modules\Form\Providers\FormServiceProvider;
use Webid\Cms\Tests\TestCase;

class CsrfTest extends TestCase
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        $providers = parent::getPackageProviders($app);
        array_push($providers, FormServiceProvider::class);

        return $providers;
    }

    /** @test */
    public function route_works()
    {
        $response = $this->get(route('csrf.index'))->assertSuccessful();

        $this->assertEquals(csrf_token(), $response->content());
    }
}
