<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\NoteEditerController;

use App\Http\Controllers\EditNoteController;
use App\Http\Controllers\EditTextboxController;
use App\Http\Controllers\AppAdminController;
use App\Http\Controllers\TestController;



/*
| --------------------------------------------------------
| ログイン認証
| --------------------------------------------------------
*/

# ログイン画面の表示(login_form)
Route::get('login_form',[AuthController::class,'login_form'])
->name('login_form');

# ログイン処理(login)
Route::post('login',[AuthController::class,'login'])
->name('login');

# ログアウト処理(logout)
Route::post('logout',[AuthController::class,'logout'])
->name('logout');



# ユーザー登録画面の表示(get_register)
Route::get('get_register',[AuthController::class,'get_register'])
->name('get_register');

# ユーザー登録処理(post_register)
Route::post('post_register',[AuthController::class,'post_register'])
->name('post_register');


# 簡単ユーザー登録(easy_post_register)
Route::post('easy_post_register',[AuthController::class,'easy_post_register'])
->name('easy_post_register');






Route::middleware(['auth'])->group(function () //ログインしていなければ、ログインページへリダイレクトする
{
    # ユーザー情報変更ページの表示(edit_register)
    Route::get('edit_register',[AuthController::class,'edit_register'])
    ->name('edit_register');

    # ユーザー情報の更新(update_register)
    Route::patch('update_register',[AuthController::class,'update_register'])
    ->name('update_register');

    # ユーザー情報の削除(destroy_register)
    Route::delete('destroy_register',[AuthController::class,'destroy_register'])
    ->name('destroy_register');

});




/*
| --------------------------------------------------------
| 閲覧ページの処理 (NotesController)
| --------------------------------------------------------
*/
Route::middleware(['delete_easy_user'])->group(function ()
//期限切れ簡単ログインユーザーの削除
{

    # ホームページの表示(home)
    Route::get('/',[NotesController::class,'home'])
    ->middleware(['delete_easy_user']) //期限切れ簡単ログインユーザーの削除
    ->name('home');




    # マイページの表示(mypage_top)
    Route::get('/mypage_top/{mypage_master}',[NotesController::class,'mypage_top'])
    ->name('mypage_top');

    # マイページの検索表示(mypage_seach)
    Route::get('/mypage_seach/{mypage_master}',[NotesController::class,'mypage_seach'])
    ->name('mypage_seach');


    # ノート閲覧ページの表示(note)
    Route::get('/note/{note}',[NotesController::class,'note'])
    ->name('note');

    # ノート印刷ページの表示(print)
    Route::get('/print/{note}',[NotesController::class,'print'])
    ->name('print');

});




/*
| --------------------------------------------------------
| 非同期通信を含むノート編集ページの処理 (NoteediterController)
| --------------------------------------------------------
*/
//マイページ管理者ログイン時以外は閲覧不可
Route::middleware(['check_mypage_master'])->group(function ()
{

    # ノート新規作成ページの表示(create_note)
    Route::get('/create_note/{mypage_master}',[NoteEditerController::class,'create_note'])
    ->name('create_note');


    # 新規作成ノートの保存(post_note)
    Route::post('/post_note/{mypage_master}',[NoteEditerController::class,'post_note'])
    ->name('post_note');


    # ノート基本情報の更新(update_note)
    Route::patch('/update_note/{note}',[NoteEditerController::class,'update_note'])
    ->name('update_note');


    # ノートの削除(destroy_note)
    Route::delete('/destroy_note/{mypage_master}',[NoteEditerController::class,'destroy_note'])
    ->name('destroy_note');




    # ノート編集ページの表示(note_editer)
    Route::get('/note_editer/{note}',[NoteEditerController::class,'note_editer'])
    ->name('note_editer');


    # 編集用のノートのjsonデータを返す。(json_note)
    Route::post('/json_note/{mypage_master}/{note}',[NoteEditerController::class,'json_note'])
    ->name('json_note');


    # 新規作成テキストボックスの保存(ajax_store_textbox)
    Route::post('/ajax_store_textbox/{note}',[NoteEditerController::class,'ajax_store_textbox'])
    ->name('ajax_store_textbox');


    # テキストボックスの更新(ajax_update_textbox)
    Route::patch('/ajax_update_textbox/{note}',[NoteEditerController::class,'ajax_update_textbox'])
    ->name('ajax_update_textbox');


    # テキストボックスの削除(ajax_destroy_textbox)
    Route::delete('/ajax_destroy_textbox/{note}',[NoteEditerController::class,'ajax_destroy_textbox'])
    ->name('ajax_destroy_textbox');

});







