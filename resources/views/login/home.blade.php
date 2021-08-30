<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ホーム画面</title>

</head>
<body>
    @if (session('login_success'))
        <div> {{session('login_success')}} </div>
    @endif
    <h2>ホーム</h2>

    <!-- ログイン中の時 -->
    @if ( Auth::check() )
        <h3>プロフィール</h3>
        <ul>
            <li>名前：{{Auth::user()->name}}</li>
            <li>メールアドレス：{{Auth::user()->email}}</li>
        </ul>

        <a href="{{route('mypage.list',Auth::user()->id)}}">マイページ</a>

        <form method="POST" action="{{route('logout')}}">
            @csrf
            <button>ログアウト</button>
        </form>

    <!-- ログアウト中の時 -->
    @else
        <ul>
            <li>こんにちはゲストさん(ログインしていません)</li>
            <li><a href="{{route('login_form')}}">ログイン</a></li>
        </ul>
    @endif

</body>
</html>
