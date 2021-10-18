<?php

namespace Webid\Cms\App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Throwable;
use Webid\Cms\App\Exceptions\Templates\DepthExceededException;
use Webid\Cms\App\Exceptions\Templates\MissingParameterException;
use Webid\Cms\App\Exceptions\Templates\TemplateNotFoundException;
use Webid\Cms\App\Http\Resources\Menu\MenuResource;
use Webid\Cms\App\Repositories\Menu\MenuRepository;
use Webid\Cms\App\Services\MenuBladeDirective\Menu;

class MenuService
{
    /** @var string $templatesPath Le chemin du répertoire dans lequel il faut chercher les templates */
    protected $templatesPath = '';

    /** @var MenuRepository */
    private $menuRepository;

    /** @var array<mixed> */
    private $menus = [];

    /** @var array<mixed> */
    private $allMenus = [];

    /**
     * MenuService constructor.
     *
     * @param MenuRepository $menuRepository
     *
     * @param string $templatesPath
     */
    public function __construct(MenuRepository $menuRepository, string $templatesPath = '')
    {
        $this->menuRepository = $menuRepository;

        if (empty($templatesPath)) {
            $templatesPath = resource_path('views');
        }

        $this->templatesPath = $templatesPath;
    }

    /**
     * Wrapper for the app(static::class) method
     *
     * @return static
     */
    public static function make()
    {
        return app(static::class);
    }

    /**
     * @return array
     */
    public function getMenus()
    {
        if (!empty($this->allMenus)) {
            return $this->allMenus;
        }

        try {
            $menus = MenuResource::collection($this->menuRepository->all())->resolve();
        } catch (Throwable $exception) {
            $menus = [];
        }

        $this->allMenus = [];

        foreach ($menus as $menu) {
            $zones = data_get($menu, 'zones', []);

            if (!empty($zones)) {
                foreach ($zones as $zone) {
                    $this->allMenus[$zone]['title'] = data_get($menu, 'title', []);
                    $this->allMenus[$zone]['zones'] = data_get($menu, 'items', []);
                }
            }
        }

        return $this->allMenus;
    }

    /**
     * Récupère la liste des zones de menus dans les templates, dédoublonnée
     *
     * @return Collection
     *
     * @throws MissingParameterException
     * @throws TemplateNotFoundException
     */
    public function getMenusZones(): Collection
    {
        try {
            $bladeTemplates = $this->findTemplatesRecursively($this->templatesPath);

            $menus = collect();

            foreach ($bladeTemplates as $template) {
                $menus = $menus->merge($this->getMenusZonesInTemplate($template));
            }

            return $menus->unique('menuID');
        } catch (DepthExceededException $exception) {
            return collect();
        }
    }

    /**
     * Récupère la liste des zones de menus dans un template
     *
     * @param string $filepath
     *
     * @return array
     *
     * @throws MissingParameterException
     * @throws TemplateNotFoundException
     */
    protected function getMenusZonesInTemplate(string $filepath)
    {
        if (!File::exists($filepath)) {
            throw new TemplateNotFoundException($filepath);
        }

        $content = File::get($filepath);
        $menus = [];
        $matches = [];

        preg_match_all("/\@menu\(([^@<]*)\)/", $content, $matches);

        if (!empty($matches[1])) {
            foreach ($matches[1] as $expressionDeMenu) {
                $menuZone = new Menu($expressionDeMenu);
                array_push($menus, $menuZone);
            }
        }

        return $menus;
    }

    /**
     * Affiche le contenu d'une zone de menu, en injectant les liens issus du BO dans le template associé
     *
     * @param string $expression L'expression utilisée dans la directive, contenant ID, label et options
     *
     * @return string
     */
    public function showMenu($expression): string
    {
        if (isset($this->menus[$expression])) {
            return $this->menus[$expression];
        }

        try {
            $menu = new Menu($expression);
            $menusData = data_get($this->getMenus(), $menu->menuID, []);

            $options = $menu->options;
            $data = [
                'menu_items' => data_get($menusData, 'zones', []),
                'title' => data_get($menusData, 'title', ''),
            ];
        } catch (MissingParameterException $exception) {
            $options = $data = [];
        }

        $this->menus[$expression] = $this->getHtmlForZone('components/menu', $data, $options);

        return $this->menus[$expression];
    }

    /**
     * Cherche de façon récursive tous les templates *.blade.php dans un dossier donné
     *
     * @param string     $currentFolder  Le nom du dossier actuel
     * @param Collection $foundTemplates La liste des templates trouvés (passée par référence)
     * @param int        $depth          La profondeur actuelle, utilisée pour éviter de boucler indéfiniment
     *
     * @return Collection
     * @throws DepthExceededException
     */
    protected function findTemplatesRecursively(
        string $currentFolder,
        Collection $foundTemplates = null,
        int $depth = 0
    ): Collection {
        // Garde-fou, pour éviter de rester bloqué dans une boucle ou une arborescence trop complexe
        if ($depth >= 10) {
            throw new DepthExceededException();
        }

        if (is_null($foundTemplates)) {
            $foundTemplates = collect();
        }

        // On récupère la liste des fichiers à scanner, en excluant certains fichiers / dossiers à éviter
        $filesToScan = collect(scandir(($currentFolder)))
            ->filter(function ($item) {
                $foldersToAvoid = [
                    '.',
                    '..',
                    'node_modules',
                    'images',
                    'fonts',
                    'assets',
                    'dist',
                ];
                return !in_array($item, $foldersToAvoid);
            });

        // On boucle sur les fichiers restants et on ne garde que les templates Blade
        foreach ($filesToScan as $file) {
            // On force le "/"
            $filename = rtrim($currentFolder, '/') . "/$file";

            if (is_dir($filename)) {
                $foundTemplates->merge(
                    $this->findTemplatesRecursively($filename, $foundTemplates, $depth + 1)
                );
            } elseif (Str::is("*blade.php", $filename)) {
                $foundTemplates->push($filename);
            }
        }

        return $foundTemplates;
    }

    /**
     * Traite les données à afficher pour une zone de contenu donnée, et retourne le résultat à afficher en HTML
     *
     * @param string|null $contentType
     * @param array  $data
     * @param array  $options
     *
     * @return mixed|string
     */
    public function getHtmlForZone($contentType, array $data, array $options = [])
    {
        try {
            $view = !empty($contentType) ? strtolower($contentType) : $contentType;
            return view($view)
                ->with($data)
                ->with($options)
                ->render();
        } catch (\Throwable $exception) {
            info($exception->getMessage());
            return '';
        }
    }
}
