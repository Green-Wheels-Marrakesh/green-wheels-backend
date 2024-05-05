<?php

namespace App\Http\Requests;

use App\Models\Attachment;
use App\Models\Person;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonAttachmentRequest extends FormRequest
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
        $rules = [
            'attachment' => [
                'required',
                'numeric',
                Rule::exists(Attachment::class, 'id'),
            ],
            'person' => [
                'required',
                'numeric',
                Rule::exists(Person::class, 'id'),
            ],
        ];
        return $rules;
    }
}
