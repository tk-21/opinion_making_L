<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
//    メールアドレスからユーザーを取得
    public function findFromEmail(string $email): User;

//    渡ってきたIDのユーザーのパスワードを更新
    public function updateUserPassword(string $password, int $id): void;
}
