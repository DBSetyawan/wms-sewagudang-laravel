@section('sidebar')
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto"><a class="navbar-brand" href="{{route('project.index')}}">
                    <div class="brand-logo" style="height: inherit">
                        <img style="width: inherit; height: inherit;"
                            src="{{asset('../images/logo/logo_sewagudangid.png')}}" alt="">
                    </div>
                    <h2 class="brand-text mb-0" style="font-size: large">Sewagudang.id</h2>
                </a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            @yield('navigation_project_has_gudang')
            <li class=" navigation-header"><span>Apps</span>
            </li>
            {{-- <li class="nav-item"><a href="{{route('gudang.index')}}"><i class="feather icon-package"></i><span
                class="menu-title" data-i18n="Email">Gudang</span></a>
            </li> --}}
            @if (Auth::user()->role->nama_role == "Admin")
            <li
                class="nav-item 
                {{(request()->routeIs('project.index') || request()->routeIs('project.create') || request()->routeIs('project.edit') || request()->routeIs('inbound.buattemplateinbound') || request()->routeIs('outbound.buattemplateoutbound') || request()->routeIs('projecthasgudang.index') || request()->routeIs('projecthasgudang.create') || request()->routeIs('projecthasgudang.edit')) ? "active" : ""}}">
                <a href="{{route('project.index')}}"><i class="feather icon-package"></i><span class="menu-title"
                        data-i18n="Email">Project</span></a>
            </li>
            <li class="nav-item"><a href="#"><i class="feather icon-user"></i><span class="menu-title"
                        data-i18n="User">Users</span></a>
                <ul class="menu-content">
                    <li
                        class="{{(request()->routeIs('role.index') || request()->routeIs('role.create') || request()->routeIs('role.edit')) ? "active" : ""}}">
                        <a href="{{route('role.index')}}"><i class="feather icon-circle"></i><span class="menu-item"
                                data-i18n="List">Role</span></a>
                    </li>
                    <li
                        class="{{(request()->routeIs('user.index') || request()->routeIs('user.create') || request()->routeIs('user.edit')) ? "active" : ""}}">
                        <a href="{{route('user.index')}}"><i class="feather icon-circle"></i><span class="menu-item"
                                data-i18n="View">User</span></a>
                    </li>
                </ul>
            </li>
            <li
                class="{{(request()->routeIs('perusahaan.index') || request()->routeIs('perusahaan.create') || request()->routeIs('perusahaan.edit')) ? "active" : ""}} nav-item">
                <a href="{{route('perusahaan.index')}}"><i class="fa fa-building-o"></i><span class="menu-title"
                        data-i18n="Todo">Perusahaan Penyewa</span></a>
            </li>
            <li
                class="{{(request()->routeIs('modul.index')|| request()->routeIs('modul.create') || request()->routeIs('modul.edit')) ? "active" : ""}} nav-item">
                <a href="{{route('modul.index')}}"><i class="fa fa-file-o"></i><span class="menu-title"
                        data-i18n="Todo">Modul</span></a>
            </li>
            <li
                class="{{(request()->routeIs('gudang.index')|| request()->routeIs('gudang.create') || request()->routeIs('gudang.edit') || request()->routeIs('locator.index') || request()->routeIs('locator.create') || request()->routeIs('locator.edit')) ? "active" : ""}} nav-item">
                <a href="{{route('gudang.index')}}"><i class="fa fa-home"></i><span class="menu-title"
                        data-i18n="Colors">Gudang</span></a></li>

            <li class="{{(request()->routeIs('log.index')) ? "active" : ""}} nav-item"><a
                    href="{{route('log.index')}}"><i class="fa fa-history"></i><span class="menu-title"
                        data-i18n="Colors">Log</span></a></li>
            @endif
            <li class="nav-item"><a href="{{route('utility.download')}}"><i class="fa 
                fa-file-text-o"></i><span class="menu-title" data-i18n="Colors">Documentasi</span></a></li>
            @yield('extra_sidebar')
        </ul>
        {{-- @yield('sidebar') --}}
    </div>
</div>

@endsection