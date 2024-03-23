<?php

namespace App\Http\Requests;

use App\Models\Reference;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReferenceRequest extends FormRequest
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
            'original_reference' => [
                'required',
                'string',
                Rule::unique(Reference::class),
            ],
            'generated_reference' => [
                'sometimes',
                'string',
                Rule::unique(Reference::class),
            ],
        ]);
        if ($this->method() == Request::METHOD_PUT || $this->method() == Request::METHOD_PUT) {
            $reference = $this->route()->parameter('reference');
            $rules->put('original_reference', [
                'required',
                'string',
                Rule::unique(Reference::class)->ignore($reference),
            ]);
            $rules->put('generated_reference', [
                'sometimes',
                'string',
                Rule::unique(Reference::class)->ignore($reference),
            ]);
        }
        return $rules->toArray();
    }
}
