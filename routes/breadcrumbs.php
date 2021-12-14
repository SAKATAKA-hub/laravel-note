<?php


# ホームページの表示(home)
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home',route('home'));
});




# マイページの表示(mypage_top)
Breadcrumbs::for('mypage_top', function ($trail,$mypage_master) {
    $trail->parent('home',$mypage_master);
    $trail->push(
        $mypage_master->name.'さんのマイページ',
        route('mypage_top',$mypage_master)
    );
});


# マイページの検索表示(mypage_seach)
Breadcrumbs::for('mypage_seach', function ($trail,$mypage_master,$seach_heading) {
    $trail->parent('mypage_top',$mypage_master);
    $trail->push(
        $seach_heading,
        route('mypage_seach',$mypage_master)
    );
});


# ノート閲覧ページの表示(note)
Breadcrumbs::for('note', function ($trail,$mypage_master,$note) {
    $trail->parent('mypage_top',$mypage_master);
    $trail->push(
        $note->title,
        route( 'note',$note )
    );
});








# ノート新規作成ページの表示(create_note_title)
Breadcrumbs::for('create_note_title', function ($trail,$mypage_master) {
    $trail->parent('mypage_top',$mypage_master); //マイページの表示
    $trail->push(
        'ノートの新規作成',
        ''
    );
});









# ノート編集ページの表示(edit_note)
Breadcrumbs::for('edit_note', function ($trail,$mypage_master,$note) {
    $trail->parent('mypage_top',$mypage_master); //マイページの表示
    $trail->push(
        !$note? 'ノートの新規作成': '"'.$note->title.'"の編集',
        route('edit_note',$mypage_master)
    );
});









# ノート基本情報編集ページの表示(edit_note_title)
Breadcrumbs::for('edit_note_title', function ($trail,$mypage_master,$note) {
    $trail->parent('edit_note',$mypage_master,$note); //ノート編集ページの表示
    $trail->push(
        'ノートの基本情報編集',
        ''
    );
});




# テキストボックス新規作成ページの表示(create_textbox)
Breadcrumbs::for('create_textbox', function ($trail,$mypage_master,$note) {
    $trail->parent('edit_note',$mypage_master,$note); //ノート編集ページの表示
    $trail->push(
        'テキストボックスの新規挿入',
        ''
    );
});


# テキストボックス編集ページの表示(edit_textbox)
Breadcrumbs::for('edit_textbox', function ($trail,$mypage_master,$note) {
    $trail->parent('edit_note',$mypage_master,$note); //ノート編集ページの表示
    $trail->push(
        'テキストボックスの編集',
        ''
    );
});



