<?php

namespace Webid\Cms\Tests\Helpers\Traits;

trait CustomAssertions
{
    protected function assertStringContainsStringTimes(string $haystack, string $needle, $timesExpected): void
    {
        $this->assertEquals(
            $timesExpected,
            substr_count($haystack, $needle)
        );
    }
}
