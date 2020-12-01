<?php

namespace Webid\Cms\Src\App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendConfirmationContact extends Mailable
{
    use SerializesModels;

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mailPath = "mail.confirmation_contact";

        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->view($mailPath)
            ->subject(config('app.name') . ' - Merci de nous avoir contactés');
    }
}
