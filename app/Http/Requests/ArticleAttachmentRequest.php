<?php

namespace App\Http\Requests;

use App\Models\Article;
use App\Models\Attachment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArticleAttachmentRequest extends FormRequest
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
        $rules = [
            'attachment' => [
                'required',
                'number',
                Rule::exists(Attachment::class, 'id'),
            ],
            'article' => [
                'required',
                'number',
                Rule::exists(Article::class, 'id'),
            ],
        ];
        return $rules;
    }
}
