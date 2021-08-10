<?php

namespace Webid\Cms\Modules\JavaScript\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CodeSnippet
 * @package Webid\Cms\Modules\JavaScript\Models
 * @property int $status
 */
class CodeSnippet extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'code_snippets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code_source',
    ];
}
