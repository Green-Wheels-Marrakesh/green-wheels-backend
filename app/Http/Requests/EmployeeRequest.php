<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
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
            'user' => [
                'required',
                'integer',
                Rule::exists(User::class, 'id'),
            ],
            'salary' => [
                'required',
                'numeric',
                'digits_between:4,10',
            ],
            'start_date' => [
                'required',
                'date_format:Y-m-d',
            ],
        ]);
        return $rules->toArray();
    }
}
