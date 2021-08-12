<?php

namespace Webid\Cms\App\Models\Components;

use App\Models\Template;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webid\Cms\App\Models\Traits\HasStatus;
use Webid\Cms\Modules\JavaScript\Models\CodeSnippet;

/**
 * Class CodeSnippetComponent
 * @package Webid\Cms\App\Models\Components
 * @property int $status
 */
class CodeSnippetComponent extends Model
{
    use HasFactory,
        HasStatus;

    const _STATUS_PUBLISHED = 1;
    const _STATUS_DRAFT = 2;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'code_snippets_components';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'status',
        'code_snippet_id',
    ];

    public function codeSnippet(): BelongsTo
    {
        return $this->belongsTo(CodeSnippet::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function templates()
    {
        return $this->morphToMany(Template::class, 'component')
            ->withPivot('order');
    }
}
