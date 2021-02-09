<?php

namespace Webid\Cms\Modules\Slideshow\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Slide extends Model
{
    use HasTranslations, HasFactory;

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
        'image_alt',
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
        'image_alt',
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
