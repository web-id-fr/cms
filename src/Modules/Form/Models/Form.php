<?php

namespace Webid\Cms\Modules\Form\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\Translatable\HasTranslations;
use Webid\Cms\App\Models\Traits\HasStatusLabels;

/**
 * Class Form
 *
 * @package Webid\Cms\Modules\Form\Models
 *
 * @property Collection $fields
 * @property Collection $titleFields
 */
class Form extends Model
{
    use HasTranslations,
        HasFactory,
        HasStatusLabels;

    const _STATUS_PUBLISHED = 0;
    const _STATUS_DRAFT = 1;

    const _RECIPIENTS = 1;
    const _SERVICES = 2;

    const TYPE_TO_SERVICE = [
        self::_RECIPIENTS => 'Recipients',
        self::_SERVICES => 'Services'
    ];

    /** @var Collection $field_items */
    public $field_items;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'forms';

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'related.formables',
        'services',
        'services.recipients',
        'recipients'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'title',
        'status',
        'description',
        'recipient_type',
        'title_service',
        'cta_name',
        'rgpd_mention',
        'confirmation_email_name',
    ];

    /**
     * The attributes that ar translatable.
     *
     * @var array
     */
    public $translatable = [
        'title',
        'description',
        'title_service',
        'cta_name',
        'rgpd_mention',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function related()
    {
        return $this->hasMany(Formable::class)
            ->orderBy('order');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function fields()
    {
        return $this->morphedByMany(Field::class, 'formable')
            ->withPivot('order')
            ->orderBy('order');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function titleFields()
    {
        return $this->morphedByMany(TitleField::class, 'formable')
            ->withPivot('order')
            ->orderBy('order');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function recipients()
    {
        return $this->belongsToMany(Recipient::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function services()
    {
        return $this->belongsToMany(Service::class)
            ->withPivot('order')
            ->orderBy('order');
    }

    /**
     * @return void
     */
    public function chargeFieldItems(): void
    {
        $fieldItems = collect();
        $fields = $this->fields;
        $titleFields = $this->titleFields;

        $fields->each(function ($field) use (&$fieldItems) {
            $field->formable_type = Field::class;
            $field->title = $field->field_name;
            $fieldItems->push($field);
        });
        $titleFields->each(function ($titleField) use (&$fieldItems) {
            $titleField->formable_type = TitleField::class;
            $fieldItems->push($titleField);
        });

        $fieldItems = $fieldItems->sortBy(function ($item) {
            return $item->pivot->order;
        });

        $this->field_items = $fieldItems;
    }
}
