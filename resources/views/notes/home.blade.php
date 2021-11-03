@extends('layouts.base')




@section('style')
<style>
    .userslist_userimage{
        width: 1.5rem;
        height: 1.5rem;
        border-radius: 50%;
        border: solid 1px #bbb;
    }
</style>
@endsection




@section('script')
@endsection






@section('title','アプリケーション管理者ページ')





@section('main.breadcrumb')
    Home
@endsection




@section('main.side_container')
@endsection








@section('main.top_container')


    <!-- page title -->
    <h2 class="pt-2 pb-2 mb-3">
        <p class="me-2 d-inline bg-primary border border-primary border-5" border-5" style="border-radius:.5em;"></p>
        ユーザー一覧
    </h2>


    <table class="table text-center">
        <thead>
            <tr>
                <th scope="col" colspan="3">ユーザー</th>
                <th scope="col">公開中投稿数</th>
                <th scope="col">コメント</th>
                <th scope="col"></th>

            </tr>
        </thead>
        <tbody>

            @foreach ($users as $user)
                <tr>
                    <th scope="row">{{sprintf('%04d',$user->id)}}</th>
                    <td>
                        <img src="{{ $user->image_url }}" alt=""class="userslist_userimage me-3">
                    </td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->open_post}}</td><!-- 公開中投稿数 -->
                    <td>{{$user->comment}}</td>
                    <td class="">
                        <a href="{{route('mypage_top',$user->id)}}" class="btn btn-primary">投稿ノート一覧はこちら</a>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>



@endsection

