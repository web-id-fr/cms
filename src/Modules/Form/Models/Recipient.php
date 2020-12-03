<?php

namespace Webid\Cms\Src\Modules\Form\Models;

use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'recipients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
    ];
}
