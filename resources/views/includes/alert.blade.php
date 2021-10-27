<!--
*---------------------------------------------
* ログイン処理関係のアラート
*---------------------------------------------
-->
<!-- login -->
@if ( session('login_alert') )
<div class="alert alert-info alert-dismissible fade show fs-5" role="alert">　<!-- alert-info -->
    {{Auth::user()->name}}さんが、<strong>ログイン</strong>しました。
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif


<!-- logout -->
@if ( session('logout_alert') )
<div class="alert alert-warning alert-dismissible fade show fs-5" role="alert"> <!-- alert-warning -->
    {{session('logout_alert')}}さんが、<strong>ログアウト</strong>しました。
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- ユーザー登録操作処理のアラート --}}
<!-- ユーザー登録 -->
@if ( session('register_alert')==='store' )
<div class="alert alert-success alert-dismissible fade show fs-5" role="alert"> <!-- alert-success -->
    {{Auth::user()->name}}さんの<strong>新規登録が完了</strong>しました。
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- ユーザー情報更新 -->
@if ( session('register_alert')==='update' )
<div class="alert alert-info alert-dismissible fade show fs-5" role="alert"> <!-- alert-info -->
    {{Auth::user()->name}}さんの<strong>登録内容を更新</strong>しました。
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- ユーザー情報削除 -->
@if ( session('destroy_register_alert') )
<div class="alert alert-warning alert-dismissible fade show fs-5" role="alert"> <!-- alert-warning -->
    {{session('destroy_register_alert')}}さんの<strong>登録内容を全て削除</strong>しました。
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif




<!-- error -->
@if ( session('error_alert') )
<div class="alert alert-danger alert-dismissible fade show fs-5" role="alert"> <!-- alert-danger -->
    エラー：{{session('error_alert')}}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif




<!--
*---------------------------------------------
* ノート・テキストボックス操作処理のアラート
*---------------------------------------------
-->

<!-- store_note_title -->
@if ( session('note_alert') === 'store_note_title' )
<div class="alert alert-success alert-dismissible fade show fs-5" role="alert"> <!-- alert-success -->
    新しいノートの基本情報を登録しました！<br>続けて”テキストボックス”の挿入を行ってください。
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif


<!-- update_note_title -->
@if ( session('note_alert') === 'update_note_title' )
<div class="alert alert-info alert-dismissible fade show fs-5" role="alert"> <!-- alert-info -->
    ノートの基本情報の編集内容を登録しました。
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif


<!-- destroy_note -->
@if ( session('note_alert') === 'destroy_note_alert' )
<div class="alert alert-warning alert-dismissible fade show fs-5" role="alert"> <!-- alert-warning -->
    ノートを１件削除しました。
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif




<!-- store_textbox -->
@if ( session('note_alert') === 'store_textbox' )
<div class="alert alert-success alert-dismissible fade show fs-5" role="alert"> <!-- alert-success -->
    新しいテキストボックスを追加しました。
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif


<!-- update_textbox -->
@if ( session('note_alert') === 'update_textbox' )
<div class="alert alert-info alert-dismissible fade show fs-5" role="alert"> <!-- alert-info -->
    テキストボックスの編集を登録しました。
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif



<!-- destroy_textbox -->
@if ( session('note_alert') === 'destroy_textbox' )
<div class="alert alert-warning alert-dismissible fade show fs-5" role="alert"> <!-- alert-warning -->
    テキストボックスを一つ削除しました。
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif




<!--
*---------------------------------------------
* アプリケーション管理者ページ処理のアラート
*---------------------------------------------
-->
<!-- パスワードのリセット -->
@if ( session('app_admin.reset_password.alert') )
<div class="alert alert-warning alert-dismissible fade show fs-5" role="alert"> <!-- alert-warning -->
    {{session('app_admin.reset_password.alert')}}さんのパスワードを初期値に設定いたしました。。
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- ユーザー投稿の削除 -->
@if ( session('app_admin.destroy_notes.alert') )
<div class="alert alert-danger alert-dismissible fade show fs-5" role="alert"> <!-- alert-danger -->
    {{session('app_admin.destroy_notes.alert')}}さんの全投稿ノートを削除しました。
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif



<!-- ユーザー登録の削除 -->
@if ( session('app_admin.destroy_user.alert') )
<div class="alert alert-danger alert-dismissible fade show fs-5" role="alert"> <!-- alert-danger -->
    {{session('app_admin.destroy_user.alert')}}さんのユーザー登録を削除しました。
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
