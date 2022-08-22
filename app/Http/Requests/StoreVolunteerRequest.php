<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVolunteerRequest extends FormRequest
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
            'firstName' => 'required|max:255|min:2',
            'lastName' => 'required|max:255|min:2',
            'email' =>   'required|email',
            'phone' => 'required|phone:auto',
            'selection' => 'required|array',
            'selection.*' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'firstName.required' => 'Le prénom est requis et doit faire 2 à 255 caractères de long',
            'firstName.min' => 'Le prénom est requis et doit faire 2 à 255 caractères de long',
            'firstName.max' => 'Le prénom est requis et doit faire 2 à 255 caractères de long',
            'lastName.required' => 'Le nom est requis et doit faire 2 à 255 caractères de long',
            'lastName.min' => 'Le nom est requis et doit faire 2 à 255 caractères de long',
            'lastName.max' => 'Le nom est requis et doit faire 2 à 255 caractères de long',
            'email.required' => 'Un email valide est requis',
            'email.email' => 'L\'email est déjà utilisé',
            'phone.required' => 'Le numéro de téléphone est requis et doit être au format +XX XX XXX XX XX',
            'phone.phone' => 'Le numéro de téléphone est requis et doit être au format +XX XX XXX XX XX',
            'selection.required' => 'Au moins une tranche horaire doit être sélectionnée',
            'selection.array' => 'Au moins une tranche horaire doit être sélectionnée',
            'selection.*.required' => 'Au moins une tranche horaire doit être sélectionnée',
            'selection.*.integer' => 'Au moins une tranche horaire doit être sélectionnée',

        ];
    }

}
