<?php

namespace Webid\Cms\Modules\Galleries\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Webid\Cms\App\Models\Traits\HasStatusLabels;

/**
 * Class Gallery
 * @package Webid\Cms\Modules\Galleries\Models
 * @property int $status
 */
class Gallery extends Model
{
    use HasTranslations,
        HasFactory,
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
