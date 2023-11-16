<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            @if ($app !== null)
                <a href="{{ route('indexAdmin') }}">
                    <img alt="image" src="{{ asset('file/setting/' . $app->logo) }}" class="mr-1"
                        style="max-width: 40px; max-height: 40px;">{{ $app->name_app }}</a>
            @else
                <a href="{{ route('indexAdmin') }}">App-2</a>
            @endif
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            @if ($app !== null)
                <a href="{{ route('indexAdmin') }}">
                    <img alt="image" src="{{ asset('file/setting/' . $app->logo) }}" class="mr-1"
                        style="max-width: 40px; max-height: 40px;">
                </a>
            @else
                <a href="{{ route('indexAdmin') }}">App-2</a>
            @endif
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class='{{ Request::is('dashboard') ? 'active' : '' }}'>
                <a class="nav-link" href="{{ route('indexAdmin') }}">
                    <i class="fas fa-fire"></i><span>Dashboard</span>
                </a>
            </li>
            <li class="menu-header">Datas</li>
            <li class='{{ Request::is('monitoring') ? 'active' : '' }}'>
                <a class="nav-link {{ Request::is('monitoring') || $totalActiveTrans !== 0 ? 'beep beep-sidebar' : '' }}"
                    href="{{ route('monitoring.index') }}">
                    <i class="fas fa-magnifying-glass-chart"></i><span>Monitoring</span>
                </a>
            </li>

            <li class='{{ Request::is('datas') ? 'active' : '' }}'>
                <a class="nav-link" href="{{ route('datas.index') }}">
                    <i class="fas fa-magnifying-glass-chart"></i><span>All Data</span>
                </a>
            </li>

            <li class="menu-header">Organization</li>
            <li class='{{ Request::is('office') ? 'active' : '' }}'>
                <a class="nav-link" href="{{ route('office.index') }}">
                    <i class="fas fa-building"></i><span>Office</span>
                </a>
            </li>
            <li class='{{ Request::is('user') ? 'active' : '' }}'>
                <a class="nav-link " href="{{ route('user.index') }}">
                    <i class="fas fa-person"></i><span>List User</span>
                </a>
            </li>
            <li class='{{ Request::is('subordinate') ? 'active' : '' }}'>
                <a class="nav-link" href="{{ route('subordinate.index') }}">
                    <i class="fas fa-users"></i><span>Subordinate</span>
                </a>
            </li>
            <li class='{{ Request::is('user-activity') ? 'active' : '' }}'>
                <a class="nav-link" href="{{ route('user-activity.index') }}">
                    <i class="fas fa-clipboard"></i><span>User Activity</span>
                </a>
            </li>
            <li class='{{ Request::is('position') ? 'active' : '' }}'>
                <a class="nav-link" href="{{ route('position.index') }}">
                    <i class="fas fa-people-arrows"></i><span>Position</span>
                </a>
            </li>
            <li class='{{ Request::is('place-transc') ? 'active' : '' }}'>
                <a class="nav-link" href="{{ route('place-transc.index') }}">
                    <i class="fas fa-map-location-dot"></i><span>Transaction Place</span>
                </a>
            </li>
            <li class='{{ Request::is('transc-type') ? 'active' : '' }}'>
                <a class="nav-link" href="{{ route('transc-type.index') }}">
                    <i class="fas fa-circle-info"></i><span>Transaction Type</span>
                </a>
            </li>
            <li class="menu-header">Setting</li>
            <li class='{{ Request::is('setting-app') ? 'active' : '' }}'>
                <a class="nav-link" href="{{ route('setting-app.index') }}">
                    <i class="fas fa-gears"></i><span>Application</span>
                </a>
            </li>
            <li class='{{ Request::is('sso') ? 'active' : '' }}'>
                <a class="nav-link" href="#">
                    <i class="fas fa-file-shield"></i><span>SSO</span>
                </a>
            </li>
        </ul>
    </aside>
</div>
