<?php

namespace Webid\Cms\Src\App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class MailService
{
    /**
     * @param $form
     * @param $to
     */
    public function sendForm($form, $to)
    {
        if (config('mail.driver') == 'ses') {
            $this->sendEmailviaS3andSES($form);
        } else {
            Mail::to($to)
                ->send($form);
        }
    }

    /**
     * @param $form
     */
    private function sendEmailViaS3andSES($form)
    {
        // envoie du mail
        // en passant pas le repo S3
        // puis execution d'une lambda qui lance le service SES

        $jform = json_encode($form->build());

        $fileName = 'email/' . uniqid() . '.json';

        Storage::disk(config('filesystems.public'))->put($fileName, $jform);
    }
}
