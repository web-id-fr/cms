<?php

namespace Webid\Cms\Src\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewsletter extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email:rfc,dns|unique:newsletters,email',
        ];
    }
}
