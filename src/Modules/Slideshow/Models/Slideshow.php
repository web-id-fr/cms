<?php

namespace Webid\Cms\Modules\Slideshow\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Spatie\Translatable\HasTranslations;

/**
 * Class Slideshow
 *
 * @package Webid\Cms\Modules\Slideshow\Models
 *
 * @property Collection $slides
 */
class Slideshow extends Model
{
    use HasTranslations, HasFactory;

    public Collection $slide_items;

    /** @var string  */
    protected $table = 'slideshows';

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'slides',
    ];

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
     * @return BelongsToMany
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

    public function chargeSlideItems(): void
    {
        $slideItems = collect();
        $slides = $this->slides;

        $slides->each(function ($slide) use (&$slideItems) {
            if (!empty($slide->image)) {
                $slide->imageAsset = config('cms.image_path') . $slide->image;
            }
            $slideItems->push($slide);
        });

        $this->slide_items = $slideItems;
    }
}
