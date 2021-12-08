<?php

namespace Webid\Cms\Modules\Newsletter\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'newsletters';

    protected $fillable = [
        'email',
        'lang',
    ];
}
