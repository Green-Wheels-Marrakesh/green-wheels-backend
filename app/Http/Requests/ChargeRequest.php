<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChargeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = collect([
            'charge_date' => [
                'required',
                'date',
            ],
            'charge_price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'charge_description' => [
                'sometimes',
                'string',
            ],
        ]);
        return $rules->toArray();
    }
}
