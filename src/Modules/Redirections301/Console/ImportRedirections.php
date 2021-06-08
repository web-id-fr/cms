<?php

namespace Webid\Cms\Modules\Redirections301\Console;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use SplFileInfo;
use Throwable;
use Webid\Cms\Modules\Redirections301\Repositories\RedirectionRepository;
use Webid\Cms\Modules\Redirections301\Rules\RedirectionRules;
use function Safe\fopen;
use function Safe\parse_url;

class ImportRedirections extends Command
{
    const ERROR_FILE_INVALID = 1;
    const ERROR_FOPEN = 2;
    const ERROR_CREATE = 3;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redirections:import {filepath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a list of redirections in database, for a given CSV file.';

    private RedirectionRepository $redirectionRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->redirectionRepository = app(RedirectionRepository::class);
    }

    /**
     * @return int
     *
     * @throws \Safe\Exceptions\FilesystemException
     */
    public function handle(): int
    {
        /** @var string $filepath */
        $filepath = $this->argument('filepath');

        if (!$this->isValidFilePath($filepath)) {
            $this->error("The file '{$filepath}' must be a CSV file and must be readable.");
            return self::ERROR_FILE_INVALID;
        }

        $fileHandle = fopen($filepath, 'r');

        if ($fileHandle == false) {
            $this->error("An error has occurred while trying to open the file '{$filepath}'.");
            return self::ERROR_FOPEN;
        }

        DB::beginTransaction();
        try {
            $this->importLines($fileHandle);
        } catch (Throwable $exception) {
            DB::rollBack();
            $this->error($exception->getMessage());
            return self::ERROR_CREATE;
        }
        DB::commit();

        $this->line("All redirections were imported successfully!");

        return 0;
    }

    /**
     * @param string $filepath
     * @return bool
     */
    private function isValidFilePath(string $filepath): bool
    {
        $file = new SplFileInfo($filepath);

        $fileExists = $file->isFile();
        $fileIsReadable = $file->isReadable();
        $fileIsCsv = $file->getExtension() === 'csv';

        return $fileExists && $fileIsReadable && $fileIsCsv;
    }

    /**
     * @param resource $fileHandle
     * @throws Throwable
     */
    private function importLines($fileHandle): void
    {
        $currentLine = 0;
        /** @var array $row */
        $row = fgetcsv($fileHandle);

        while ($row !== false) {
            $currentLine++;

            if ($currentLine == 1) {
                continue;
            }

            $from = parse_url($row[0], PHP_URL_PATH) ?? '';
            $to = parse_url($row[1], PHP_URL_PATH) ?? '';

            if (empty($from) || empty($to)) {
                continue;
            }

            $validator = Validator::make([
                'from' => $from,
                'to' => $to,
            ], [
                'from' => RedirectionRules::sourceUrlRules(),
                'to' => RedirectionRules::destinationUrlRules(),
            ]);

            if ($validator->fails()) {
                throw new Exception("Line {$currentLine}, errors: " . implode(' ', $validator->errors()->all()));
            }

            $this->redirectionRepository->create($from, $to);
        }
    }
}
