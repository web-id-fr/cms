<?php

namespace Webid\Cms\Tests\Unit;

use PHPUnit\Framework\TestCase;

class ValidateJsonFilesTest extends TestCase
{
    const JSON_FILES = [
        'src/resources/lang/vendor/nova/en.json',
        'src/resources/lang/vendor/nova/fr.json',
        'src/Modules/Form/Resources/lang/dropzone-traduction.json',
    ];

    /** @test */
    public function json_files_are_ok()
    {
        foreach (self::JSON_FILES as $json_file_path) {
            $json = file_get_contents($json_file_path);

            if (false === $json) {
                $this->fail("The file {$json_file_path} does not exist.");
            }

            $this->assertJson(
                $json,
                "The file {$json_file_path} does not contain valid JSON."
            );
        }
    }
}
