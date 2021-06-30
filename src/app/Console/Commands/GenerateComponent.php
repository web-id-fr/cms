<?php

namespace  Webid\Cms\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class GenerateComponent extends Command
{
    /** @var string */
    protected $signature = 'cms:component:make {name}';

    /** @var string */
    protected $description = 'Generates a new CMS component';

    private Filesystem $files;

    /** Example: MyNewComponent */
    private string $componentName;

    /** Example: my_new_component */
    private string $componentLowerName;

    /** Example: my-new-component */
    private string $componentKebabName;

    /** Example: My New Component */
    private string $componentSpacedName;

    /** Example: My New Components */
    private string $componentSpacedPluralName;

    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /** @var string $name */
        $name = $this->argument('name');

        $this->componentName = Str::studly($name);
        $this->componentLowerName = Str::snake($this->componentName);
        $this->componentKebabName = Str::kebab($this->componentName);
        $this->componentSpacedName = Str::title(str_replace('-', ' ', $this->componentKebabName));
        $this->componentSpacedPluralName = Str::plural($this->componentSpacedName);

        $this->call('make:migration', [
            'name' => "create_{$this->componentLowerName}_components_table",
        ]);

        $this->generateModel();
        $this->generateModelFactory();
        $this->generateNovaResource();
        $this->generateRepository();
        $this->generateJsonResource();
        $this->generateTest();
        $this->generateBladeTemplate();
        $this->generateSeeder();

        $this->info("Generation done successfully.");

        return 0;
    }

    private function generateModel(): void
    {
        $destination = "app/Models/Components/{$this->componentName}Component.php";
        $this->createDirectoryIfNecessary('app/Models/Components');
        $this->files->put($destination, $this->generateFromStub('ComponentModel.stub'));
        $this->info("Created Model: {$destination}");
    }

    private function generateModelFactory(): void
    {
        $destination = "database/Factories/Components/{$this->componentName}ComponentFactory.php";
        $this->createDirectoryIfNecessary('database/Factories/Components');
        $this->files->put($destination, $this->generateFromStub('ComponentFactory.stub'));
        $this->info("Created Factory: {$destination}");
    }

    private function generateNovaResource(): void
    {
        $destination = "app/Nova/Components/{$this->componentName}Component.php";
        $this->createDirectoryIfNecessary('app/Nova/Components');
        $this->files->put($destination, $this->generateFromStub('ComponentNovaResource.stub'));
        $this->info("Created Nova Resource: {$destination}");
    }

    private function generateRepository(): void
    {
        $destination = "app/Repositories/Components/{$this->componentName}ComponentRepository.php";
        $this->createDirectoryIfNecessary('app/Repositories/Components');
        $this->files->put($destination, $this->generateFromStub('ComponentRepository.stub'));
        $this->info("Created Repository: {$destination}");
    }

    private function generateJsonResource(): void
    {
        $destination = "app/Http/Resources/Components/{$this->componentName}ComponentResource.php";
        $this->createDirectoryIfNecessary('app/Http/Resources/Components');
        $this->files->put($destination, $this->generateFromStub('ComponentJsonResource.stub'));
        $this->info("Created JsonResource: {$destination}");
    }

    private function generateTest(): void
    {
        $destination = "tests/Feature/Components/{$this->componentName}ComponentTest.php";
        $this->createDirectoryIfNecessary('tests/Feature/Components');
        $this->files->put($destination, $this->generateFromStub('ComponentTest.stub'));
        $this->info("Created Test: {$destination}");
    }

    private function generateBladeTemplate(): void
    {
        $destination = "resources/views/components/{$this->componentLowerName}.blade.php";
        $this->createDirectoryIfNecessary('resources/views/components');
        $this->files->put($destination, $this->generateFromStub('ComponentBladeTemplate.stub'));
        $this->info("Created Blade template: {$destination}");
    }

    private function generateSeeder(): void
    {
        $destination = "database/seeders/Components/{$this->componentName}ComponentSeeder.php";
        $this->createDirectoryIfNecessary('database/seeders/Components');
        $this->files->put($destination, $this->generateFromStub('ComponentSeeder.stub'));
        $this->info("Created Seeder: {$destination}");
    }

    private function generateFromStub(string $stubFileName): string
    {
        $stub = $this->files->get(base_path("vendor/webid/cms/src/app/Console/Commands/stubs/{$stubFileName}"));

        $stub = str_replace('{{componentName}}', $this->componentName, $stub);
        $stub = str_replace('{{componentLowerName}}', $this->componentLowerName, $stub);
        $stub = str_replace('{{componentKebabName}}', $this->componentKebabName, $stub);
        $stub = str_replace('{{componentSpacedName}}', $this->componentSpacedName, $stub);
        $stub = str_replace('{{componentSpacedPluralName}}', $this->componentSpacedPluralName, $stub);

        return $stub;
    }

    private function createDirectoryIfNecessary(string $directory): void
    {
        if (!$this->files->exists($directory)) {
            $this->files->makeDirectory($directory, 0755, true, true);
        }
    }
}
