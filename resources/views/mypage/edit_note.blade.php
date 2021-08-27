<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>edit</title>
</head>
<body>
    <h2>投稿内容の編集</h2>
    <form method="POST" action="">
        @csrf
        <h3>見出し1</h3>
        <input type="hidden" name="note_part_name_id" value="1">
        <input type="text" name="text">
        <br>
        <button>文章部品の追加</button>
    </form>
    <form method="POST" action="">
        @csrf
        <h3>テキスト</h3>
        <input type="hidden" name="note_part_name_id" value="4">
        <textarea name="text" cols="30" rows="10"></textarea>
        <br>
        <button>文章部品の追加</button>
    </form>
    <form method="POST" action="">
        @csrf
        <h3>リンク文</h3>
        <input type="hidden" name="note_part_name_id" value="8">
        <input type="text" name="text" placeholder="テキスト">
        <br>
        <input type="text" name="url" placeholder="URL">
        <br>
        <button>文章部品の追加</button>
    </form>


</body>
</html>
