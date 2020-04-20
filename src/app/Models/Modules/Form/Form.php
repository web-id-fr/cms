<?php

namespace Webid\Cms\Src\App\Models\Modules\Form;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Form extends Model
{
    use HasTranslations;

    const _STATUS_PUBLISHED = 0;
    const _STATUS_DRAFT = 1;

    const _RECIPIENTS = 1;
    const _SERVICES = 2;

    const TYPE_TO_NAME = [
        self::_STATUS_PUBLISHED => 'published',
        self::_STATUS_DRAFT => 'draft',
    ];

    const TYPE_TO_SERVICE = [
        self::_RECIPIENTS => 'Recipients',
        self::_SERVICES => 'Services'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'forms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'status',
        'description',
        'recipient_type',
        'title_service',
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
    ];

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
        return $this->belongsToMany(Service::class);
    }

    /** @var $field_items */
    public $field_items;

    public function chargeFieldItems()
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

    /** @var $recipient_items */
    public $recipient_items;

    public function chargeRecipientItems()
    {
        $recipientItems = collect();
        $recipients = $this->recipients;

        $recipients->each(function ($recipient) use (&$recipientItems) {
            $recipientItems->push($recipient);
        });

        $this->recipient_items = $recipientItems;
    }

    /** @var $service_items */
    public $service_items;

    public function chargeServiceItems()
    {
        $serviceItems = collect();
        $services = $this->services;

        $services->each(function ($service) use (&$serviceItems) {
            $serviceItems->push($service);
        });

        $this->service_items = $serviceItems;
    }
}
