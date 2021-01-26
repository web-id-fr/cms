<?php

namespace Webid\Cms\App\Models\Modules\Galleries;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Webid\Cms\App\Models\Traits\HasStatusLabels;

class Gallery extends Model
{
    use HasTranslations,
        HasStatusLabels;

    const _STATUS_PUBLISHED = 1;
    const _STATUS_DRAFT = 2;

    /**
     * @var string
     */
    protected $table = 'galleries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'status',
        'folder',
        'cta_name',
    ];

    /**
     * The attributes that ar translatable.
     *
     * @var array
     */
    public $translatable = [
        'title',
        'cta_name',
    ];
}
