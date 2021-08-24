<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\RegisterFormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    # ログイン画面の表示(login_form)
    public function login_form()
    {
        return view('login.login_form');
    }


    # ログイン処理(login)
    public function login(LoginFormRequest $request)
    {
        // ログイン成功の処理
        $credentials = $request->only('email','password');

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            return redirect('home')->with('login_success','ログインに成功しました！');
        }

        // ログイン失敗の処理
        return back()->with([
            'login_error' => 'メールアドレスかパスワードが間違っています。',
        ]);

        return back()->withErrors([
            'login_error' => 'メールアドレスかパスワードが間違っています。',
        ]);
    }




    # ユーザー登録画面の表示(get_register)
    public function get_register()
    {
        return view('login.register_form');
    }


    # ユーザー登録処理(post_register)
    public function post_register(RegisterFormRequest $request)
    {
        // ユーザー情報の保存
        $user = new \App\Models\User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->save();

        // ログイン処理
        $credentials = $request->only('email','password');

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            return redirect('home')->with('login_success','新規登録に成功しました！');
        }

        return redirect('home')->with('login_success','ログインに失敗しました。');
    }
}
