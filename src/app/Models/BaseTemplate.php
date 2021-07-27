<?php

namespace Webid\Cms\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;
use Webid\Cms\App\Models\Contracts\Menuable;
use Webid\Cms\App\Models\Traits\HasFlexible;
use Webid\Cms\App\Models\Traits\HasMenus;
use Webid\Cms\App\Models\Traits\HasStatus;

/**
 * Class BaseTemplate
 *
 * @package Webid\Cms\App\Models
 *
 * @property int $id
 * @property string $title
 * @property array $slug
 * @property int|bool $homepage
 * @property int $status
 * @property int|bool $contains_articles_list
 * @property \DateTime $publish_at
 */
abstract class BaseTemplate extends Model implements Menuable
{
    use HasTranslations,
        HasFactory,
        HasStatus,
        HasMenus,
        HasFlexible;

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
        'meta_keywords',
        'publish_at',
        'homepage',
        'menu_description',
        'contains_articles_list',
        'breadcrumb',
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
        'menu_description',
        'meta_keywords',
        'breadcrumb',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'publish_at' => 'datetime',
    ];

    public function related(): HasMany
    {
        return $this->hasMany(Component::class)
            ->orderBy('order');
    }

    public function isHomepage(): bool
    {
        return boolval($this->homepage);
    }

    public function containsArticlesList(): bool
    {
        return boolval($this->contains_articles_list);
    }

    /**
     * @param string $value
     *
     * @return \Whitecube\NovaFlexibleContent\Layouts\Collection|string
     */
    public function getBreadcrumbAttribute($value)
    {
        return $this->toFlexible($value);
    }
}
