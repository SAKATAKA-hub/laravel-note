<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


use App\Models\User;
use App\Models\Note;


class AppAdminController extends Controller
{
    /**
     * アプリケーション管理者ページの表示(app_admin.top)
     *
     *
     * @return \Illuminate\View\View
     */
    public function top()
    {
        $users = User::get();

        return view('app_admin.top',compact('users'));
    }








    /**
     * パスワードのリセット(app_admin.reset_password)
     *
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function reset_password(Request $request)
    {
        # ユーザー情報
        $user = User::find($request->user_id);

        # パスワードを'password'に変更
        $user->update([
            'password' => Hash::make('password'),
        ]);


        # APP管理者ページTOPへリダイレクト
        return redirect()->route('app_admin.top')
        ->with('app_admin.reset_password.alert',$user->name);
    }




    /**
     * ユーザー投稿の削除(app_admin.destroy_notes)
     *
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function destroy_notes(Request $request)
    {
        # ユーザー情報
        $user = User::find($request->user_id);


        # ユーザー投稿をすべて削除
        $notes = Note::where('user_id',$user->id)->get();

        for ($i=0; $i < count($notes); $i++) {
            $notes[$i]->delete();
        }


        # APP管理者ページTOPへリダイレクト
        return redirect()->route('app_admin.top')
        ->with('app_admin.destroy_notes.alert',$user->name);
    }




    /**
     * ユーザー登録の削除(app_admin.destroy_user)
     *
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function destroy_user(Request $request)
    {
        # ユーザー情報
        $user = User::find($request->user_id);


        # 管理者ページ閲覧中ユーザーの削除は不可
        if(Auth::user()->id === $user->id)
        {
            return redirect()->route('app_admin.top')
            ->with('error_alert','管理者ページ閲覧中ユーザーのユーザー情報を削除することはできません。');
        }


        # ユーザー情報の削除
        $user->delete();


        # APP管理者ページTOPへリダイレクト
        return redirect()->route('app_admin.top')
        ->with('app_admin.destroy_user.alert',$user->name);
    }


    // ----------------------------------------------
    // AWS s3のファイル操作
    // ----------------------------------------------

    /**
     * ファイル編集ページの表示(edit_file)
     *
     *
     * @return \Illuminate\View\View
     */
    public function edit_file()
    {
        $mode = 'edit_file';
        return view('app_admin.s3.edit_file',compact('mode'));
    }




    /**
     * ファイルの表示(show_file)
     *
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function show_file(Request $request)
    {
        $mode = 'show_file';
        $path = $request->path;
        $url = Storage::disk('s3')->url($path);

        return view( 'app_admin.s3.edit_file',compact('mode','url','path') );
    }




    /**
     * ファイルの保存(upload_file)
     *
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function upload_file(Request $request)
    {
        $mode = 'upload_file';

        # ファイルのアップロード
        $file = $request->file('file');
        $dir = '/'.$request->dir;
        $path = Storage::disk('s3')->putFile($dir, $file, 'public');

        $url = Storage::disk('s3')->url($path);

        return view( 'app_admin.s3.edit_file',compact('mode','url','path') );
    }


    /**
     * ファイルの削除(delete_file)
     *
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function delete_file(Request $request)
    {
        $mode = 'delete_file';
        $path = $request->path;


        # ファイルが存在するか確認
        $text = '指定したファイルは存在しません。';
        if ( Storage::disk('s3')->exists($path) )
        {
            Storage::disk('s3')->delete($path);
            $text = 'ファイルを1件削除しました。';
        }
        return view( 'app_admin.s3.edit_file',compact('mode','text') );
    }


}
