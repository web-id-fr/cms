<?php

namespace Webid\Cms\App\Models\Components;

use App\Models\Template;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Webid\Cms\App\Models\Traits\HasStatusLabels;

class NewsletterComponent extends Model
{
    use HasTranslations,
        HasFactory,
        HasStatusLabels;

    const _STATUS_PUBLISHED = 1;
    const _STATUS_DRAFT = 2;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'newsletters_component';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'status',
        'title',
        'cta_name',
        'placeholder',
    ];

    /**
     * The attributes that ar translatable.
     *
     * @var array
     */
    public $translatable = [
        'title',
        'cta_name',
        'placeholder',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function templates()
    {
        return $this->morphToMany(Template::class, 'component')
            ->withPivot('order');
    }
}
