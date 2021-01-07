<?php

namespace Webid\Cms\Modules\Form\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TitleField extends Model
{
    use HasTranslations, HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'title_fields';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
    ];

    /**
     * The attributes that ar translatable.
     *
     * @var array
     */
    public $translatable = [
        'title',
    ];
}
