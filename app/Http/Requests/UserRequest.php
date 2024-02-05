<?php

namespace App\Http\Requests;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Enum\Laravel\Rules\EnumRule;

class UserRequest extends PersonRequest
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
            'email' => [
                'required',
                'email',
                Rule::unique(User::class),
            ],
            'role' => [
                'required',
                new EnumRule(RoleEnum::class),
            ],
            'salary' => [
                Rule::requiredIf(fn () => RoleEnum::EMPLOYEE()->equals(RoleEnum::from($this->role))),
                'numeric',
                'digits_between:4,10',
            ],
            'start_date' => [
                Rule::requiredIf(fn () => RoleEnum::EMPLOYEE()->equals(RoleEnum::from($this->role))),
                'date_format:Y-m-d',
            ],
        ];
        if ($this->method() == Request::METHOD_PUT || $this->method() == Request::METHOD_PATCH) {
            $user = $this->route()->parameter('user');
            Arr::add($rules, 'password', [
                'sometimes',
                'confirmed',
                Password::defaults(),
            ]);
            if ($user instanceof User) {
                $rules['email'][2] = Rule::unique(User::class)->ignore($user);
            }
        }
        $personRequestRules = parent::rules();
        $userRequestRules = collect($rules);
        $userRequestRules = $userRequestRules->merge($personRequestRules);
        return $userRequestRules->toArray();
    }
}
