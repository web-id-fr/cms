<?php

namespace Webid\Cms\Modules\Form\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;

class Field extends Model
{
    use HasTranslations, HasFlexible, HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fields';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'field_options',
        'field_name',
        'field_type',
        'placeholder',
        'required',
        'date_field_title',
        'date_field_placeholder',
        'time_field_title',
        'time_field_placeholder',
        'duration_field_title',
        'field_name_time',
        'field_name_duration',
    ];

    /**
     * The attributes that ar translatable.
     *
     * @var array
     */
    public $translatable = [
        'placeholder',
        'field_options',
        'date_field_title',
        'date_field_placeholder',
        'time_field_title',
        'time_field_placeholder',
        'duration_field_title',
    ];

    /**
     * @param $value
     *
     * @return \Whitecube\NovaFlexibleContent\Layouts\Collection
     */
    public function getFieldOptionsAttribute($value)
    {
        if (request()->is('nova-api*')) {
            return $value;
        }

        return $this->toFlexible($value);
    }
}
