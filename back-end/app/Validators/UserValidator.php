<?php

namespace App\Validators;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class UserValidator
{
    public static function validate(array $input)
    {
        $rules = [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('tenant_id', tenant()->id);
                }),
            ],
            'password' => [
                'required',
                'string',
                'min:8',
            ],
        ];

        return Validator::make($input, $rules)->validate();
    }
}
