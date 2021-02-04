<?php

namespace Webid\Cms\Modules\Galleries\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Resource;
use Webid\Cms\App\Nova\Traits\HasIconSvg;
use Webid\Cms\Modules\Galleries\Services\Contracts\GalleryServiceContract;
use Webid\Cms\Modules\Galleries\Models\Gallery as GalleryModel;
use Webid\TranslatableTool\Translatable;

class Gallery extends Resource
{
    use HasIconSvg;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = GalleryModel::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'title',
    ];

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Galleries');
    }

    /**
     * @return string
     */
    public static function singularLabel()
    {
        return __('Gallery');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Translatable::make(__('Title'), 'title')
                ->singleLine()
                ->rules('required', 'array')
                ->sortable(),

            Select::make(__('Folder'), 'folder')
                ->options($this->getFoldersGalleriesName())
                ->rules('required')
                ->hideFromIndex(),

            Translatable::make(__('CTA name show more'), 'cta_name')
                ->singleLine()
                ->rules('required', 'array')
                ->sortable(),

            Select::make(__('Status'), 'status')
                ->options(GalleryModel::statusLabels())
                ->displayUsingLabels()
                ->rules('required', 'integer')
                ->hideFromIndex(),

            Boolean::make(__('Published'), function () {
                return $this->isPublished();
            })->onlyOnIndex(),
        ];
    }

    /**
     * @return array
     */
    public function getFoldersGalleriesName()
    {
        $galleriesFolders = app(GalleryServiceContract::class)->getGalleries();
        $galleries = [];

        if (empty($galleriesFolders)) {
            return [];
        }

        foreach ($galleriesFolders as $galleryFolder) {
            if (is_dir(config('galleries.gallery_path') . $galleryFolder)) {
                $galleries[] = $galleryFolder;
            }
        }

        return array_combine($galleries, $galleries) ?? [];
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->status == GalleryModel::_STATUS_PUBLISHED;
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public static function icon(): string
    {
        return self::svgIcon('galleries', package_module_path('Galleries/Resources/svg'));
    }
}
