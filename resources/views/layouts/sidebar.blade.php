<aside class="main-sidebar" id="sidebar-wrapper" data-turbolinks-permenent>

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('img/default-user.png') }}" class="img-circle"
                     alt="User Image"/>
            </div>
            <div class="pull-left info">
                @if (! Auth::guest())
                    <p>{{ Auth::user()->name}}</p>
                @endif
            </div>
        </div>

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            @include('layouts.menu')
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
