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


                    {{--
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                    </li>
                    --}}

                </ul>





                <!-- user menu -->
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">


                        <img src="{{ asset('storage/people/7777.png') }}" alt=""class="user_image d-inline-block align-top">
                        <span><strong>ゲスト</strong>さん</span>


                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">

                        <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> マイページ </a></li>

                        <li><hr class="dropdown-divider"></li>

                        <li><a class="dropdown-item" href="#">プロフィール変更</a></li>

                        <li><a class="dropdown-item" href="#">パスワード変更</a></li>

                        <li><hr class="dropdown-divider"></li>

                        <li><a class="dropdown-item" href="#"><i class="bi bi-person-plus"></i> 新規会員登録</a></li>

                        <li><a class="dropdown-item" href="#"><i class="bi bi-box-arrow-in-right"></i> ログイン</a></li>

                        <li><a class="dropdown-item" href="#"><i class="bi bi-box-arrow-right"></i> ログアウト</a></li>

                    </ul>
                </div>


            </div>
        </div>
    </nav>
</header>
