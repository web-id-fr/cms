<?php

namespace Webid\Cms\Src\App\Models\Modules\Form;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TitleField extends Model
{
    use HasTranslations;

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
