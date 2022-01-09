<!doctype html>
<html lang="ja">
<head>
    <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <!-- Title meta tags -->
        <title>ようこそ　my noteへ！</title>

    <!-- favicon -->
        <link rel="icon" href="{{asset('svg/logo.svg')}}">

    <!-- Styel link tags -->
        <!--(bootstrap)-->
        @include('includes.bootstrap.css')
        <!-- base.css -->
        <link rel="stylesheet" href="{{asset('css/layouts/base.css')}}">

        <style>
            section{
                text-align: center;
                padding: 4em 0;
            }
            .sec_container{
                max-width: 1200px;
                min-width: 400px;
                margin: 0 auto;
                text-align: center;
                box-sizing: border-box;
            }
        </style>



</head>

<body>


    <!-- header -->
    @include('includes.header')



    <main>
        <section>
            <div class="sec_container">


                <h2 class="display-1 fw-bold m-5">
                    <p class="d-lg-inline">あなただけの</p>
                    <p class="d-lg-inline">Ｗebノート</p>
                </h2>

                <div>
                    <p class="d-lg-inline">パソコン、スマートフォン、タブレットを使って、</p>
                    <p class="d-lg-inline">ブログのようなノートが簡単に使える！</p>
                </div>
                <div>
                    <p class="d-lg-inline">公開・非公開設定でみんなで共有できる、</p>
                    <p class="d-lg-inline">自分だけのノートにもできる。使い方はあなた次第。</p>
                </div>


                <div class="mt-5 mb-5" style="max-width:400px;margin:0 auto;">

                    <div>

                        <a href="{{route('get_register')}}" style="width:8em;" class="btn btn-primary m-3"
                        >無料登録</a>

                        <a href="{{route('login_form')}}" style="width:8em;" class="btn btn-outline-primary m-3"
                        >ログイン</a>

                    </div>

                    <form action="{{route('easy_post_register')}}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg mt-3 mb-3 w-100">
                            簡単ユーザー登録
                        </button>
                    </form>

                </div>


                <div class="p-3 bg-light" style="max-width:600px;margin:0 auto;">
                    <div>
                        <p class="d-lg-inline">簡単ユーザー登録を押すと、</p>
                        <p class="d-lg-inline">簡易的にユーザー登録をして、すぐに利用することができます。</p>
                    </div>
                    <p><small>※簡単ユーザー登録アカウントの利用期限は24時間です。</small></p>
                </div>




            </div>
        </section>


        <section>
            <div class="row sec_container">
                <div class="col-md-7">
                    <h2 class="featurette-heading m-5">webアプリノートだからこんなに便利</h2>
                    <p class="lead text-start m-3">
                        webに保存するノートだから、パソコン、スマートフォン、タブレットなど、ネットにつなげばいつでも書ける、いつでも読める。
                    </p>
                    <p class="lead text-start m-3">
                        URLを友達や仲間に教えれば、保存したノートをみんなで共有できる。<br>
                        自分だけのメモや、人に見せたくないノートは、”非公開設定”にすることで、他の人からは見られなくなるよ。
                    </p>

                </div>
                <div class="col-md-5">
                    <img src="{{asset('storage/img/top/top1.png')}}" alt="サンプル画像" width="400" height="400">
                </div>
            </div>
        </section>



        <section>
            <div class="row sec_container">
                <div class="col-md-7 order-md-2">
                    <h2 class="featurette-heading m-5">
                        HTMLコーディングができなくても<br>
                        簡単スタイリング
                    </h2>
                    <p class="lead text-start m-3">
                        HTMLの知識が無くても、見出し、文章、画像、リンクなど、文章の種類を指定するだけで、お好みのスタイリングが可能！
                    </p>
                    <p class="lead text-start m-3">
                        このアプリでは、文章の種類を
                        <strong class="text-decoration-underline">"テキストボックスの種類"</strong>
                        と呼んでるよ。
                    </p>
                    <p class="lead text-start m-3">
                        スタイリングの配色の種類は、10種類！
                        テキストボックスの選択で好きなスタイリングを選ぼう！！
                    </p>


                    </div>
                <div class="col-md-5 order-md-1">
                    <img src="{{asset('storage/img/top/top2.png')}}" alt="サンプル画像" width="400" height="400">
                </div>
            </div>
        </section>



        <section>
            <div class="row sec_container">
                <div class="col-md-7">
                    <h2 class="featurette-heading m-5">作ったノートをマイページで簡単管理</h2>
                    <p class="lead text-start m-3">
                        作ったノートに”タグ名”を付けておけば、マイページからタグ別にノートの一覧を表示できる。
                        タグ名はいくつでも付けられるから、ノートを細かく管理しよう！
                    </p>
                    <p class="lead text-start m-3">
                        もちろん、マイページからは”タイトルキーワード検索”、”日付絞り込み”、”公開・非公開別表示”など、ブログの様な簡単管理が可能！
                    </p>
                    <p class="lead text-start m-3">
                        <a class="btn btn-outline-secondary" href="https://sakataka-laravel-note.herokuapp.com/mypage_top/15">
                            サンプルユーザーのマイページはこちら
                        </a>
                    </p>


                </div>
                <div class="col-md-5">
                    <img src="{{asset('storage/img/top/top4.png')}}" alt="サンプル画像" width="400" height="400">
                </div>
            </div>
        </section>



        <section style="background-color:#eee;">
            <div class="sec_container">


                <h2 class="h1 fw-bold m-5">
                    <p>さあ、いますぐ</p>
                    <p>my noteをはじめよう！</p>
                </h2>


                <div class="mt-5 mb-5" style="max-width:400px;margin:0 auto;">

                    <div>

                        <a href="{{route('get_register')}}" style="width:8em;" class="btn btn-primary m-3"
                        >無料登録</a>

                        <a href="{{route('login_form')}}" style="width:8em;" class="btn btn-outline-primary m-3"
                        >ログイン</a>

                    </div>

                    <form action="{{route('easy_post_register')}}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg mt-3 mb-3 w-100">
                            簡単ユーザー登録
                        </button>
                    </form>

                </div>


            </div>
        </section>




    <footer class="bg-secondary text-white">

        <p>&copy SAKAI TAKAHIRO</p>

    </footer>





    <!-- Script tags -->
    @include('includes.bootstrap.js') <!--(bootstrap)-->

</body>
</html>
