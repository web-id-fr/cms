<?php

namespace Webid\Cms\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use Webid\Cms\App\Rules\IsUrlPath;

class IsUrlPathTest extends TestCase
{
    /** @test */
    public function rule_is_well_configured()
    {
        $rule = new IsUrlPath();

        foreach ($this->failingValues() as $value) {
            $this->assertFalse($rule->passes('attribute', $value));
        }

        foreach ($this->successfulValues() as $value) {
            $this->assertTrue($rule->passes('attribute', $value));
        }
    }

    private function successfulValues(): array
    {
        return [
            '/toto',
            '/toto/',
            '/12',
            '/with_underscores',
            '/with-dashes',
            '/with/multiple/levels',
            '/with-query-param?with=true',
            '/and-another/?with=true',
            '/avec-caractères-accentués',
        ];
    }

    private function failingValues(): array
    {
        return [
            null,
            '',
            'https://www.truc.com',
            'www.truc.com',
            'www.url.com/',
            'without-slash',
            '//too-many-slashes',
            '/again//too-many',
            '/whoops//',
        ];
    }
}
