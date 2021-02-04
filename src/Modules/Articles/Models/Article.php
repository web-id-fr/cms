<?php

namespace Webid\Cms\Modules\Articles\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Webid\Cms\App\Models\Traits\HasStatusLabels;
use Webid\Cms\Modules\Slideshow\Models\Slideshow;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;

/**
 * @property string $title
 * @property string $slug
 * @property string $article_image
 * @property integer $status
 * @property string $extrait
 * @property string $content
 * @property string $metatitle
 * @property string $metadescription
 * @property string $opengraph_title
 * @property string $opengraph_description
 * @property string $opengraph_picture
 * @property Carbon $publish_at
 * @method Builder published()
 * @method Builder publishedForLang(string $language)
 */
class Article extends Model
{
    use HasTranslations,
        HasFactory,
        HasStatusLabels,
        HasFlexible;

    /**
     * @var string
     */
    protected $table = 'articles';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'article_image',
        'status',
        'extrait',
        'content',
        'metatitle',
        'metadescription',
        'opengraph_title',
        'opengraph_description',
        'opengraph_picture',
        'publish_at',
    ];

    /**
     * @var array
     */
    public $translatable = [
        'title',
        'slug',
        'extrait',
        'metatitle',
        'metadescription',
        'opengraph_title',
        'opengraph_description',
    ];

    protected $casts = [
        'publish_at' => 'datetime',
    ];

    const _STATUS_PUBLISHED = 0;
    const _STATUS_DRAFT = 1;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(ArticleCategory::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function slideshows()
    {
        return $this->belongsToMany(Slideshow::class);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', self::_STATUS_PUBLISHED)
            ->where(function ($query) {
                $query->orWhere('publish_at', '<', Carbon::now())
                    ->orWhereNull('publish_at');
            });
    }

    /**
     * @param Builder $query
     * @param string $language
     * @return Builder
     */
    public function scopePublishedForLang(Builder $query, string $language): Builder
    {
        return $this
            ->scopePublished($query)
            ->where("slug->{$language}", '!=', '');
    }

    /**
     * @param $value
     *
     * @return \Whitecube\NovaFlexibleContent\Layouts\Collection
     */
    public function getContentAttribute($value)
    {
        if (request()->is('nova-api*')) {
            return $value;
        }

        return $this->toFlexible($value);
    }
}
