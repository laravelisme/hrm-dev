<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="img-fluid d-flex align-items-center">
                    <h5 class="logo-text ms-3">HRM Admin</h5>
                </div>
                <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                </div>
                <div class="sidebar-toggler x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                <li class="sidebar-item {{ activeState('admin.dashboard') }}">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                @php($user = auth()->user())
                @if($user && $user->hasRole('hr'))
                    <li class="sidebar-item has-sub {{ request()->is('admin/master-data*') ? 'active' : '' }}">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-folder-fill"></i>
                            <span>Master Data</span>
                        </a>
                        <ul class="submenu">
                            <li class="submenu-item {{ activeState('admin.master-data.jabatan.index') }}">
                                <a href="{{ route('admin.master-data.jabatan.index') }}" class="sidebar-link">
                                    <i class="bi bi-person-badge"></i>
                                    <span>Jabatan</span>
                                </a>
                            </li>

                            <li class="submenu-item {{ activeState('admin.master-data.saldo-cuti.index') }}">
                                <a href="{{ route('admin.master-data.saldo-cuti.index') }}" class="sidebar-link">
                                    <i class="bi bi-calendar2-check"></i>
                                    <span>Saldo Cuti</span>
                                </a>
                            </li>

                            <li class="submenu-item {{ activeState('admin.master-data.hari-libur.index') }}">
                                <a href="{{ route('admin.master-data.hari-libur.index') }}" class="sidebar-link">
                                    <i class="bi bi-calendar-event"></i>
                                    <span>Hari Libur</span>
                                </a>
                            </li>

                            <li class="submenu-item {{ activeState('admin.master-data.lokasi-kerja.index') }}">
                                <a href="{{ route('admin.master-data.lokasi-kerja.index') }}" class="sidebar-link">
                                    <i class="bi bi-geo-alt"></i>
                                    <span>Lokasi Kerja</span>
                                </a>
                            </li>

                            <li class="submenu-item {{ activeState('admin.master-data.company.index') }}">
                                <a href="{{ route('admin.master-data.company.index') }}" class="sidebar-link">
                                    <i class="bi bi-buildings"></i>
                                    <span>Company</span>
                                </a>
                            </li>

                            <li class="submenu-item {{ activeState('admin.master-data.department.index') }}">
                                <a href="{{ route('admin.master-data.department.index') }}" class="sidebar-link">
                                    <i class="bi bi-diagram-3"></i>
                                    <span>Department</span>
                                </a>
                            </li>

                            <li class="submenu-item {{ activeState('admin.master-data.setting.index') }}">
                                <a href="{{ route('admin.master-data.setting.index') }}" class="sidebar-link">
                                    <i class="bi bi-gear"></i>
                                    <span>Setting</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                <!-- Logout Section -->
                <li class="sidebar-item">
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-flex align-items-center">
                        @csrf
                        <button type="submit" class="btn sidebar-link w-100 text-danger text-decoration-none d-flex align-items-center">
                            <i class="bi bi-box-arrow-right"></i>
                            <span class="ms-2">Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
