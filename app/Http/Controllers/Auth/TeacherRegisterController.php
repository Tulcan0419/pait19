<?php

    namespace App\Http\Controllers\Auth;

    use App\Http\Controllers\Controller;
    use App\Models\Teacher;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;

    class TeacherRegisterController extends Controller
    {
        public function showRegistrationForm()
        {
            return view('auth.teacher.register');
        }

        public function register(Request $request)
        {
            $this->validator($request->all())->validate();

            $teacher = $this->create($request->all());

            Auth::guard('teacher')->login($teacher);

            return redirect()->route('profesor.dashboard');
        }

        protected function validator(array $data)
        {
            return Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:teachers'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'teacher_code' => ['nullable', 'string', 'unique:teachers'],
            ]);
        }

        protected function create(array $data)
        {
            return Teacher::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'teacher_code' => $data['teacher_code'] ?? null,
            ]);
        }
    }
    