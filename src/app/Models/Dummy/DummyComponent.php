<?php

namespace Webid\Cms\App\Models\Dummy;

use App\Models\Template;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webid\Cms\App\Models\Traits\DeleteRelationshipOnCascade;
use Webid\Cms\App\Models\Traits\HasStatus;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property int $status
 * @property string $name
 */
class DummyComponent extends Model
{
    use HasStatus,
        DeleteRelationshipOnCascade,
        HasFactory;

    const _STATUS_PUBLISHED = 1;
    const _STATUS_DRAFT = 2;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dummy_components';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'status',
    ];

    public function templates(): MorphToMany
    {
        return $this->morphToMany(Template::class, 'component')
            ->withPivot('order');
    }
}
