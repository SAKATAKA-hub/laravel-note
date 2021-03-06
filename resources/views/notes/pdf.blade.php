<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>print</title>


    <style>
        {{$css}}
    </style>

</head>
<body>
    <div class="pdf_note_container_{{$note->color}}"> <!-- (クラスからページカラーを指定できる) -->


        <div class="title_box">
            <p>{{$note->time_text}}</p>

             <!-- (タイトル) -->
            <h3 class="title">{{$note->title}}</h3>

            <p>タグ：
                @foreach ($note->tags_array as $tag)
                <span>{{$tag}}</span>
                @endforeach
            </p>
        </div>

        <br><br>


        @foreach ($note->textboxes as $textbox)

            @include('includes.main_container.textbox_cases')

        @endforeach

    </div>
</body>
</html>
