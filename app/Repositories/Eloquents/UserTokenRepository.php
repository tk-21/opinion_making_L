<?php

namespace App\Repositories\Eloquents;

use App\Models\UserToken;
use App\Repositories\Interfaces\UserTokenRepositoryInterface;

class UserTokenRepository implements UserTokenRepositoryInterface
{
    private $userToken;

    public function __construct(UserToken $userToken)
    {
        $this->userToken = $userToken;
    }

    public function updateOrCreateUserToken(int $userId): UserToken
    {
        $now = Carbon::now();
//        ユーザーIDをハッシュ化
        $provitionalToken = hash('sha256', $userId, '');

//ユーザーIDがuser_tokenテーブルに存在しない場合は新規作成、存在する場合はそのレコードを更新
        return $this->userToken->updateOrCreate(
            [
                'user_id' => $userId,
            ],
            [
//                ハッシュ化したユーザーIDを含むトークンを作成
                'token' => uniqid(rand(), $provitionalToken),
//                トークンの有効期限を現在から48時間後に設定
                'expire_at' => $now->addHours(48)->toDateTimeString(),
            ]);
    }
}
