<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('indexAdmin') }}">P2-BS</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('indexAdmin') }}">P2-BS</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class='{{ Request::is('dashboard') ? 'active' : '' }}'>
                <a class="nav-link" href="{{ route('indexAdmin') }}">
                    <i class="fas fa-fire"></i><span>Dashboard</span>
                </a>
            </li>
            <li class="menu-header">Datas</li>
            <li>
                <a class="nav-link" href="#">
                    <i class="fas fa-database"></i><span>Monitoring</span>
                </a>
                <a class="nav-link" href="#">
                    <i class="fas fa-magnifying-glass-chart"></i><span>All Data</span>
                </a>
            </li>
            <li class="menu-header">User</li>
            <li class='{{ Request::is('user') ? 'active' : '' }}'>
                <a class="nav-link " href="{{ route('user.index') }}">
                    <i class="fas fa-person"></i><span>List User</span>
                </a>

            </li>
            <li class='{{ Request::is('user-activity') ? 'active' : '' }}'>
                <a class="nav-link" href="{{ route('user-activity.index') }}">
                    <i class="fas fa-clipboard"></i><span>User Activity</span>
                </a>
            </li>
            <li class="menu-header">Organization</li>
            <li class='{{ Request::is('office') ? 'active' : '' }}'>
                <a class="nav-link" href="{{ route('office.index') }}">
                    <i class="fas fa-person"></i><span>Office</span>
                </a>
            </li>
            <li class='{{ Request::is('organization') ? 'active' : '' }}'>
                <a class="nav-link" href="#">
                    <i class="fas fa-people-arrows"></i><span>Position</span>
                </a>
                <a class="nav-link" href="#">
                    <i class="fas fa-map-location-dot"></i><span>Transaction Place</span>
                </a>
                <a class="nav-link" href="#">
                    <i class="fas fa-circle-info"></i><span>Transaction Type</span>
                </a>
            </li>
            <li class="menu-header">Setting</li>
            <li class='{{ Request::is('Setting') ? 'active' : '' }}'>
                <a class="nav-link" href="#">
                    <i class="fas fa-gears"></i><span>Application</span>
                </a>
                <a class="nav-link" href="#">
                    <i class="fas fa-file-shield"></i><span>SSO</span>
                </a>
            </li>
        </ul>
    </aside>
</div>
