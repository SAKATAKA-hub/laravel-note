<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\EditNoteController;
use App\Http\Controllers\EditTextboxController;

use App\Http\Middleware\CheckMypageMaster;


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

// ログイン前は表示不可
Route::middleware(['auth'])->group(function () {
    # ホームページの表示
    Route::get('login_home',function() {
        return view('login.home');
    })->name('login_home');

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


// Route::get('edit_note',function(){
//     return view('notes.edit_note',['title'=>'マイノート編集(TOP)']);
// })
// ->name('edit_note');


Route::get('edit_note_title',function(){
    return view('notes.edit_note_title');
});


Route::get('edit_textbox',function(){
    return view('edit.textbox');
});


// Route::get('test/mypage_master={mypage_master}/{seach_key?}',[NotesController::class,'test'])
// ->name('test');



/*
| --------------------------------------------------------
| 閲覧ページの処理 (NotesController)
| --------------------------------------------------------
*/

# ホームページの表示(home)
Route::get('home',function(){
    return '<h1>Home(準備中)</h1>';
})->name('home');

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




/*
| --------------------------------------------------------
| ノート編集ページの処理 (EditNoteController)
| --------------------------------------------------------
*/
# ノート編集ページの表示(edit_note)
Route::get('/edit_note/{note}',[EditNoteController::class,'edit_note'])
->name('edit_note')
;
// ->middleware('check_mypage_master');


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




# ノートの削除(destroy_note)
Route::delete('/destroy_note',[EditNoteController::class,'destroy_note'])
->name('destroy_note');




/*
| --------------------------------------------------------
| テキストボックス編集ページの処理 (EditTextboxController)
| --------------------------------------------------------
*/
# テキストボックス新規作成ページの表示(create_textbox)
Route::get('/create_textbox/{note}/{order}',[EditTextboxController::class,'create_textbox'])
->name('create_textbox');

# 新規作成テキストボックスの保存(store_textbox)
Route::post('/store_textbox/{note}',[EditTextboxController::class,'store_textbox'])
->name('store_textbox');




# テキストボックス編集ページの表示(edit_textbox)
Route::get('/edit_textbox/{textbox}',[EditTextboxController::class,'edit_textbox'])
->name('edit_textbox');

# テキストボックスの更新(update_textbox)
Route::patch('/update_textbox/{edit_textbox}',[EditTextboxController::class,'update_textbox'])
->name('update_textbox');




# テキストボックスの削除(destroy_textbox)
Route::delete('/destroy_textbox/{note}',[EditTextboxController::class,'destroy_textbox'])
->name('destroy_textbox');





