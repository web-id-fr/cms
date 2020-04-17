<?php

namespace Webid\Cms\Src\App\Models\Newsletter;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    /**
     * @var string
     */
    protected $table = 'newsletters';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'lang',
    ];
}
