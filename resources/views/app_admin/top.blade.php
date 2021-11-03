@extends('layouts.base')




@section('style')

@endsection




@section('script')
@endsection






@section('title','アプリケーション管理者ページ')





@section('main.breadcrumb')
    アプリケーション管理者ページ
@endsection




@section('main.side_container')
@endsection








@section('main.top_container')


    <!-- page title -->
    <h2 class="pt-2 pb-2 mb-3">
        <p class="me-2 d-inline bg-primary border border-primary border-5" border-5" style="border-radius:.5em;"></p>
        アプリケーション管理者ページ
    </h2>

    <table class="table text-center">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">氏名</th>
                <th scope="col">メールアドレス</th>
                <th scope="col">管理者権限</th>
                <th scope="col">公開中投稿数</th>
                <th scope="col">非公開投稿数</th>
                <th scope="col"></th>

            </tr>
        </thead>
        <tbody>

            @foreach ($users as $user)
                <tr>
                    <th scope="row">{{sprintf('%04d',$user->id)}}</th>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td></td><!-- 管理者権限 -->
                    <td>{{$user->open_post}}</td><!-- 公開中投稿数 -->
                    <td>{{$user->private_post}}</td><!-- 非公開投稿数 -->

                    <td class="">

                        <form method="POST" action="{{route('app_admin.reset_password')}}" class='d-inline'>
                            @method('patch')
                            @csrf
                            <input type="hidden" name="user_id" value="{{$user->id}}">
                            <button type="submit" class="btn btn-secondary mb-3 me-3">パスワードのリセット</button>
                        </form>

                        <form method="POST" action="{{route('app_admin.destroy_notes')}}" class='d-inline'>
                            @method('delete')
                            @csrf
                            <input type="hidden" name="user_id" value="{{$user->id}}">
                            <button type="submit" class="btn btn-secondary mb-3 me-3">全投稿のリセット</button>
                        </form>

                        <form method="POST" action="{{route('app_admin.destroy_user')}}" class='d-inline'>
                            @method('delete')
                            @csrf
                            <input type="hidden" name="user_id" value="{{$user->id}}">
                            <button type="submit" class="btn btn-outline-danger mb-3 me-3">ユーザー削除</button>
                        </form>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>

    <div class="pt-5 pb-5 ">
        <a href="{{route('app_admin.s3.edit_file')}}" class="btn btn-link">ファイル管理</a>
    </div>



@endsection

