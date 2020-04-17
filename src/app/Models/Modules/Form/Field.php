<?php

namespace Webid\Cms\Src\App\Models\Modules\Form;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;

class Field extends Model
{
    use HasTranslations, HasFlexible;

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
    ];

    /**
     * The attributes that ar translatable.
     *
     * @var array
     */
    public $translatable = [
        'placeholder',
        'field_options',
    ];

    /**
     * @return \Whitecube\NovaFlexibleContent\Layouts\Collection
     */
    public function getFlexibleFieldOptionsAttribute()
    {
        return $this->flexible('field_options');
    }
}
