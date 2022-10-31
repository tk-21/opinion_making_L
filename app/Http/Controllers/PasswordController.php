<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendEmailRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PasswordController extends Controller
{
    private $userRepository;
    private $userTokenRepository;

    private const MAIL_SENT_SESSION_KEY = 'user_reset_password_mail_sent_action';

    public function __construct(
        UserRepositoryInterface      $userRepository,
        UserTokenRepositoryInterface $userTokenRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->userTokenRepository = $userTokenRepository;
    }

    public function emailFormResetPassword()
    {
        return view('reset_password.email_form');
    }

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

        session()->put(self::MAIL_SENT_SESSION_KEY, 'user_reset_password_send_email');

        return to_route('password_reset.email.send_complete');
    }
}
