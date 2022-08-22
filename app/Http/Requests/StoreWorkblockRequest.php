<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWorkblockRequest extends FormRequest
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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $start = new Carbon($this->block_start);
        $stop = new Carbon($this->block_stop);
        $this->block_start = $start->setTimezone($this->timezone);
        $this->block_stop = $stop->setTimezone($this->timezone);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'block_start' => 'required|date',
            'block_stop' => 'required|date|after:block_start',
            'timezone' => 'required|timezone',
            'workplace_id' => [
                'required',
                'exists:workplaces,id',
                Rule::unique('workblocks')->where('block_start', $this->block_start)->where('block_stop', $this->block_stop)
            ],
            'volunteer_number' => 'required|integer|min:0|max:100',

        ];
    }

    public function messages()
    {
        return [
            'block_start.required' => 'La date et heure de début est requise et doit être au format JJ-MM-AAAA HH:MM',
            'block_start.date' => 'La date et heure de début est requise et doit être au format JJ-MM-AAAA HH:MM',
            'block_stop.required' => 'La date et heure de fin est requise et doit être au format JJ-MM-AAAA HH:MM',
            'block_stop.date' => 'La date et heure de fin est requise et doit être au format JJ-MM-AAAA HH:MM',
            'block_stop.after' => 'La date et heure de fin est requise et doit être postérieure à la date de début',
            'workplace_id.required' => 'L\'emplacement de travail doit être définit',
            'workplace_id.exists' => 'L\'emplacement de travail n\'existe pas dans la base de données',
            'workplace_id.unique' => 'Une tranche horaire identique existe déjà',
            'volunteer_number.required' => 'Un nombre de bénévole doit être définit',
            'volunteer_number.integer' => 'Le nombre de bénévole doit être un chiffre entier',
            'volunteer_number.min' => 'Le nombre de bénévoles doit être entre 0 et 100',
            'volunteer_number.max' => 'Le nombre de bénévoles doit être entre 0 et 100',
       ];
    }

}
