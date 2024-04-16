<?php

namespace App\Http\Requests;

use App\Models\Article;
use App\Models\Selling;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SellingDetailRequest extends FormRequest
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
            'selling' => [
                'required',
                'numeric',
                Rule::exists(Selling::class, 'id'),
            ],
            'article' => [
                'required',
                'numeric',
                Rule::exists(Article::class, 'id'),
            ],
            'selling_price' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);
        if ($this->method() == Request::METHOD_PUT || $this->method() == Request::METHOD_PUT) {
            $rules->pull('selling');
        }
        return $rules->toArray();
    }
}
