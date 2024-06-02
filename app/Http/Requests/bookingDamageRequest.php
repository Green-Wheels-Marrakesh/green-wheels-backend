<?php

namespace App\Http\Requests;

use App\Models\BookingDetail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class bookingDamageRequest extends FormRequest
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
            'booking_detail' => [
                'required',
                'numeric',
                Rule::exists(BookingDetail::class, 'id'),
            ],
            'damage_type' => [
                'sometimes',
                'string',
            ],
            'damage_date' => [
                'required',
                'date',
            ],
            'damage_estimated_price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'damage_description' => [
                'sometimes',
                'string',
            ],
        ]);
        if ($this->method() == Request::METHOD_PUT || $this->method() == Request::METHOD_PUT) {
            $rules->pull('booking_detail');
        }
        return $rules->toArray();
    }
}
