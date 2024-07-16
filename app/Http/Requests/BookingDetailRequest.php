<?php

namespace App\Http\Requests;

use App\Models\BikeVariant;
use App\Models\Booking;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookingDetailRequest extends FormRequest
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
            'booking' => [
                'required',
                'numeric',
                Rule::exists(Booking::class, 'id'),
            ],
            'bike_variant' => [
                'required',
                'numeric',
                Rule::exists(BikeVariant::class, 'id'),
            ],
            'booking_price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'guaranty_price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'for_child' => [
                'sometimes',
                'boolean',
            ],
        ]);
        if ($this->method() == Request::METHOD_PUT || $this->method() == Request::METHOD_PUT) {
            $rules->pull('booking');
        }
        return $rules->toArray();
    }
}
