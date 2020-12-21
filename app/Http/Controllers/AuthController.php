<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        if (Auth::user()) return redirect()->route('dashboard');

        if ($request->getMethod() === 'POST') {
            $messages = [
                'name.min' => 'Имя не может быть короче 2 символов',
                'name.max' => 'Имя не может быть больше 100 символов',
                'email.unique' => 'Пользователь с таким e-mail уже существует',
                'password.min' => 'Пароль должен содержать минимум 6 знаков',
                'password.max' => 'Пароль должен быть не больше 10 символов',
            ];
            $this->validate($request, [
                'name' => 'required|string|min:2|max:100',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6|max:10',
            ], $messages);

            try {
                $user = new User();
                $user->role = User::ROLE_USER;
                $user->name = $request->input('name');
                $user->email = $request->input('email');
                $user->password = password_hash($request->input('password'), PASSWORD_DEFAULT);

                if (!$user->save()) throw new \Exception('Failed to store user');

                Auth::login($user);

                return redirect()->route('dashboard');
            } catch (\Exception $e) {
                $errors = [$e->getMessage()];
            }
        }

        return view('auth/register', [
            'errors' => $errors ?? []
        ]);
    }

    public function login(Request $request)
    {
        if (Auth::user()) return redirect()->route('dashboard');

        if ($request->getMethod() === 'POST') {
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required|string|min:6|:max:10',
            ]);

            try {
                $email = $request->input('email');
                $password = $request->input('password');
                $user = User::where('email', $email)->first();

                if (!$user) throw new \Exception('User not found', 1);
                if (!password_verify($password, $user->password)) throw new \Exception('Wrong password', 2);

                Auth::login($user);

                return redirect()->route('dashboard');
            } catch (\Exception $e) {
                $messages = [
                    1 => 'Пользователь не существует',
                    2 => 'Неверный пароль'
                ];
                $error = $messages[$e->getCode()] ?? 'Произошла ошибка';
                $errors = [$error];
            }
        }

        return view('auth/login', [
            'errors' => $errors ?? []
        ]);
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('home');
    }
}
