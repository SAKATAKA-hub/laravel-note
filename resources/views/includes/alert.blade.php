{{-- <div class="alert alert-info alert-dismissible fade show fs-5" role="alert">
    さんが、<strong>ログイン</strong>しました。
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div> --}}


<!-- login -->
@if ( session('login_alert') )
<div class="alert alert-info alert-dismissible fade show fs-5" role="alert">
    {{Auth::user()->name}}さんが、<strong>ログイン</strong>しました。
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif


<!-- logout -->
@if ( session('logout_alert') )
<div class="alert alert-warning alert-dismissible fade show fs-5" role="alert">
    {{session('logout_alert')}}さんが、<strong>ログアウト</strong>しました。
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif


<!-- register -->
@if ( session('register_alert') )
<div class="alert alert-success alert-dismissible fade show fs-5" role="alert">
    {{Auth::user()->name}}さんの<strong>新規登録が完了</strong>しました。
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif


<!-- error -->
@if ( session('error_alert') )
<div class="alert alert-danger alert-dismissible fade show fs-5" role="alert">
    エラー：{{session('error_alert')}}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif


<!-- destroy_note -->
@if ( session('destroy_note_alert') )
<div class="alert alert-warning alert-dismissible fade show fs-5" role="alert">
    {{session('destroy_note_alert')}}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
