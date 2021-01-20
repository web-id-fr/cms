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

    const MODULES_JSON_FILES = [
        'src/Modules/*/composer.json',
        'src/Modules/*/module.json',
        'src/Modules/*/package.json',
    ];

    /** @test */
    public function json_files_are_ok()
    {
        // On check les fichiers JSON
        foreach (self::JSON_FILES as $json_file_path) {
            $this->checkFileIsOk($json_file_path);
        }

        // On check les fichiers qui existent dans tous les modules
        foreach (scandir(__DIR__ . '/../../src/Modules') as $moduleName) {
            if (in_array($moduleName, ['.', '..'])) {
                continue;
            }

            foreach (self::MODULES_JSON_FILES as $json_file_path) {
                $json_file_path = str_replace('*', $moduleName, $json_file_path);

                if (file_exists($json_file_path)) {
                    $this->checkFileIsOk($json_file_path);
                }
            }
        }
    }

    /**
     * @param string $json_file_path
     */
    protected function checkFileIsOk(string $json_file_path): void
    {
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
