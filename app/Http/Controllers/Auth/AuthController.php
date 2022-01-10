<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Method;
use App\Http\Controllers\EditNoteController;

use Illuminate\Http\Request;
use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\RegisterFormRequest;
use App\Http\Requests\EditRegisterFormRequest;

use App\Models\Note;
use App\Models\Textbox;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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

        return view('login.edit_register',compact('user'));
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
        $save_data = $this::uploadUserImage($request, $save_data);

        # ユーザー情報の更新
        User::find($request->user_id)->update($save_data);



        return redirect()->route('mypage_top',$mypage_master = Auth::user()->id)
        ->with('register_alert','update');

    }




    # ユーザー情報の削除(destroy_register)
    public function destroy_register(Request $request)
    {

        # ログアウト処理
        Auth::logout(); //ユーザーセッションの削除
        $request->session()->invalidate(); //全セッションの削除
        $request->session()->regenerateToken(); //セッションの再作成(二重送信の防止)


        # 削除するユーザー
        $delete_user = User::find($request->user_id);


        # アラート表示する、削除ユーザーの名前
        $user_name = $delete_user->name;


        # ストレージからユーザー画像を削除
        Storage::delete($delete_user->image);


        # ストレージからユーザーがアップロードしたファイルを削除
        $this::deleteUserUplordFiles($delete_user);


        # ユーザー情報の削除
        $delete_user->delete();


        return redirect()->route('home')
        ->with('destroy_register_alert',$user_name);
    }




    # 簡単ユーザー登録(easy_post_register)
    public function easy_post_register(Request $request)
    {


        // ユーザー情報の保存
        $user = new User([
            'name' => '簡単ユーザー登録ゲスト',
            'email' => sprintf("%08d", mt_rand(1,99999999)).'@email.co.jp',
            'password' => Hash::make('password'),
            'easy_user' => 1,
            'comment' => <<<_comment_
                簡単ユーザー登録で登録したゲストさんです。
                アカウントの利用期限は24時間です。

                メールアドレス：プロフィール変更より確認
                パスワード：password
            _comment_,
        ]);
        $user->save();


        // ログアウト処理
        Auth::logout(); //ユーザーセッションの削除
        $request->session()->invalidate(); //全セッションの削除
        $request->session()->regenerateToken(); //セッションの再作成(二重送信の防止)


        // ログイン処理
        $credentials = [
            'email' => $user->email ,
            'password' => 'password',
        ];

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            return redirect()->route('mypage_top',$user)->with('register_alert','store');
        }

        return redirect()->route('mypage_top',$user)->with('error_alert','ログインに失敗しました。');
    }







    /*
    |
    |　コントローラー内で利用するメソッド
    |
    |
    */

    /**
     * ストレージにユーザー画像をアップロード(uploadUserImage)
     *
     *
     * @param \Illuminate\Illuminate\Http\Request $request
     * @param Array $save_data
     * @return Array $save_data
     */
    public function uploadUserImage($request,$save_data)
    {
        if($request->file('image')) //ファイルの添付があれば、アップロード
        {
            $upload_image = $request->file('image'); //保存画像
            $dir = 'upload/user'; //アップロード先ディレクトリ名

            # 画像のアップロード
            $save_data['image'] = $upload_image->store($dir);

            # 古いアップロード画像の削除
            Storage::delete($request->old_image);
        }


        return $save_data;
    }




    /**
     * ストレージからユーザーが投稿した画像を削除(deleteUserUplordFiles)
     *
     *
     * @param $delete_user_id (削除するユーザーIDID)
     */
    public function deleteUserUplordFiles($delete_user)
    {
        # 削除ユーザーに紐づく、投稿ノートを取得
        $notes = Note::where('user_id',$delete_user->id)->get();
        foreach ($notes as $note)
        {
            # 投稿ノートに紐づく、テキストボックスを取得
            $textboxes = Textbox::where('note_id',$note->id)->get();
            foreach ($textboxes as $textbox)
            {

                // ストレージ保存のテキストファイルを削除
                Method::deleteTextFile($textbox);

                // ストレージ保存の画像ファイルを削除
                Method::deleteImageFile($textbox);

            }

        }
    }



}
