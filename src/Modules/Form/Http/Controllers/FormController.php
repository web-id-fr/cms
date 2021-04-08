<?php

namespace Webid\Cms\Modules\Form\Http\Controllers;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Webid\Cms\App\Http\Controllers\BaseController;
use Webid\Cms\Modules\Form\Http\Requests\FormRequest;
use Webid\Cms\Modules\Form\Mail\SendForm;
use Webid\Cms\Modules\Form\Repositories\FormRepository;
use Webid\Cms\Modules\Form\Repositories\ServiceRepository;

class FormController extends BaseController
{
    /** @var ServiceRepository */
    protected $serviceRepository;

    /** @var FormRepository */
    protected $formRepository;

    /** @var string */
    protected $sendConfirmationContact;

    /**
     * @param ServiceRepository $serviceRepository
     * @param FormRepository $formRepository
     */
    public function __construct(
        ServiceRepository $serviceRepository,
        FormRepository $formRepository
    ) {
        $this->serviceRepository = $serviceRepository;
        $this->formRepository = $formRepository;
        $this->sendConfirmationContact = config('form.send_confirmation_contact_class');
    }

    /**
     * @param FormRequest $request
     * @return JsonResponse
     * @throws DecryptException
     */
    protected function handle(FormRequest $request)
    {
        if ($request->service) {
            $service = $this->serviceRepository->get($request->service);
            $to = $service->recipients->pluck("email");
        } else {
            $form = $this->formRepository->find($request->form_id);
            $to = $form->recipients->pluck("email");
        }

        $files = !empty($request->file) ? $request->file : null;

        $fields = Arr::except($request->post(), ['valid_from', 'form_id', 'file', 'confirmation_email_name', 'extra']);

        Mail::to($to ?? config('mail.from.address'))->send(new SendForm($fields, $files));

        if (config('form.send_email_confirmation') && !empty($request->confirmation_email_name)) {
            $field = $request->confirmation_email_name;
            $email = $request->$field;

            if (!empty($request->extra)) {
                $extra = $this->decryptExtraParameters($request->extra);
            }

            Mail::to($email)->send(new $this->sendConfirmationContact($extra ?? []));
        }

        return response()->json([
            'errors' => false,
        ]);
    }

    /**
     * @param string $extraParametersEncrypted
     * @return array
     * @throws DecryptException
     */
    private function decryptExtraParameters(string $extraParametersEncrypted): array
    {
        $extraParameters = Crypt::decrypt($extraParametersEncrypted);

        return array_map(function ($parameter) {
            if (is_array($parameter) && arrayKeysAreLocales($parameter)) {
                return $parameter[app()->getLocale()] ?? '';
            }

            return $parameter;
        }, $extraParameters);
    }
}
