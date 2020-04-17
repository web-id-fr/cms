<?php

namespace Webid\LanguageTool\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateLanguageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'flag' => 'required|string'
        ];
    }
}
