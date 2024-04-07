<?php

namespace App\Http\Requests;

use App\Models\BikeVariant;
use App\Models\Client;
use App\Models\Operation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookingRequest extends FormRequest
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
            'operation' => [
                'required',
                'integer',
                Rule::exists(Operation::class, 'id'),
            ],
            'client' => [
                'required',
                'integer',
                Rule::exists(Client::class, 'id'),
            ],
            'bike_variant' => [
                'required',
                'integer',
                Rule::exists(BikeVariant::class, 'id'),
            ],
            'date_end' => [
                'required',
                'date',
            ],
            'child' => [
                'required',
                'boolean',
            ],
            'guaranty_price' => [
                'sometimes',
                'numeric',
                'min:0',
            ],
        ]);
        if ($this->method() == Request::METHOD_PUT || $this->method() == Request::METHOD_PUT) {
            $rules->pull('oepration');
        }
        return $rules->toArray();
    }
}
