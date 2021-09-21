<?php

# マイページの表示(list)
Breadcrumbs::for('list', function ($trail,$mypage_master) {

    $trail->push(
        $mypage_master->name.'さんのマイページ',
        route('list',$mypage_master)
    );

});


# ノート閲覧ページの表示(show)
Breadcrumbs::for('show', function ($trail, $mypage_master, $note) {

    $trail->parent('list',$mypage_master);
    $trail->push(
        $note->title,
        route('show',$note)
    );
});







