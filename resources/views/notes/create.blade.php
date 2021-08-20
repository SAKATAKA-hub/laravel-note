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
    <form method="POST" action="{{route('notes.store')}}">
        @csrf
        <label>
            <h4>user ID</h4>
            <input type="text" name="user_id" value="1">
        </label>
        <label>
            <h4>タイトル</h4>
            <input type="text" name="title">
        </label>
        <label>
            <h4>ハッシュタグ</h4>
            <input type="text" name="hashtags">
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
</body>
</html>
