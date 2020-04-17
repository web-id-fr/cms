<?php

namespace Webid\Cms\Src\App\Models\Modules\Galleries;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Gallery extends Model
{
    use HasTranslations;

    const _STATUS_PUBLISHED = 1;
    const _STATUS_DRAFT = 2;

    const TYPE_TO_NAME = [
        self::_STATUS_PUBLISHED => 'Published',
        self::_STATUS_DRAFT => 'Draft',
    ];

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
