<?php

namespace Webid\Cms\App\Models\Components;

use App\Models\Template;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Webid\Cms\Modules\Galleries\Models\Gallery;
use Webid\Cms\App\Models\Traits\HasStatus;

/**
 * Class GalleryComponent
 * @package Webid\Cms\App\Models\Components
 * @property int $status
 */
class GalleryComponent extends Model
{
    use HasFactory,
        HasStatus;

    const _STATUS_PUBLISHED = 1;
    const _STATUS_DRAFT = 2;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'galleries_component';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'status',
    ];

    /** @var Collection */
    public $gallery_items;

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'galleries',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function galleries()
    {
        return $this->belongsToMany(Gallery::class);
    }

    public function chargeGalleryItems(): void
    {
        $galleryItems = collect();
        $galleries = $this->galleries;

        $galleries->each(function ($gallery) use (&$galleryItems) {
            $galleryItems->push($gallery);
        });

        $this->gallery_items = $galleryItems;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function templates()
    {
        return $this->morphToMany(Template::class, 'component')
            ->withPivot('order');
    }
}
