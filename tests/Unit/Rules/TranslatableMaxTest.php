<?php

namespace Webid\Cms\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use Webid\Cms\App\Rules\TranslatableMax;

class TranslatableMaxTest extends TestCase
{
    /** @test */
    public function rule_is_well_configured()
    {
        $rule = new TranslatableMax(10);

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
            [''],
            ['1'],
            ['12'],
            ['123'],
            ['1234'],
            ['12345'],
            ['123456'],
            ['1234567'],
            ['12345678'],
            ['123456789'],
            ['msg test'],
        ];
    }

    private function failingValues(): array
    {
        return [
            ['message trop long'],
            ['0123456789876543210'],
        ];
    }
}
