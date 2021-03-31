<?php

namespace Webid\Cms\Modules\Form\Http\Requests;

use Webid\Cms\Modules\Form\Models\Field;
use Webid\Cms\Modules\Form\Repositories\FormRepository;
use Illuminate\Foundation\Http\FormRequest as BaseRequest;

class FormRequest extends BaseRequest
{
    /** @var FormRepository  */
    protected $formRepository;

    /**
     * @param FormRepository $formRepository
     * @param array $query
     * @param array $request
     * @param array $attributes
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param null $content
     */
    public function __construct(
        FormRepository $formRepository,
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    ) {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->formRepository = $formRepository;
    }

    /**
     * @return array
     */
    public function constructRulesArray()
    {
        $fields_rules = [];

        if ($this->request->get('form_id')) {
            $form = $this->formRepository->find($this->request->get('form_id'));
        } else {
            $fields_rules['form_id'] = 'integer|required';
            return $fields_rules;
        }

        foreach ($form->related as $field) {
            if ($field->formable_type == Field::class) {
                $field_type = $field->formable->field_type;
                $field_type = config("fields_type.$field_type");
                if (array_key_exists($field_type, config("fields_type_validation"))) {
                    if ($field->formable->required) {
                        $rules = config("fields_type_validation.$field_type") . '|required';
                    } else {
                        $rules = 'nullable|' . config("fields_type_validation.$field_type");
                    }
                    $fields_rules[$field->formable->field_name] = $rules;
                }
            }
        }

        return $fields_rules;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->constructRulesArray();
    }
}
