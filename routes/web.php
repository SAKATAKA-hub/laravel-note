<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\MypageController;




//--------------------------------------------------------
// ログイン認証
//--------------------------------------------------------
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

// ログイン前は表示不可
Route::middleware(['auth'])->group(function () {
    # ホームページの表示
    Route::get('home',function() {
        return view('login.home');
    })->name('home');

});


//--------------------------------------------------------
// 閲覧ページの処理 (NotesController)
//--------------------------------------------------------
Route::get('base',function(){
    return view('layouts.base');
});


Route::get('list',function(){
    return view('notes.list');
});



Route::get('show',function(){
    return view('notes.show');
});


Route::get('edit_note',function(){
    return view('notes.edit_note');
});


Route::get('edit_note_title',function(){
    return view('notes.edit_note_title');
});


Route::get('edit_textbox',function(){
    return view('notes.edit_textbox');
});




//--------------------------------------------------------
// マイページの処理 (MypageController)
//--------------------------------------------------------

# ノート一覧ページの表示(list)
Route::get('/mypage/list/user={user}/{seach_keys?}',[MypageController::class,'list'])
->name('mypage.list');

# ノートページの表示(show_note)
Route::get('/mypage/show_note/note={note}/{seach_keys?}',[MypageController::class,'show_note'])
->name('show_note');

# ノートの新規作成ページの表示(create_note)
Route::get('/mypage/create_note/user={user}',[MypageController::class,'create_note'])
->name('create_note');

# 新規作成ノートの保存(store_note)
Route::post('/mypage/store_note',[MypageController::class,'store_note'])
->name('store_note');

# ノートの削除(destroy_note)
Route::delete('/mypage/destroy_note/note={note}',[MypageController::class,'destroy_note'])
->name('destroy_note');

# ノート編集ページの表示(edit_note)
Route::get('/mypage/edit_note/note={note}',[MypageController::class,'edit_note'])
->name('edit_note');

# ノート基本情報の更新(update_note)
Route::patch('/mypage/update_note/note={note}',[MypageController::class,'update_note'])
->name('update_note');





# 新規作成ノート部品の保存(store_note_part)
Route::post('/mypage/store_note_part',[MypageController::class,'store_note_part'])
->name('store_note_part');

# ノート部品編集ページの表示(edit_note_part)
Route::get('/mypage/edit_note_part/{note}',[MypageController::class,'edit_note_part'])
->name('edit_note_part');

# 編集ノート部品の保存(update_note_part)
Route::patch('/mypage/update_note_part/{note}',[MypageController::class,'update_note_part'])
->name('update_note_part');

# ノート部品の削除(destroy_note_part)
Route::delete('/mypage/destroy_note_part/{note}',[MypageController::class,'destroy_note_part'])
->name('destroy_note_part');

