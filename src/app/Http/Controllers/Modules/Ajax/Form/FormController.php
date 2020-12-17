<?php

namespace Webid\Cms\App\Http\Controllers\Modules\Ajax\Form;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Webid\Cms\App\Http\Requests\FormRequest;
use Webid\Cms\App\Mail\SendForm;
use Webid\Cms\App\Repositories\Modules\Form\FormRepository;
use Webid\Cms\App\Repositories\Modules\Form\ServiceRepository;

class FormController extends Controller
{
    /** @var ServiceRepository */
    protected $serviceRepository;

    /** @var FormRepository */
    protected $formRepository;

    /**
     * @param ServiceRepository $serviceRepository
     * @param FormRepository $formRepository
     */
    public function __construct(ServiceRepository $serviceRepository, FormRepository $formRepository)
    {
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

        $fields = $request->except(['valid_from', 'form_id', 'file']);

        Mail::to($to ?? config('mail.from.address'))->send(new SendForm($fields, $files));

        return response()->json([
            'errors' => false,
        ]);
    }
}
