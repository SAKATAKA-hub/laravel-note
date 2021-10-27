<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">


            <!-- rogo icon -->
            <h1 class="navbar-brand">
                <a class="navbar-brand text-primary" href="#">
                    <img src="{{ asset('storage/common/logo_image.png') }}" alt="" width="30" height="24" class="d-inline-block align-top">
                    note
                </a>
            </h1>



            <!-- hanburger icon -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>




            <div class="collapse navbar-collapse" id="navbarSupportedContent">


                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    {{-- <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                    </li> --}}
                </ul>




                <!-- user menu -->
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">


                        @if (Auth::check())
                            <img src="{{ asset('storage/'.Auth::user()->image) }}" alt=""class="user_image d-inline-block align-top">
                            <span><strong>{{Auth::user()->name}}</strong> さん</span>

                        @else

                            <img src="{{ asset('storage/people/no_img.png') }}" alt=""class="user_image d-inline-block align-top">
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
                </div>


            </div>
        </div>
    </nav>
</header>
