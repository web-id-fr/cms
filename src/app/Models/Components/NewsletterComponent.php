<?php

namespace Webid\Cms\App\Models\Components;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class NewsletterComponent extends Model
{
    use HasTranslations;

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
}
