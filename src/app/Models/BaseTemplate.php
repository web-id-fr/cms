<?php

namespace Webid\Cms\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Webid\Cms\App\Models\Menu\Menu;
use Webid\Cms\App\Models\Menu\MenuItem;
use Webid\Cms\App\Models\Traits\HasStatusLabels;

abstract class BaseTemplate extends Model
{
    use HasTranslations,
        HasFactory,
        HasStatusLabels;

    const _STATUS_PUBLISHED = 0;
    const _STATUS_DRAFT = 1;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'status',
        'indexation',
        'follow',
        'metatitle',
        'metadescription',
        'opengraph_title',
        'opengraph_description',
        'opengraph_picture',
        'opengraph_picture_alt',
        'publish_at',
        'homepage',
    ];

    /**
     * The attributes that ar translatable.
     *
     * @var array
     */
    public $translatable = [
        'title',
        'slug',
        'metatitle',
        'metadescription',
        'opengraph_title',
        'opengraph_description',
        'opengraph_picture_alt',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'publish_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function menus()
    {
        return $this->morphToMany(Menu::class, 'menuable')
            ->with('children')
            ->withPivot('order', 'parent_id', 'parent_type');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
            ->with('menus')
            ->orderBy('order');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function related()
    {
        return $this->hasMany(Component::class)
            ->orderBy('order');
    }
}
