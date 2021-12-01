<!doctype html>
<html lang="ja">
<head>
    <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <!-- Title meta tags -->
        <title>@yield('title')</title>


    <!-- Styel link tags -->
        <!--(bootstrap)-->
        @include('includes.bootstrap.css')
        <!-- base.css -->
        <link rel="stylesheet" href="{{asset('css/layouts/base.css')}}">

        @yield('style')

</head>

<body>


    <!-- header -->
    @include('includes.header')



    <main id="app">

        <!-- breadcrumb -->
        <div class="mt-2 ms-3">
            <i class="bi bi-house-fill"></i>
            <div class="d-inline-block">
                @yield('main.breadcrumb')
            </div>
        </div>




        <!-- main_container -->
        <div class="main_container row ps-2 ps-md-0 pt-5">


            <!-- alert -->
            @include('includes.alert')


            <!-- main_top_container -->
            @yield('main.top_container')



            <!-- main_center_container -->
            <div class="main_center_container col-lg-9 mb-5">

                @yield('main.center_container')

            </div>


            <!-- main_side_container -->
            <div class="main_side_container col-lg-3">

                @yield('main.side_container')

            </div>


        </div>
    </main>



    <footer class="bg-secondary text-white">

        <p>&copy SAKAI TAKAHIRO</p>

    </footer>




    <!-- Script tags -->
        @include('includes.bootstrap.js') <!--(bootstrap)-->

        @yield('script')

</body>
</html>
