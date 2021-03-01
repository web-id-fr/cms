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
        $mailPath = "form::mail.confirmation_contact";
        $file = $this->mailData['pdf'] ?? '';
        $text = $this->mailData['content_email'] ?? 'Merci de nous avoir contactés, nous traitons votre demande.';

        $message = $this->from(config('mail.from.address'), config('mail.from.name'))
            ->view($mailPath)
            ->with([
                "text" => $text,
            ])->subject(config('app.name') . ' - Merci de nous avoir contactés');

        if (!empty($file)) {
            $message->attach($file);
        }

        return $message;
    }
}
