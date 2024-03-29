<?php

namespace Webid\Cms\Modules\Faq\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Webid\Cms\App\Models\Traits\HasStatus;

/**
 * Class Faq
 * @package Webid\Cms\Modules\Faq\Models
 * @property int $status
 */
class Faq extends Model
{
    use HasTranslations, HasStatus, HasFactory;

    const _STATUS_PUBLISHED = 1;
    const _STATUS_DRAFT = 2;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'faqs';

    protected $fillable = [
        'name',
        'question',
        'answer',
        'order',
        'status',
        'faq_theme_id',
    ];

    /**
     * The attributes that ar translatable.
     *
     * @var array
     */
    public $translatable = [
        'question',
        'answer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function faqTheme()
    {
        return $this->belongsTo(FaqTheme::class);
    }
}
