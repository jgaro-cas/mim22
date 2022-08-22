<?php

namespace App\Http\Requests;

use App\Models\Workblock;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWorkblockRequest extends FormRequest
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
        //dd($this->workblock);
        return [
            'block_start' => 'required|date',
            'block_stop' => 'required|date|after:block_start',
            'timezone' => 'required|timezone',
            'workplace_id' => [
                'required',
                'exists:workplaces,id',
                Rule::unique('workblocks')->ignore($this->workblock)->where('block_start', $this->block_start)->where('block_stop', $this->block_stop)
            ],
            'volunteer_number' => 'required|integer|min:0|max:100',
        ];
    }
}
