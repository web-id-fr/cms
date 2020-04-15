<?php

namespace Webid\Cms\Src\App\Http\Controllers\Modules\Ajax\Form;

use App\Http\Controllers\Controller;
use Webid\Cms\Src\App\Http\Requests\FormRequest;
use Webid\Cms\Src\App\Mail\SendForm;
use Webid\Cms\Src\App\Repositories\Modules\Form\FormRepository;
use Webid\Cms\Src\App\Repositories\Modules\Form\ServiceRepository;
use Webid\Cms\Src\App\Services\MailService;

class FormController extends Controller
{
    /** @var MailService  */
    protected $mailService;

    /** @var ServiceRepository  */
    protected $serviceRepository;

    /** @var FormRepository  */
    protected $formRepository;

    /**
     * @param MailService $mailService
     * @param ServiceRepository $serviceRepository
     * @param FormRepository $formRepository
     */
    public function __construct(
        MailService $mailService,
        ServiceRepository $serviceRepository,
        FormRepository $formRepository
    ) {
        $this->mailService = $mailService;
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

        $this->mailService->sendForm(new SendForm($request->all()), $to ?? config('mail.from.address'));

        return response()->json([
            'errors' => false,
        ]);
    }
}
