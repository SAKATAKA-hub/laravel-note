<h1>マイページTOP</h1>
<h2>{{$user->name}}さんのマイページ</h2>



<!-- ノートの表示 -->
<h3>ノート一覧</h3>
<table>
    @forelse ($notes as $note)
        <tr>
            <td style="width:10em">
                <div><a href="{{route('show_note',$note)}}">{{$note->title}}</a></div>
            </td>
            <td style="width:30em">
                <p>タグ：{{$note->tags}}</p>
            </td>
            @if ( Auth::check() && (Auth::user()->id == $user->id) )
                <td>
                    <a href="#"><button>編集</button></a>
                </td>
                <td>
                    <form action="#" method="POST">
                        @method('DELETE')
                        @csrf
                        <button>削除</button>
                    </form>
                </td>
            @else
                <td></td>
                <td></td>
            @endif
        </tr>
    @empty
        <tr><td>保存データはありません。</td></tr>
    @endforelse
</table>



<!-- マイページユーザー以外のユーザーがログイン中の時の表示 -->

<!-- マイページユーザーがログイン中の時の表示 -->
@if ( Auth::check() && (Auth::user()->id == $user->id) )
    <h3>操作ボタン</h3>
    <ul>
        <li><a href="{{route('create_note',$user)}}">新規作成</a></li>
    </ul>
@endif



<!-- ログイン(ログアウト)ボタンの表示 -->
@if (Auth::check())
    <form method="POST" action="{{route('logout')}}">
        @csrf
        <button>ログアウト</button>
    </form>
@else
    <a href="{{route('login_form')}}"><button>ログイン</button></a>
@endif
