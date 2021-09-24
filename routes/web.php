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
// 閲覧ページの処理 (viewの表示)
//--------------------------------------------------------
Route::get('base',function(){
    return view('layouts.base');
});


Route::get('list',function(){
    return view('notes.list');
});



// Route::get('show',function(){
//     return view('notes.show');
// });


Route::get('edit_note',function(){
    return view('notes.edit_note',['title'=>'マイノート編集(TOP)']);
})
->name('edit_note');


Route::get('edit_note_title',function(){
    return view('notes.edit_note_title');
});


Route::get('edit_textbox',function(){
    return view('notes.edit_textbox');
});


// Route::get('test/mypage_master={mypage_master}/{seach_key?}',[NotesController::class,'test'])
// ->name('test');

Route::get('test/mypage_master={mypage_master}',[NotesController::class,'test'])
->name('test');


//--------------------------------------------------------
// 閲覧ページの処理 (NotesController)

//--------------------------------------------------------
# マイページの表示(list)
Route::get('/list/mypage_master={mypage_master}',[NotesController::class,'list'])
->name('list');

# マイページの検索表示(seach_list)
Route::get('/seach_list/mypage_master={mypage_master}/{list_type}/{seach_value?}',[NotesController::class,'seach_list'])
->name('seach_list');

# ノート閲覧ページの表示(show)
Route::get('/show/note={note}',[NotesController::class,'show'])
->name('show');


//--------------------------------------------------------
// マイページの処理 (MypageController)
//--------------------------------------------------------

# ノート一覧ページの表示(list)
Route::get('/mypages/list/user={user}/{seach_keys?}',[MypageController::class,'list'])
->name('mypage.list');

# ノートページの表示(show_note)
Route::get('/mypages/show_note/note={note}/{seach_keys?}',[MypageController::class,'show_note'])
->name('show_note');

# ノートの新規作成ページの表示(create_note)
Route::get('/mypages/create_note/user={user}',[MypageController::class,'create_note'])
->name('create_note');

# 新規作成ノートの保存(store_note)
Route::post('/mypages/store_note',[MypageController::class,'store_note'])
->name('store_note');

# ノートの削除(destroy_note)
Route::delete('/mypages/destroy_note/note={note}',[MypageController::class,'destroy_note'])
->name('destroy_note');

# ノート編集ページの表示(edit_note)
Route::get('/mypages/edit_note/note={note}',[MypageController::class,'edit_note'])
->name('edit_note');

# ノート基本情報の更新(update_note)
Route::patch('/mypages/update_note/note={note}',[MypageController::class,'update_note'])
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

