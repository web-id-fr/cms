<?php

namespace Webid\Cms\App\Models\Components;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Webid\Cms\App\Models\Modules\Galleries\Gallery;

class GalleryComponent extends Model
{
    use HasFactory;

    const _STATUS_PUBLISHED = 1;
    const _STATUS_DRAFT = 2;

    const TYPE_TO_NAME = [
        self::_STATUS_PUBLISHED => 'published',
        self::_STATUS_DRAFT => 'draft',
    ];

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function galleries()
    {
        return $this->belongsToMany(Gallery::class);
    }
}
