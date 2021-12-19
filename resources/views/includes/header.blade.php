<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">


            <!-- rogo icon -->
            <h1 class="navbar-brand">
                <a class="navbar-brand text-primary" href="{{route('home')}}">
                    <img src="{{asset('svg/logo.svg')}}" alt="" width="30" height="24" class="d-inline-block align-top">
                    my note
                </a>
            </h1>




            <!-- user menu -->
            <div class="dropdown">
                <a class="nav-link dropdown-toggle mb-2" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">


                    @if (Auth::check())

                        {{-- <img src="{{ Auth::user()->image_url }}" alt=""class="user_image d-inline-block align-top"> --}}
                        <span><strong>{{Auth::user()->name}}</strong> さんがログイン中</span>

                    @else

                        {{-- <img src="{{ $image_url['gest_user'] }}" alt=""class="user_image d-inline-block align-top"> --}}
                        <span><strong>ゲスト</strong> さん</span>

                    @endif


                </a>

                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">

                    @if (Auth::check())

                        <li>
                            <a class="dropdown-item" href="{{route('mypage_top',Auth::user()->id)}}">
                                <i class="bi bi-person"></i> マイページ
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        @if (Auth::user()->app_dministrator)
                        <li>
                            <a class="dropdown-item" href="{{route('app_admin.top')}}">管理者ページ</a>
                        </li>
                        @endif

                        <li>
                            <a class="dropdown-item" href="{{route('edit_register')}}">プロフィール変更</a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{route('edit_register')}}">パスワード変更</a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{route('edit_register')}}">退会する</a>
                        </li>


                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <form method="POST" action="{{route('logout')}}" lass="dropdown-item">
                                @csrf
                                <button class="dropdown-item"><i class="bi bi-box-arrow-right"></i> ログアウト</button>
                            </form>
                        </li>

                        @else

                        <li>
                            <a class="dropdown-item" href="{{route('login_form')}}">
                                <i class="bi bi-box-arrow-right"></i> ログイン
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{route('get_register')}}">
                                <i class="bi bi-person-plus"></i> 新規会員登録
                            </a>
                        </li>


                    @endif

                </ul>
            </div><!--end user menu -->


        </div>
    </nav>
</header>
