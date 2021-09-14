<!doctype html>
<html lang="ja">
<head>
    <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <!-- Title meta tags -->
        <title>@yield('title')</title>


    <!-- Styel link tags -->
        @include('includes.bootstrap.css') <!--(bootstrap)-->

        <link rel="stylesheet" href="{{asset('css/layouts/base.css')}}"> <!-- base.css -->
        @yield('style')

</head>

<body>


    <!-- header -->
    @include('includes.header')



    <main>
        <!-- breadcrumb -->
        @yield('main.breadcrumb')


        <div class="main_container row p-md-3">


            <!-- main_center_container -->
            <div class="main_center_container col-md-8 mb-5">

                @yield('main.center_container')

            </div>


            <!-- main_side_container -->
            <div class="main_side_container col-md-4">

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
