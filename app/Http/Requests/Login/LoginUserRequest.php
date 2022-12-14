<?php

namespace App\Http\Requests\Login;

use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:10'],
            'password' => ['required', 'string', 'min:4', 'alpha-num']
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'ユーザーネーム',
            'password' => 'パスワード',
        ];
    }

}
