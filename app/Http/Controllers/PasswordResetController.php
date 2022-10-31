<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    // パスワードリセットフォームの表示
    public function showRequestForm()
    {
        \view\request_form\index();
    }


    // パスワードリセットのリクエストを受けて、リセット用のメールを送信する
    public function request()
    {
        $csrf_token = get_param('csrf_token', '');

        if (empty($csrf_token)) {
            Msg::push(Msg::ERROR, '不正なリクエストです。');
            redirect(GO_REFERER);
        }

        $email = get_param('email', '');

        $validation = new UserValidation('', '', $email);

        if (!$validation->validateEmail()) {
            Msg::push(Msg::ERROR, 'リクエストに失敗しました。');
            redirect(GO_REFERER);
        }

        $valid_email = $validation->getValidEmail();

        // メールアドレスからユーザー情報を取得
        $exist_user = UserQuery::fetchByEmail($valid_email);

        // 入力されたメールアドレスに該当するユーザーがいなければ、エラーメッセージを表示
        if (!$exist_user) {
            Msg::push(Msg::ERROR, '登録されていないメールアドレスです。');
            redirect(GO_REFERER);
        }

        // 既にパスワードリセットのフロー中がどうかを確認するため
        $passwordResetUser = PasswordResetQuery::fetchByEmail($valid_email);

        $passwordResetToken = bin2hex(random_bytes(32));
        $token_sent_at = (new \DateTime())->format('Y-m-d H:i:s');

        // DBへの登録とメール送信を行う
        try {
            $db = new DataSource;
            $db->begin();

            // 既にパスワードリセットのフロー中がどうかを確認
            if (!$passwordResetUser) {
                // 値が取れてこなければ新規リクエストとみなし、登録
                PasswordResetQuery::insert($valid_email, $passwordResetToken, $token_sent_at);
            } else {
                // 既にフロー中であれば、tokenの再発行と有効期限のリセットを行う
                PasswordResetQuery::update($valid_email, $passwordResetToken, $token_sent_at);
            }

            // メールの送信
            if (!static::sendResetMail($valid_email, $passwordResetToken)) {
                throw new Exception('メール送信に失敗しました。');
            }

            $db->commit();
        } catch (Exception $e) {
            $db->rollback();
            Msg::push(Msg::DEBUG, $e->getMessage());
            Msg::push(Msg::ERROR, 'エラーが発生しました。');
            redirect(GO_REFERER);
        }

        redirect('email_sent');
    }


    // リセット用のメールを送信する
    public function sendResetMail($email, $passwordResetToken)
    {
        mb_language("Japanese");
        mb_internal_encoding("UTF-8");

        $url = sprintf('%s%s?token=%s', BASE_PATH, 'reset_password', $passwordResetToken);

        $subject = 'パスワードリセット用URLをお送りします。';

        $body = <<<EOD
        24時間以内に下記URLへアクセスし、パスワードの変更を完了してください。
        {$url}
        EOD;

        $from = "zzzzz@520328.jp";
        $headers = "From: {$from}\n";
        $headers .= "Reply-To: {$from}\n";
        $headers .= "Content-Transfer-Encoding: BASE64\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\n";

        return mb_send_mail($email, $subject, $body, $headers);
    }


    // メール送信完了画面の表示
    public function showEmailSent()
    {
        \view\email_sent\index();
    }


    // 新しいパスワードの入力フォームを表示
    public function showResetForm()
    {
        $passwordResetToken = get_param('token', '', false);

        // トークンに合致するユーザーを取得
        $passwordResetUser = PasswordResetQuery::fetchByToken($passwordResetToken);

        // トークンに合致するユーザーがいなければ処理を中止
        if (!$passwordResetUser) {
            Msg::push(Msg::ERROR, '無効なURLです。');
            exit;
        }

        // tokenの有効期限を24時間に設定
        $tokenValidPeriod = (new \DateTime())->modify('-24 hour')->format('Y-m-d H:i:s');

        // リクエストが24時間以上前の場合、有効期限切れとする
        if ($passwordResetUser->token_sent_at < $tokenValidPeriod) {
            Msg::push(Msg::ERROR, '有効期限切れです。');
            exit;
        }

        \view\reset_form\index($passwordResetToken);
    }


    // パスワードの変更処理をして完了メールを送信し、ログインする
    public function reset()
    {
        $password = get_param('password', '');

        $validation = new UserValidation('', $password);

        if (!$validation->validatePassword()) {
            Msg::push(Msg::ERROR, 'パスワード変更に失敗しました。');
            redirect(GO_REFERER);
        }

        $valid_password = $validation->getValidPassword();

        $password_confirmation = get_param('password_confirmation', '');

        // 確認用パスワードとの照合
        if ($valid_password !== $password_confirmation) {
            Msg::push(Msg::ERROR, 'パスワードが確認用パスワードと一致していません。');
            redirect(GO_REFERER);
        }

        // パスワードをハッシュ化
        $hashedPassword = password_hash($valid_password, PASSWORD_DEFAULT);

        $csrf_token = get_param('csrf_token', '');

        if (empty($csrf_token)) {
            Msg::push(Msg::ERROR, '不正なリクエストです。');
            redirect(GO_REFERER);
        }

        $passwordResetToken = get_param('password_reset_token', '');

        // トークンに合致するユーザーを取得
        $passwordResetUser = PasswordResetQuery::fetchByToken($passwordResetToken);

        // トークンに合致するユーザーがいなければ処理を中止
        if (!$passwordResetUser) {
            Msg::push(Msg::ERROR, '無効なURLです。');
            exit;
        }

        try {
            $db = new DataSource;
            $db->begin();

            // ユーザーテーブルのパスワードを更新し、パスワードリセットテーブルから削除
            if (UserQuery::update($hashedPassword, $passwordResetUser) && PasswordResetQuery::delete($passwordResetUser)) {
                $db->commit();
                Msg::push(Msg::INFO, 'パスワードの変更が完了しました。');
            }
        } catch (Exception $e) {
            $db->rollback();

            Msg::push(Msg::DEBUG, $e->getMessage());
            Msg::push(Msg::ERROR, 'エラーが発生しました。');
        }

        // メールアドレスからユーザー情報を取得
        $user = UserQuery::fetchByEmail($passwordResetUser->email);

        // 変更完了メール送信
        static::sendCompleteMail($user->email);

        // ログインしてマイページへ遷移
        static::login($user->name, $valid_password);
    }


    // パスワード変更完了メールの送信
    public function sendCompleteMail($email)
    {
        mb_language("Japanese");
        mb_internal_encoding("UTF-8");

        $subject = 'パスワードの変更が完了しました。';

        $body = <<<EOD
        パスワードの変更が完了しました。
        EOD;

        $from = "zzzzz@520328.jp";
        $headers = "From: {$from}\n";
        $headers .= "Reply-To: {$from}\n";
        $headers .= "Content-Transfer-Encoding: BASE64\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\n";

        return mb_send_mail($email, $subject, $body, $headers);
    }


    public function login($name, $password)
    {
        if (Auth::login($name, $password)) {
            $user = UserModel::getSession();
            Msg::push(Msg::INFO, "{$user->name}さん、ようこそ。");
            redirect(GO_HOME);
        } else {
            redirect(GO_REFERER);
        }
    }

}
