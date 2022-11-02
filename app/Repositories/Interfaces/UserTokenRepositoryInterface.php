<?php

namespace App\Repositories\Interfaces;

use App\Models\UserToken;

interface UserTokenRepositoryInterface
{
//    ユーザーのパスワードリセット用トークンを発行。すでに存在していれば更新する。
    public function updateOrCreateUserToken(int $userId): UserToken;

//    トークンからUserTokenのレコードを1件取得
    public function getUserTokenfromToken(string $token): UserToken;
}
