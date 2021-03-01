<?php

namespace Webid\Cms\Modules\Form\Http\Controllers\Abstracts;

use Illuminate\Support\Facades\Mail;
use Webid\Cms\App\Http\Controllers\BaseController;
use Webid\Cms\Modules\Form\Http\Requests\FormRequest;
use Webid\Cms\Modules\Form\Mail\SendForm;
use Webid\Cms\Modules\Form\Repositories\FormRepository;
use Webid\Cms\Modules\Form\Repositories\ServiceRepository;

abstract class FormController extends BaseController
{
    /** @var ServiceRepository */
    protected $serviceRepository;

    /** @var FormRepository */
    protected $formRepository;

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
    }

    /**
     * @param FormRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
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

        $fields = $request->except(['valid_from', 'form_id', 'file', 'confirmation_email_name', 'extra']);

        Mail::to($to ?? config('mail.from.address'))->send(new SendForm($fields, $files));

        if (config('form.send_email_confirmation') && !empty($request->confirmation_email_name)) {
            $field = $request->confirmation_email_name;
            $email = $request->$field;
            $extra = json_decode($request->extra, true) ?? [];

            $this->sendConfirmationEmail($email, $extra);
        }

        return response()->json([
            'errors' => false,
        ]);
    }

    /**
     * @param string $email
     * @param array $extra
     *
     * @return void
     */
    abstract protected function sendConfirmationEmail(string $email, array $extra): void;
}
