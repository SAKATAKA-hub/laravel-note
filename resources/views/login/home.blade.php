<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ログイン後画面</title>

</head>
<body>
    @if (session('login_success'))
        <div> {{session('login_success')}} </div>
    @endif

    @if ( Auth::check() )
        <h3>プロフィール</h3>
        <ul>
            <li>名前：{{Auth::user()->name}}</li>
            <li>メールアドレス：{{Auth::user()->email}}</li>
        </ul>
    @else
        <div>ログインしていません。</div>
    @endif

</body>
</html>
