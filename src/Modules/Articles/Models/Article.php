<?php

namespace Webid\Cms\Modules\Articles\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

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
 */
class Article extends Model
{
    use HasTranslations, HasFactory;

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
        'content',
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
     * @return array
     */
    public static function statusLabels(): array
    {
        return [
            self::_STATUS_PUBLISHED => __('Published'),
            self::_STATUS_DRAFT => __('Draft'),
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(ArticleTag::class);
    }

    /**
     * @param Builder $query
     * @param string $language
     * @return Builder
     */
    public function scopePublishedForLang(Builder $query, string $language)
    {
        return $query
            ->where('status', self::_STATUS_PUBLISHED)
            ->where(function ($query) {
                $query->orWhere('publish_at', '<', Carbon::now())
                    ->orWhereNull('publish_at');
            })
            ->where("slug->{$language}", '!=', '');
    }
}
