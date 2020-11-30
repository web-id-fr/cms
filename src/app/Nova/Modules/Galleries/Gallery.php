<?php

namespace Webid\Cms\Src\App\Nova\Modules\Galleries;

use Webid\Cms\Src\App\Services\Galleries\Contracts\GalleryServiceContract;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Resource;
use Webid\TranslatableTool\Translatable;
use Webid\Cms\Src\App\Models\Modules\Galleries\Gallery as GalleryModel;

class Gallery extends Resource
{
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

            Translatable::make(__('Title'))
                ->singleLine()
                ->rules('required', 'array')
                ->sortable(),

            Select::make(__('Folder'))
                ->options($this->getFoldersGalleriesName())
                ->rules('required')
                ->hideFromIndex(),

            Translatable::make(__('CTA name show more'), 'cta_name')
                ->singleLine()
                ->rules('required', 'array')
                ->sortable(),

            Select::make(__('Status'), 'status')
                ->options(GalleryModel::TYPE_TO_NAME)
                ->displayUsingLabels()
                ->rules('required', 'integer')
                ->hideFromIndex(),

            Boolean::make(__('Published'), function () {
                return $this->isPublished();
            })->onlyOnIndex(),
        ];
    }

    /**
     * @return array|false
     */
    public function getFoldersGalleriesName()
    {
        $galleriesFolder = app(GalleryServiceContract::class)->getGalleries();
        $galleries = [];

        if(empty($galleriesFolder)) {
            return [];
        }

        foreach ($galleriesFolder as $gallery) {
            $galleries[] = $gallery;
        }

        $galleriesList = array_combine($galleries, $galleries);

        return $galleriesList;
    }

    /**
     * Si le status est publié  : VERT
     *
     * Si ce n'est pas le cas ROUGE
     *
     * @return bool
     */
    public function isPublished()
    {
        return $this->status == GalleryModel::_STATUS_PUBLISHED;
    }

    /**
     * @return string
     */
    public static function icon(): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <circle fill="#B3C1D1" cx="12" cy="12" r="3.2"/>
                    <path fill="#B3C1D1"
                          d="M9 2L7.17 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2h-3.17L15 
                          2H9zm3 15c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/>
                    <path d="M0 0h24v24H0z" fill="none"/>
                </svg>';
    }
}
