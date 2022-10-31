<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PasswordController extends Controller
{
    public function emailFormResetPassword()
    {
        return view('reset_password.email_form');
    }
}
