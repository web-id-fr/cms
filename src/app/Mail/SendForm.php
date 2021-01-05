<?php

namespace Webid\Cms\App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendForm extends Mailable
{
    use SerializesModels;

    //stock les données qui seront affichées sur le mail
    protected $mailData;

    /** @var $files */
    protected $files;

    /**
     * Create a new message instance
     *
     * @param array $mailData
     */
    public function __construct(array $mailData, $files)
    {
        $this->mailData = $mailData;
        $this->files = $files;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mailPath = "mail.form";

        $message =  $this->from(config('mail.from.address'), config('mail.from.name'))
            ->view($mailPath)
            ->with([
                "mail" => $this->mailData,
            ])->subject('Demande de contact');

        if (isset($this->files)) {
            foreach ($this->files as $file) {
                $message->attach($file, [
                    'as' => $file->getClientOriginalName()
                ]);
            }
        }

        return $message;
    }
}
