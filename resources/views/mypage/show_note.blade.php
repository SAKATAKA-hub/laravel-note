<h1>ノート表示ページ</h1>


<section>
    <h2 style="background:{{$note->main_color}}">{{$note->title}}</h2>
    <p>tタグ：{{$note->tags}}</p>
    <p>{{$note->user->name}}さん</p>
    <p>更新日：{{$note->updated_at}}</p>
</section>


<button onClick="history.back()">戻る</button>

