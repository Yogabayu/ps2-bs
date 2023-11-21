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
                <a class="nav-link" href="{{ route('s-dashboard') }}">
                    <i class="fas fa-fire"></i><span>Dashboard</span>
                </a>
            </li>
            <li class="menu-header">Datas</li>
            <li class='{{ Request::is('s-monitoring') ? 'active' : '' }}'>
                <a class="nav-link {{ $totalActiveTrans !== 0 ? 'beep beep-sidebar' : '' }}"
                    href="{{ route('s-monitoring.index') }}">
                    <i class="fas fa-magnifying-glass-chart"></i><span>Monitoring</span>
                </a>
            </li>

            <li class='{{ Request::is('s-datas') ? 'active' : '' }}'>
                <a class="nav-link" href="{{ route('s-datas.index') }}">
                    <i class="fas fa-magnifying-glass-chart"></i><span>All Data</span>
                </a>
            </li>

            <li class="menu-header">Organization</li>
            <li class='{{ Request::is('s-listuser') ? 'active' : '' }}'>
                <a class="nav-link " href="{{ route('s-listuser.index') }}">
                    <i class="fas fa-person"></i><span>List User</span>
                </a>
            </li>
        </ul>
    </aside>
</div>
