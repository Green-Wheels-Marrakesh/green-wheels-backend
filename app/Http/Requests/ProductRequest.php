<?php

namespace App\Http\Requests;

use App\Enums\ProductStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Enum\Laravel\Rules\EnumRule;

class ProductRequest extends FormRequest
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
            'product_type' => [
                'required',
                'string',
            ],
            'product_model' => [
                'sometimes',
                'alpha_num',
            ],
            'product_mark' => [
                'required',
                'string',
            ],
            'product_status' => [
                'sometimes',
                new EnumRule(ProductStatusEnum::class),
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
