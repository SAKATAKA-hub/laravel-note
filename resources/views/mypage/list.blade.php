<h1>マイページTOP</h1>
@if ( Auth::check() )
    <h2>{{Auth::user()->name}}さんのマイページ</h2>
    <h4>user ID : {{sprintf('%04d',Auth::user()->id)}}</h4>

    <form method="POST" action="{{route('logout')}}">
        @csrf
        <button>ログアウト</button>
    </form>
@else
    <ul>
        <li>こんにちはゲストさん(ログインしていません)</li>
        <li><a href="{{route('login_form')}}">ログイン</a></li>
    </ul>
@endif
<ul>
    <li><a href="{{route('create_note')}}">新規作成</a></li>
</ul>