/*
| --------------------------------------------------------
| 同期処理ノート編集ページの処理 (EditNoteController)
| --------------------------------------------------------
*/

//マイページ管理者ログイン時以外は閲覧不可
Route::middleware(['check_mypage_master'])->group(function ()
{

    # ノート編集ページの表示(edit_note)
    Route::get('/edit_note/{note}',[EditNoteController::class,'edit_note'])
    ->name('edit_note');


    # ノート新規作成ページの表示(create_note_title)
    Route::get('/create_note_title/{mypage_master}',[EditNoteController::class,'create_note_title'])
    ->name('create_note_title');


    # 新規作成ノートの保存(store_note_title)
    Route::post('/store_note_title/{mypage_master}',[EditNoteController::class,'store_note_title'])
    ->name('store_note_title');




    # ノート基本情報編集ページの表示(edit_note_title)
    Route::get('/edit_note_title/{note}',[EditNoteController::class,'edit_note_title'])
    ->name('edit_note_title');

    # ノート基本情報の更新(update_note_title)
    Route::patch('/update_note_title/{note}',[EditNoteController::class,'update_note_title'])
    ->name('update_note_title');




    # ノートの削除(destroy_note_title)
    Route::delete('/destroy_note_title/{mypage_master}',[EditNoteController::class,'destroy_note_title'])
    ->name('destroy_note_title');

});





/*
| --------------------------------------------------------
| 同期処理テキストボックス編集ページの処理 (EditTextboxController)
| --------------------------------------------------------
*/

//マイページ管理者ログイン時以外は表示不可
Route::middleware(['check_mypage_master'])->group(function ()
{

    # テキストボックス新規作成ページの表示(create_textbox)
    Route::get('/create_textbox/{note}/{order}',[EditTextboxController::class,'create_textbox'])
    ->name('create_textbox');

    # 新規作成テキストボックスの保存(store_textbox)
    Route::post('/store_textbox/{note}',[EditTextboxController::class,'store_textbox'])
    ->name('store_textbox');




    # テキストボックス編集ページの表示(edit_textbox)
    Route::get('/edit_textbox/{note}/{textbox}',[EditTextboxController::class,'edit_textbox'])
    ->name('edit_textbox');

    # テキストボックスの更新(update_textbox)
    Route::patch('/update_textbox/{note}/{edit_textbox}',[EditTextboxController::class,'update_textbox'])
    ->name('update_textbox');




    # テキストボックスの削除(destroy_textbox)
    Route::delete('/destroy_textbox/{note}',[EditTextboxController::class,'destroy_textbox'])
    ->name('destroy_textbox');

});





/*
| --------------------------------------------------------
| アプリケーション管理者ページの処理 (AppAdminController)
| --------------------------------------------------------
*/
//ログインユーザーにアプリケーション管理者権限が無ければ閲覧不可

Route::middleware(['check_app_dministrator'])->group(function ()
{

    # アプリケーション管理者ページの表示(app_admin.top)
    Route::get('app_admin.top',[AppAdminController::class,'top'])
    ->name('app_admin.top');


    # パスワードのリセット(reset_password)
    Route::patch('app_admin.reset_password',[AppAdminController::class,'reset_password'])
    ->name('app_admin.reset_password');


    # ユーザー投稿の削除(destroy_notes)
    Route::delete('app_admin.destroy_notes',[AppAdminController::class,'destroy_notes'])
    ->name('app_admin.destroy_notes');


    # ユーザー登録の削除(destroy_user)
    Route::delete('app_admin.destroy_user',[AppAdminController::class,'destroy_user'])
    ->name('app_admin.destroy_user');




    # s3のファイル操作
    // ファイル編集ページの表示(edit_file)
    Route::get('app_admin.s3.edit_file',[AppAdminController::class,'edit_file'])
    ->name('app_admin.s3.edit_file');


    // ファイルの表示(show_file)
    Route::post('app_admin.s3.show_file',[AppAdminController::class,'show_file'])
    ->name('app_admin.s3.show_file');


    // ファイルの保存(upload_file)
    Route::post('app_admin.s3.upload_file',[AppAdminController::class,'upload_file'])
    ->name('app_admin.s3.upload_file');


    // ファイルの削除(delete_file)
    Route::post('app_admin.s3.delete_file',[AppAdminController::class,'delete_file'])
    ->name('app_admin.s3.delete_file');


});




# test
Route::get('test',function(){
    return view('test.test');
})->name('test');

# API受取り
Route::post('api',[TestController::class,'api'])
->name('api');

# upload_test
Route::get('upload_test',function(){
    return 'upload test';
})->name('upload_test');


