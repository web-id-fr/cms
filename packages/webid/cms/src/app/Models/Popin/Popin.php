<?php

namespace Webid\Cms\Src\App\Models\Popin;

use App\Models\Template;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Popin extends Model
{
    use HasTranslations;

    /**
     * Valeurs des statuts possibles
     */
    const _STATUS_DRAFT = 1;
    const _STATUS_PUBLISHED = 2;

    /**
     * La liste des statuts possibles, indexée sur la valeur en base
     */
    const STATUSES = [
        self::_STATUS_PUBLISHED => 'Published',
        self::_STATUS_DRAFT => 'Draft',
    ];

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
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function templates()
    {
        return $this->belongsToMany(Template::class, 'popin_template');
    }

    /** @var $template_items */
    public $template_items;

    public function chargeTemplateItems()
    {
        $templateItems = collect();
        $templates = $this->templates;

        $templates->each(function ($recipient) use (&$templateItems) {
            $templateItems->push($recipient);
        });

        $this->template_items = $templateItems;
    }
}
