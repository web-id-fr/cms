<?php

namespace Webid\Cms\App\Models\Popin;

use App\Models\Template;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Webid\Cms\App\Models\Traits\HasStatusLabels;

class Popin extends Model
{
    use HasTranslations,
        HasStatusLabels;

    /**
     * Valeurs des statuts possibles
     */
    const _STATUS_DRAFT = 1;
    const _STATUS_PUBLISHED = 2;

    protected $table = 'popins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'status',
        'image',
        'image_alt',
        'description',
        'button_1_title',
        'button_1_url',
        'display_second_button',
        'display_call_to_action',
        'button_2_title',
        'button_2_url',
        'type',
        'button_name',
        'delay',
        'mobile_display',
        'pages',
        'max_display',
    ];

    public $translatable = [
        'title',
        'description',
        'button_1_title',
        'button_1_url',
        'button_2_title',
        'button_2_url',
        'image_alt',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function templates()
    {
        return $this->belongsToMany(Template::class, 'popin_template');
    }
}
