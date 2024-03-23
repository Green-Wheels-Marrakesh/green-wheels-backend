<?php

namespace App\Http\Requests;

use App\Models\Article;
use App\Models\Bike;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BikeVariantRequest extends FormRequest
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
            'bike' => [
                'required',
                'number',
                Rule::exists(Bike::class, 'id'),
            ],
            'article' => [
                'required',
                'number',
                Rule::exists(Article::class, 'id'),
            ],
            'bike_size' => [
                'required',
                'string',
            ],
        ]);
        return $rules->toArray();
    }
}
