<?php

namespace Webid\Cms\Modules\Redirections301\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $source_url
 * @property string $destination_url
 */
class Redirection extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'redirections';

    protected $fillable = [
        'source_url',
        'destination_url',
    ];
}
