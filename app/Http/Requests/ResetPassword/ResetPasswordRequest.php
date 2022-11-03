<?php

namespace App\Http\Requests\ResetPassword;

use App\Rules\TokenExpirationTimeRule;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'password' => ['required', 'min:4', 'alpha-num', 'confirmed'],
            'password_confirmation' => ['required'],
            'reset_token' => ['required', new TokenExpirationTimeRule],
        ];
    }

    public function attributes()
    {
        return [
            'password' => 'パスワード',
            'password_confirmation' => 'パスワード'
        ];
    }

    public function messages()
    {
        return [
            'password.required' => ':attributeを入力してください。',
            'password.min:4' => ':attributeは4桁以上で入力してください。',
            'password.alpha-num' => ':attributeは半角英数字で入力してください。',
            'password.confirmed' => ':attributeが再入力欄と一致していません。',
            'password_confirmation.required' => '確認のため、再入力欄にも:attributeを入力してください。',
        ];
    }
}
