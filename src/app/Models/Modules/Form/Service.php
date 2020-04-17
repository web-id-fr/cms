<?php

namespace Webid\Cms\Src\App\Models\Modules\Form;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Service extends Model
{
    use HasTranslations;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'services';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that ar translatable.
     *
     * @var array
     */
    public $translatable = [
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function recipients()
    {
        return $this->belongsToMany(Recipient::class);
    }

    /** @var $recipient_items */
    public $recipient_items;

    public function chargeRecipientItems()
    {
        $recipientItems = collect();
        $recipients = $this->recipients;

        $recipients->each(function ($recipient) use (&$recipientItems) {
            $recipientItems->push($recipient);
        });

        $this->recipient_items = $recipientItems;
    }
}
