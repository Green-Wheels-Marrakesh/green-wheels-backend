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
                'numeric',
                Rule::exists(Bike::class, 'id'),
            ],
            'article' => [
                'required',
                'numeric',
                Rule::exists(Article::class, 'id'),
            ],
            'bike_size' => [
                'required',
                'string',
            ],
        ]);
        if ($this->method() == Request::METHOD_PUT || $this->method() == Request::METHOD_PUT) {
            $rules->pull('bike');
            $rules->pull('article');
        }
        return $rules->toArray();
    }
}
