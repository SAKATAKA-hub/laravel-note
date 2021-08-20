<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;


# 一覧ページ表示
Route::get('/',[NoteController::class,'index'])
->name('notes.index');

# ノートの表示(show)

# 印刷ページ(print)

# 新規投稿ページ(create)
Route::get('/notes/create',[NoteController::class,'create'])
->name('notes.create');

# 新規投稿(store)
Route::post('/notes/store',[NoteController::class,'store'])
->name('notes.store');

# 投稿編集ページ(edit)
Route::get('/notes/edit/{note}',[NoteController::class,'edit'])
->name('notes.edit');

# 編集内容の保存(update)
# 投稿削除(destroy_note)
# 投稿部品の削除(destroy_parts)

# ログイン
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
->name('home');
