<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
//    メールアドレスからユーザーを取得
    public function findFromEmail(string $email): User;
}
