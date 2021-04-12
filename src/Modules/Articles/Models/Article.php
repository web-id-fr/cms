<?php

namespace Webid\Cms\Modules\Articles\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Webid\Cms\App\Models\Traits\HasFlexible;
use Webid\Cms\App\Models\Traits\HasStatusLabels;
use Webid\Cms\Modules\Slideshow\Models\Slideshow;

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
    protected $with = [
        "categories"
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'article_image',
        'article_image_alt',
        'status',
        'extrait',
        'content',
        'metatitle',
        'metadescription',
        'opengraph_title',
        'opengraph_description',
        'opengraph_picture',
        'opengraph_picture_alt',
        'publish_at',
        'order',
        'not_display_in_list',
        'article_type',
        'author',
    ];

    /**
     * @var array
     */
    public $translatable = [
        'title',
        'slug',
        'article_image_alt',
        'extrait',
        'metatitle',
        'metadescription',
        'opengraph_title',
        'opengraph_description',
        'opengraph_picture_alt',
        'author',
    ];

    protected $casts = [
        'publish_at' => 'datetime',
    ];

    const _STATUS_PUBLISHED = 0;
    const _STATUS_DRAFT = 1;
    const _TYPE_PRESS = 0;
    const _TYPE_NORMAL = 1;
    const _TYPE_CITATION = 2;

    public $available_types = [
        self::_TYPE_PRESS => "press",
        self::_TYPE_NORMAL => "normal",
        self::_TYPE_CITATION => "citation",
    ];

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
        return $this->toFlexible($value);
    }

    /**
     * @return array
     */
    public static function availableArticleTypes(): array
    {
        return [
            self::_TYPE_NORMAL => __('Normal'),
            self::_TYPE_PRESS => __('Press'),
            self::_TYPE_CITATION => __('Citation'),
        ];
    }

    /**
     * @return string
     */
    public function getArticleTypeSlug(): string
    {
        return $this->available_types[$this->article_type];
    }
}
