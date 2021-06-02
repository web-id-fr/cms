<?php

namespace Webid\Cms\Modules\Form\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendConfirmationContact extends Mailable
{
    use SerializesModels;

    /** @var array */
    protected $mailData;

    /**
     * @param array $mailData
     */
    public function __construct(array $mailData)
    {
        $this->mailData = $mailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        /** @var view-string $mailPath */
        $mailPath = "form::mail.confirmation_contact";

        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->view($mailPath)
            ->with([
                "text" => "Merci de nous avoir contactés, nous traitons votre demande.",
            ])->subject(config('app.name') . ' - Merci de nous avoir contactés');
    }
}
