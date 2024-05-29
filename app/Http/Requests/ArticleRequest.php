<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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
            'default_selling_price' => [
                'sometimes',
                'numeric',
                'min:0',
            ],
            'default_booking_rental_price' => [
                'sometimes',
                'numeric',
                'min:0',
            ],
            'default_booking_tour_price' => [
                'sometimes',
                'numeric',
                'min:0',
            ],
            'default_guaranty_price' => [
                'sometimes',
                'numeric',
                'min:0',
            ],
            'buying_price' => [
                'sometimes',
                'numeric',
                'min:0',
            ],
        ]);
        return $rules->toArray();
    }
}
