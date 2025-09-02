<?php

    namespace App\Http\Controllers\Auth;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Password;
    use Illuminate\Support\Str;
    use Illuminate\Auth\Events\PasswordReset;

    class TeacherResetPasswordController extends Controller
    {
        public function showResetForm(Request $request, $token = null)
        {
            return view('auth.teacher.passwords.reset')->with(
                ['token' => $token, 'email' => $request->email]
            );
        }

        public function reset(Request $request)
        {
            $request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|confirmed|min:8',
            ]);

            $status = Password::broker('teachers')->reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => bcrypt($password)
                    ])->setRememberToken(Str::random(60));

                    $user->save();

                    event(new PasswordReset($user));
                }
            );

            return $status == Password::PASSWORD_RESET
                        ? redirect()->route('profesor.login')->with('status', __($status))
                        : back()->withErrors(['email' => [__($status)]]);
        }
    }
    