<?php

    namespace App\Http\Controllers\Auth;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Password;

    class TeacherForgotPasswordController extends Controller
    {
        public function showLinkRequestForm()
        {
            return view('auth.teacher.passwords.email');
        }

        public function sendResetLinkEmail(Request $request)
        {
            $request->validate(['email' => 'required|email']);

            $response = Password::broker('teachers')->sendResetLink(
                $request->only('email')
            );

            return $response == Password::RESET_LINK_SENT
                        ? back()->with('status', trans($response))
                        : back()->withErrors(['email' => trans($response)]);
        }
    }
    