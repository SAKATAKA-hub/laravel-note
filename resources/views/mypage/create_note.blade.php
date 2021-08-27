<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>create</title>
</head>
<body>
    <h2>新規投稿</h2>
    <form method="POST" action="{{route('store_note')}}">
        @csrf
        <h4>user ID : {{sprintf('%04d',Auth::user()->id)}}　名前：{{Auth::user()->name}}さん</h4>
        <label>
            <h4>タイトル(※必須 100文字以内)</h4>
            <input type="text" name="title" value="{{old('title')}}" required>
            <p style="color:red">{{ $errors->has('title')? $errors->first('title'): '' }}</p>

        </label>
        <label>
            <h4>タグ(※必須 複数選択可)</h4>
            @foreach ($hashtags as $hashtag)
            <label><input type="checkbox" name="hashtags[]" value="{{$hashtag->hashtag}}">
                {{$hashtag->hashtag}}</label>
            @endforeach
            <input type="text" name="new_hashtags" placeholder="新しいタグの入力">
            <p style="color:red">{{ $errors->has('hashtags')? $errors->first('hashtags'): '' }}</p>
            <p style="color:red">{{ $errors->has('new_hashtags')? $errors->first('new_hashtags'): '' }}</p>
        </label>
        <label>
            <h4>イメージ画像</h4>
            <input type="file" name="main_image">
        </label>
        <label>
            <h4>イメージカラー</h4>
            <label>
                <input type="radio" name="main_color" value="red" checked>赤
            </label>
            <label>
                <input type="radio" name="main_color" value="blue">青
            </label>
            <label>
                <input type="radio" name="main_color" value="green">緑
            </label>
        </label>

        <h4>
            <button>編集画面へ進む</button>
        </h4>

    </form>

    <ul>
        <li><a href="{{route('mypage.list')}}">マイページ</a></li>
    </ul>

</body>
</html>
