<?php

namespace App\Http\Requests;

use App\Models\Article;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductVariantRequest extends FormRequest
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
            'product' => [
                'required',
                'numeric',
                Rule::exists(Product::class, 'id'),
            ],
            'article' => [
                'required',
                'numeric',
                Rule::exists(Article::class, 'id'),
            ],
            'product_size' => [
                'required',
                'string',
            ],
        ]);
        if ($this->method() == Request::METHOD_PUT || $this->method() == Request::METHOD_PUT) {
            $rules->pull('product');
            $rules->pull('article');
        }
        return $rules->toArray();
    }
}
