<?php

namespace App\Http\Requests;

use App\Enums\TourModeEnum;
use App\Models\Booking;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Enum\Laravel\Rules\EnumRule;

class TourBookingRequest extends FormRequest
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
                'integer',
                Rule::exists(Booking::class, 'id'),
            ],
            'tour_type' => [
                'sometimes',
                'string',
            ],
            'tour_mode' => [
                'required',
                new EnumRule(TourModeEnum::class),
            ],
            'guide' => [
                'sometimes',
                'array',
            ],
            'guide_assistant' => [
                'sometimes',
                'array',
            ],
        ]);
        if ($this->method() == Request::METHOD_PUT || $this->method() == Request::METHOD_PUT) {
            $rules->pull('booking');
        }
        return $rules->toArray();
    }
}
