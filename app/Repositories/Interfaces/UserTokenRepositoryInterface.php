<?php

namespace App\Repositories\Interfaces;

use App\Models\UserToken;

interface UserTokenRepositoryInterface
{
//    ユーザーのパスワードリセット用トークンを発行する。すでに存在していれば更新する。
    public function updateOrCreateUserToken(int $userId): UserToken;
}
