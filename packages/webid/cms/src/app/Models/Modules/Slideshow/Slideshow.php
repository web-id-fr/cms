<?php

namespace Webid\Cms\Src\App\Models\Modules\Slideshow;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Slideshow extends Model
{
    use HasTranslations;

    /** @var string  */
    protected $table = 'slideshows';

    /**
     * The attributes that ar translatable.
     *
     * @var array
     */
    public $translatable = [
        'title',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'js_controls',
        'js_animate_auto',
        'js_speed',
    ];

    /**
     * @return mixed
     */
    public function slides()
    {
        return $this->belongsToMany(Slide::class)
            ->withPivot('order')
            ->orderBy('order');
    }

    /**
     * Set the js_speed.
     *
     * @param string $value
     *
     * @return void
     */
    public function setJsSpeedAttribute($value)
    {
        if (!$value) {
            $this->attributes['js_speed'] = 5000;
        } else {
            $this->attributes['js_speed'] = $value * 1000;
        }
    }

    /** @var $recipient_items */
    public $slide_items;

    public function chargeSlideItems()
    {
        $slideItems = collect();
        $slides = $this->slides;

        $slides->each(function ($slide) use (&$slideItems) {
            $slideItems->push($slide);
        });

        $this->slide_items = $slideItems;
    }
}
