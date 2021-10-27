<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\RegisterFormRequest;
use App\Http\Requests\EditRegisterFormRequest;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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

        if (Auth::attempt($credentials)) { //ログイン成功のチェック

            $user = Auth::user()->id;

            $request->session()->regenerate(); //ユーザー情報をセッションに保存


            return redirect()->route('mypage_top',$user)->with('login_alert','ログイン成功');
        }

        // ログイン失敗の処理
        return back()->with('login_error','メールアドレスかパスワードが違います。');

    }




    # ログアウト処理(logout)
    public function logout(Request $request)
    {
        $user = Auth::user();

        Auth::logout(); //ユーザーセッションの削除

        $request->session()->invalidate(); //全セッションの削除

        $request->session()->regenerateToken(); //セッションの再作成(二重送信の防止)


        return redirect()->route('mypage_top',$user)->with('logout_alert',$user->name);
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
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->save();

        // ログイン処理
        $credentials = $request->only('email','password');

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            return redirect()->route('mypage_top',$user)->with('register_alert','store');
        }

        return redirect()->route('mypage_top',$user)->with('error_alert','ログインに失敗しました。');
    }




    # ユーザー情報の変更ページの表示(edit_register)
    public function edit_register()
    {
        $user = Auth::user();

        return view('user.edit_register',compact('user'));
    }





    # ユーザー情報の更新(update_register)
    public function update_register(EditRegisterFormRequest $request){

        # 保存データ(パスワード変更処理の時は、空配列になる)
        $save_data = $request->only('name','email','comment');


        # パスワードの変更処理
        if($request->password)
        {
            $save_data['password'] = Hash::make($request->password);
        }


        # 画像アップロード処理
        if($request->file('image')) //ファイルの添付があれば、アップロード
        {
            $save_data['image'] = $this::uploadImage($request); //画像のパスを'image'カラムに保存
        }
        elseif($request->old_image) //アップ―ド画像に変更が無ければ、画像パスを更新しない。
        {
            $save_data['image'] = $request->old_image;
        }


        # ユーザー情報の更新
        User::find($request->user_id)->update($save_data);



        return redirect()->route('mypage_top',$mypage_master = Auth::user()->id)
        ->with('register_alert','update');

    }




    # ユーザー情報の削除(destroy_register)
    public function destroy_register(Request $request)
    {
        # 削除するユーザーの名前
        $user_name = Auth::user()->name;

        # ログアウト処理
        Auth::logout(); //ユーザーセッションの削除
        $request->session()->invalidate(); //全セッションの削除
        $request->session()->regenerateToken(); //セッションの再作成(二重送信の防止)

        # ユーザー情報の削除
        // $user->delete();
        User::find($request->user_id)->delete();



        return redirect()->route('mypage_top',$mypage_master = 1) //ユーザー１のマイページへリダイレクト
        ->with('destroy_register_alert',$user_name);
    }








    /*
    |
    |　コントローラー内で利用するメソッド
    |
    |
    */

    /**
     * 画像のアップロード(uploadImage)
     *
     *
     * @param \Illuminate\Http\EditTextboxFormRequest $request
     * @return String $image_path; //アプロードした画像のパスを返す
     */
    public function uploadImage($request)
    {
        $upload_image = $request->file('image');

        $dir = 'upload/user_img'; //アップロード先ディレクトリ名

        $extension = $upload_image->extension(); //拡張子

        $file_name = sprintf('%04d', Auth::user()->id).'.'.$extension; //ファイル名

        $image_path = $upload_image->storeAs($dir,$file_name); //画像のアップロード


        return $image_path;

    }

}
