<?php

namespace App\Rules;

use App\Repositories\Interfaces\UserTokenRepositoryInterface;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Carbon;

class TokenExpirationTimeRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

//    トークンの有効期限が切れていないかをチェックする

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $now = Carbon::now();
        $userTokenRepository = app()->make(UserTokenRepositoryInterface::class);
        $userToken = $userTokenRepository->getUserTokenfromToken($value);
        $expireTime = new Carbon($userToken->expire_at);

        return $now->lte($expireTime);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '有効期限が過ぎています。パスワードリセットメールを再発行してください。';
    }
}
