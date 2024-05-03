<?php

namespace App\Http\Requests;

use App\Models\Booking;
use App\Models\ProductVariant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookingAdditionalRequest extends FormRequest
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
            'product_variant' => [
                'required',
                'numeric',
                Rule::exists(ProductVariant::class, 'id'),
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);
        if ($this->method() == Request::METHOD_PUT || $this->method() == Request::METHOD_PUT) {
            $rules->pull('booking');
        }
        return $rules->toArray();
    }
}
