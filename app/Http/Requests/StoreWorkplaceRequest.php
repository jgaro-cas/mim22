<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWorkplaceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required',
                        'max:255',
                        'min:2',
                        Rule::unique('workplaces', 'name')]
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Ce nom est déjà utilisé',
            'name.required' => 'Le nom est requis et doit faire :min à :max caractères de long',
            'name.max' => 'Le nom est requis et doit faire :min à :max caractères de long',
            'name.min' => 'Le nom est requis et doit faire :min à :max caractères de long',
        ];
    }


}
