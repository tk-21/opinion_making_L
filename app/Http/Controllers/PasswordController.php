<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SendEmailRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\UserTokenRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use function Symfony\Component\Translation\t;

class PasswordController extends Controller
{
    private $userRepository;
    private $userTokenRepository;

    private const MAIL_SENT_SESSION_KEY = 'mail_send_action';
    private const UPDATE_PASSWORD_SESSION_KEY = 'update_password_action';


    public function __construct(
        UserRepositoryInterface      $userRepository,
        UserTokenRepositoryInterface $userTokenRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->userTokenRepository = $userTokenRepository;
    }


//    メールアドレス入力フォームを表示
    public function emailFormResetPassword()
    {
        return view('reset_password.email_form');
    }


//    パスワードリセットメール送信処理
    public function sendEmailResetPassword(SendEmailRequest $request)
    {
        try {
            $user = $this->userRepository->findFromEmail($request->email);
            $userToken = $this->userTokenRepository->updateOrCreateUserToken($user->id);

            Log::info(__METHOD__ . '...ID: ' . $user->id . 'のユーザーにパスワード再設定用メールを送信します。');
            Mail::send(new UserResetPasswordMail($user, $userToken));

            Log::info(__METHOD__ . '...ID: ' . $user->id . 'のユーザーにパスワード再設定用メールを送信しました。');

        } catch (Exception $e) {
            Log::info(__METHOD__ . 'ユーザーへのパスワード再設定用メール送信に失敗しました。 request_email =' . $request->email . 'error_message =' . $e);
            return to_route('password_reset.email.form')->with('error', '処理に失敗しました。時間をおいて再度お試しください。');
        }
//        送信時にセッションキーに値を保存
        session()->put(self::MAIL_SENT_SESSION_KEY, 'mail_sent');

        return to_route('password_reset.email.send_complete');
    }


//    パスワードリセットメール送信完了画面表示
    public function sendComplete()
    {
//        メール送信時に保存したセッションキーに値がなければアクセスできないようにする
        if (session()->pull(self::MAIL_SENT_SESSION_KEY) !== 'mail_sent') {
            return to_route('password_reset.email.form')->with('error', '不正なリクエストです。');
        }

        return view('reset_password.send_complete');
    }


//    新しいパスワードの入力フォームを表示
    public function edit(Request $request)
    {
        if ($request->hasValidSignature()) {
            abort(403, 'URLの有効期限が過ぎたためエラーが発生しました。パスワードリセットメールを再発行してください。');
        }

        $resetToken = $request->reset_token;

        try {
            $userToken = $this->userTokenRepository->getUserTokenfromToken($resetToken);
        } catch (Exception $e) {
            Log::error(__METHOD__ . ' UserTokenの取得に失敗しました。 error_message = ' . $e);
            return to_route('password_reset.email.form')->with('error', 'パスワードリセットメールに添付されたURLから遷移してください。');
        }

        return view('reset_password.edit')->with('user_token', $userToken);
    }


//    パスワード更新処理
    public function update(ResetPasswordRequest $request)
    {
        try {
            $userToken = $this->userTokenRepository->getUserTokenfromToken($request->reset_token);
            $this->userRepository->updateUserPassword($request->password, $userToken->user_id);
            Log::info(__METHOD__ . '...ID' . $userToken->user_id . 'のユーザーのパスワードを更新しました。');
        } catch (Exception $e) {
            Log::error(__METHOD__ . '...ユーザーのパスワードの更新に失敗しました。...error_message = ' . $e);
            return to_route('password_reset.email.form')->with('error', '処理に失敗しました。時間をおいて再度お試しください。');
        }

//        更新時にセッションキーに値を保存
        $request->session()->put(self::UPDATE_PASSWORD_SESSION_KEY, 'update_password');

        return to_route('password_reset.edited');
    }
}
