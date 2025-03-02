<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="text-center w-100 mt-3 p-0 text-white user-panel">
            <b><i class="fa fa-user mr-2"></i></b>
            <p>{{ $user->role_name }}</p>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            @if ($user->role == 'biro-sdm')
                <li class="header">BIRO SDM</li>
                <li class="{{ Request::routeIs('dashboard.*') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.biro-sdm') }}">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="{{ Request::routeIs('program-pelatihan.biro-sdm') ? 'active' : '' }}">
                    <a href="#">
                        <i class="fa fa-files-o"></i>
                        <span>Program Pelatihan</span>
                    </a>
                </li>
                <li class="{{ Request::routeIs('pegawai.biro-sdm') ? 'active' : '' }}">
                    <a href="{{route('pegawai.biro-sdm')}}">
                        <i class="fa fa-user-o"></i>
                        <span>Pegawai</span>
                    </a>
                </li>
            @elseif ($user->role == 'unit-kerja')
                <li class="header">UNIT KERJA</li>
                <li class="{{ Request::routeIs('dashboard.*') ? 'active' : '' }} ">
                    <a href="{{ route('dashboard.unit-kerja') }}">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="">
                    <a href="#">
                        <i class="fa fa-files-o"></i>
                        <span>Usulan Pelatihan</span>
                    </a>
                </li>
            @elseif ($user->role == 'pegawai')
                <li class="header">PEGAWAI</li>
                <li class="{{ Request::routeIs('dashboard.*') ? 'active' : '' }} ">
                    <a href="{{ route('dashboard.pegawai') }}">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>
            @endif
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
