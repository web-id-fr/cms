<?php

namespace Webid\Cms\Modules\Articles\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\Translatable\HasTranslations;

/**
 * @property string $name
 * @property Collection<Article> $articles
 * @property \DateTime $publish_at
 * @property \DateTime $updated_at.
 */
class ArticleCategory extends Model
{
    use HasTranslations, HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * @var array
     */
    protected $translatable = [
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }

    /**
     * @param string $language
     * @return Collection<Article>
     */
    public function publishedArticlesForLang(string $language)
    {
        return $this->articles()->publishedForLang($language)->get();
    }
}
