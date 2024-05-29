<?php

namespace App\Http\Requests;

use App\Enums\BikeStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Spatie\Enum\Laravel\Rules\EnumRule;

class BikeRequest extends FormRequest
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
            'bike_type' => [
                'required',
                'string',
            ],
            'bike_model' => [
                'required',
                'string',
            ],
            'bike_mark' => [
                'required',
                'string',
            ],
            'bike_status' => [
                'sometimes',
                new EnumRule(BikeStatusEnum::class),
            ],
            'qty_notification_setting' => [
                'sometimes',
                'integer',
                'min:0',
            ],
        ]);
        return $rules->toArray();
    }
}
