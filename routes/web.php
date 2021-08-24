<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\MypageController;



//--------------------------------------------------------
// ログイン認証
//--------------------------------------------------------
# ログイン画面の表示(login_form)
Route::get('/',[AuthController::class,'login_form'])
->name('login_form');


# ログイン処理(login)
Route::post('login',[AuthController::class,'login'])
->name('login');

# ログイン後のページの表示
Route::get('home',function() {
    return view('login.home');
})->name('login_home');


# ユーザー登録画面の表示(get_register)
Route::get('get_register',[AuthController::class,'get_register'])
->name('get_register');

# ユーザー登録処理(post_register)
Route::post('post_register',[AuthController::class,'post_register'])
->name('post_register');





# ログイン前の処理
Route::middleware(['guest'])->group(function () {
    //
});
# ログイン後の処理
Route::middleware(['auth'])->group(function () {
    //
});

//--------------------------------------------------------
// 閲覧ページの処理 (NotesController)
//--------------------------------------------------------

# ノート一覧ページの表示(list)
Route::get('/notes/list/{seach_keys?}',[NotesController::class,'list'])
->name('notes.list');

# ノートページの表示(show_note)
Route::get('/notes/show_note/{note}/{seach_keys?}',[NotesController::class,'show_note'])
->name('notes.show_note');

# 投稿者別ノート一覧ページの表示(user_specific_list)
Route::get('/notes/user_specific_list/{seach_keys?}',
[NotesController::class,'user_specific_list'])
->name('notes.user_specific_list');

# 投稿者別ノートページの表示(user_specific_note)
Route::get('/notes/user_specific_note/{note}/{seach_keys?}',
[NotesController::class,'user_specific_note'])
->name('notes.user_specific_note');

# ノートの印刷(print_note)
Route::get('/notes/print_note/{note}',[NotesController::class,'print_note'])
->name('notes.print_note');




//--------------------------------------------------------
// マイページの処理 (MypageController)
//--------------------------------------------------------

# ノート一覧ページの表示(list)
Route::get('/mypage/list/{seach_keys?}',[MypageController::class,'list'])
->name('mypage.list');




# ノートの新規作成ページの表示(create_note)
Route::get('/mypage/create_note',[MypageController::class,'create_note'])
->name('mypage.create_note');

# 新規作成ノートの保存(store_note)
Route::post('/mypage/store_note',[MypageController::class,'store_note'])
->name('mypage.store_note');

# ノート編集ページの表示(edit_note)
Route::get('/mypage/edit_note/{note}',[MypageController::class,'edit_note'])
->name('mypage.edit_note');

# 編集ノートの保存(update_note)
Route::patch('/mypage/update_note/{note}',[MypageController::class,'update_note'])
->name('mypage.update_note');

# ノートの削除(destroy_note)
Route::delete('/mypage/destroy_note/{note}',[MypageController::class,'destroy_note'])
->name('mypage.destroy_note');




# 新規作成ノート部品の保存(store_note_part)
Route::post('/mypage/store_note_part',[MypageController::class,'store_note_part'])
->name('mypage.store_note_part');

# ノート部品編集ページの表示(edit_note_part)
Route::get('/mypage/edit_note_part/{note}',[MypageController::class,'edit_note_part'])
->name('mypage.edit_note_part');

# 編集ノート部品の保存(update_note_part)
Route::patch('/mypage/update_note_part/{note}',[MypageController::class,'update_note_part'])
->name('mypage.update_note_part');

# ノート部品の削除(destroy_note_part)
Route::delete('/mypage/destroy_note_part/{note}',[MypageController::class,'destroy_note_part'])
->name('mypage.destroy_note_part');
