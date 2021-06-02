<?php

namespace Webid\Cms\Modules\Form\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendForm extends Mailable
{
    use SerializesModels;

    protected string $formSubject;
    protected array $mailData;
    protected ?array $files;

    public function __construct(string $subject, array $mailData, ?array $files)
    {
        $this->formSubject = empty($subject)
            ? 'Demande de contact'
            : $subject;
        $this->mailData = $mailData;
        $this->files = $files;
    }

    public function build(): self
    {
        /** @var view-string $mailPath */
        $mailPath = "form::mail.form";

        $message = $this->from(config('mail.from.address'), config('mail.from.name'))
            ->view($mailPath)
            ->with([
                "mail" => $this->mailData,
            ])
            ->subject($this->formSubject);

        if (isset($this->files)) {
            foreach ($this->files as $file) {
                $message->attach($file, [
                    'as' => $file->getClientOriginalName(),
                ]);
            }
        }

        return $message;
    }
}
