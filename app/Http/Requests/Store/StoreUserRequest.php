<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'password' => ['required', 'string', 'min:4', 'alpha-num'],
            'email' => ['required', 'string', 'email:filter,dns', 'max:255', 'unique:users']
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'ユーザーネーム',
            'password' => 'パスワード',
            'email' => 'メールアドレス'
        ];
    }

}
