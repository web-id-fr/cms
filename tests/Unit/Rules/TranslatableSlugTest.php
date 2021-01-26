<?php

namespace Webid\Cms\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use Webid\Cms\App\Rules\TranslatableSlug;

class TranslatableSlugTest extends TestCase
{
    /** @test */
    public function rule_is_well_configured()
    {
        $rule = new TranslatableSlug();

        foreach ($this->failingValues() as $value) {
            $this->assertFalse($rule->passes('attribute', $value), "La valeur suivante devrait échouer : " . print_r($value, true));
        }

        foreach ($this->successfulValues() as $value) {
            $this->assertTrue($rule->passes('attribute', $value), "La valeur suivante devrait passer : " . print_r($value, true));
        }
    }

    private function successfulValues(): array
    {
        return [
            [],
            ['slug'],
            ['slug_avec_underscore'],
            ['slug-avec-tirets'],
            ['slug-avec-nombre-2dans'],
            ['1234567890'],
            [123456],
            ['slug-accentué'],
        ];
    }

    private function failingValues(): array
    {
        return [
            [null],
            [''],
            [' '],
            ['...'],
            ['pas/un/slug'],
            ['pas un slug'],
            ['pas.un.slug'],
            ['#pasunslug']
        ];
    }
}
