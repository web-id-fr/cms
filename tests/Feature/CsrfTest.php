<?php

namespace Webid\Cms\Tests\Feature;

use Webid\Cms\Tests\TestCase;

class CsrfTest extends TestCase
{
    /** @test */
    public function route_works()
    {
        $response = $this->get(route('csrf.index'))->assertSuccessful();

        $this->assertEquals(csrf_token(), $response->content());
    }
}
