<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Co-author NetWork Menagement</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
{{--    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">--}}
    {{--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">--}}
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/AdminLTE_all-skins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/iCheck_skins_square_all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('css/ionicons.min.css') }}">

    @yield('css')
    <!-- <script type="application/javascript" src="{{ asset('js/turbolinks.min.js') }}"></script> -->
</head>

<body class="skin-blue sidebar-mini">
@if (\App\Helpers\Utility::displayForAdmin())
    <div class="wrapper">
        <!-- Main Header -->
        <header class="main-header" data-turbolinks-permenent>

            <!-- Logo -->
            <a href="#" class="logo">
                <b>Co-author NetWork</b>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- User Account Menu -->

                        {!! Form::open(['url'=> url('/logout'), 'id' => 'logout-form', 'method' => 'POST']) !!}
                            {!! Form::submit('Logout', ['style' => 'border: none; font-size: 20px; padding: 10; color:white; margin: 10px 10px 0px 0px; background: none;']) !!}
                        {!! Form::close() !!}
                    </ul>
                </div>
            </nav>
        </header>

        <!-- Left side column. contains the logo and sidebar -->
        @include('layouts.sidebar')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        <!-- Main Footer -->
        <footer class="main-footer" style="max-height: 100px;text-align: center">
            <strong>Copyright © 2017 <a href="#">Company</a>.</strong> All
            rights reserved.
        </footer>

    </div>
@else
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{!! url('/') !!}">
                    Co-author NetWork
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li>{!! Html::link(route('user.authors.index'), 'Author') !!}</li>
                    <li>{!! Html::link(route('user.papers.index'), 'Paper') !!}</li>
                    <li>{!! Html::link(route('user.author-paper.index'), 'Author Paper') !!}</li>
                    <li>{!! Html::link(route('user.co-authors.index'), 'Co-authors') !!}</li>
                    <li>{!! Html::link(route('user.candidates.index'), 'Candidate') !!}</li>
                    <li>{!! Html::link(route('user.universities.index'), 'University') !!}</li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
@endif
    <!-- jQuery 3.1.1 -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    {{--<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>--}}
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/icheck.min.js') }}"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset('js/app.min.js') }}"></script>

    @yield('scripts')
</body>
</html>
