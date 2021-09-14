<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ログインフォーム</title>

    <script src="{{ asset('js/app.js') }}" defer></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/signin.css') }}" rel="stylesheet">
</head>

<body class="text-center">

    <main class="form-signin">

        @csrf

        @if (session('login_error'))
            <div class="alert alert-denger">
                エラー：{{ session('login_error') }}
            </div>
        @endif

        <h1 class="h3 mb-3 fw-normal">ログイン</h1>
        <label for="inputEmail" class="visually-hidden" >メールアドレス</label>
        <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputPassword" class="visually-hidden">パスワード</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <button class="w-100 btn btn-lg btn-primary" type="submit">ログイン</button>
      </form>

      <a href="{{ route('get_register') }}">無料会員登録はこちら</a>
    </main>

  </body>
</html>
