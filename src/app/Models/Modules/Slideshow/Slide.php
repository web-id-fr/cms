<?php

namespace Webid\Cms\App\Models\Modules\Slideshow;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Slide extends Model
{
    use HasTranslations;

    /** @var string  */
    protected $table = 'slides';

    /**
     * The attributes that ar translatable.
     *
     * @var array
     */
    public $translatable = [
        'title',
        'description',
        'url',
        'cta_name',
        'cta_url',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'image',
        'url',
        'cta_name',
        'cta_url',
    ];

    /**
     * @return mixed
     */
    public function slideshows()
    {
        return $this->belongsToMany(Slideshow::class)
            ->withPivot('order');
    }
}
