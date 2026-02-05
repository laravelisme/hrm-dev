<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="img-fluid d-flex align-items-center">
                    <img  width="40" src="{{ asset('storage/' . $global_setting['app_logo']) }}">
                    <h6 class="ms-3">HRM Admin</h6>
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

                @if($user && $user->hasAnyRole(['super-admin']))
                    <li class="sidebar-item {{ activeState('tenancy.domain.index') }}">
                        <a href="{{ route('tenancy.domain.index') }}" class="sidebar-link">
                            <i class="bi bi-globe2"></i>
                            <span>Tenants</span>
                        </a>
                    </li>
                @endif

                @if($user && $user->hasAnyRole(['hr', 'admin']))
                    <li class="sidebar-item {{ activeState('admin.karyawan.index') }}">
                        <a href="{{ route('admin.karyawan.index') }}" class="sidebar-link">
                            <i class="bi bi-people-fill"></i>
                            <span>Karyawan</span>
                        </a>
                    </li>
                @endif

                @php($user = auth()->user())
                @if($user && $user->hasRole('hr'))

                    <li class="sidebar-item has-sub {{ request()->is('admin/calon-karyawan*') ? 'active' : '' }}">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-person-plus"></i>
                            <span>Calon Karyawan</span>
                        </a>
                        <ul class="submenu">
                            <li class="submenu-item {{ activeState('admin.calon-karyawan.generate-link.index') }}">
                                <a href="{{ route('admin.calon-karyawan.generate-link.index') }}" class="sidebar-link">
                                    <i class="bi bi-link-45deg"></i>
                                    <span>Generate Link</span>
                                </a>
                            </li>

                            <li class="submenu-item {{ activeState('admin.calon-karyawan.shortlist-admin.index') }}">
                                <a href="{{ route('admin.calon-karyawan.shortlist-admin.index') }}" class="sidebar-link">
                                    <i class="bi bi-person-check"></i>
                                    <span>Shortlist Adm</span>
                                </a>
                            </li>

                            <li class="submenu-item {{ activeState('admin.calon-karyawan.test-tulis.index') }}">
                                <a href="{{ route('admin.calon-karyawan.test-tulis.index') }}" class="sidebar-link">
                                    <i class="bi bi-journal-text"></i>
                                    <span>Test Tulis</span>
                                </a>
                            </li>

                            <li class="submenu-item {{ activeState('admin.calon-karyawan.interview.index') }}">
                                <a href="{{ route('admin.calon-karyawan.interview.index') }}" class="sidebar-link">
                                    <i class="bi bi-person-video3"></i>
                                    <span>Interview</span>
                                </a>
                            </li>

                            <li class="submenu-item {{ activeState('admin.calon-karyawan.talent-pool.index') }}">
                                <a href="{{ route('admin.calon-karyawan.talent-pool.index') }}" class="sidebar-link">
                                    <i class="bi bi-people"></i>
                                    <span>Talent Pool</span>
                                </a>
                            </li>

                            <li class="submenu-item {{ activeState('admin.calon-karyawan.offering.index') }}">
                                <a href="{{ route('admin.calon-karyawan.offering.index') }}" class="sidebar-link">
                                    <i class="bi bi-people"></i>
                                    <span>Offering</span>
                                </a>
                            </li>

                            <li class="submenu-item {{ activeState('admin.calon-karyawan.rejected.index') }}">
                                <a href="{{ route('admin.calon-karyawan.rejected.index') }}" class="sidebar-link">
                                    <i class="bi bi-person-x"></i>
                                    <span>Rejected</span>
                                </a>
                            </li>

                        </ul>
                    </li>

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

                            <li class="submenu-item {{ activeState('admin.master-data.grup-jam-kerja.index') }}">
                                <a href="{{ route('admin.master-data.grup-jam-kerja.index') }}" class="sidebar-link">
                                    <i class="bi bi-clock"></i>
                                    <span>Grup Jam Kerja</span>
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


                @php($user = auth()->user())
                @if($user && $user->hasRole('hr'))

                    <li class="sidebar-item has-sub {{ request()->is('admin/transaksi*') ? 'active' : '' }}">
                        <a href="#" class="sidebar-link">
                            <i class="bi bi-receipt"></i>
                            <span>Transaksi</span>
                        </a>
                        <ul class="submenu">
                            <li class="submenu-item {{ activeState('admin.transaksi.saldo-cuti-tahunan.index') }}">
                                <a href="{{ route('admin.transaksi.saldo-cuti-tahunan.index') }}" class="sidebar-link">
                                    <i class="bi bi-calendar-check"></i>
                                    <span>Cuti Tahunan</span>
                                </a>
                            </li>

                            <li class="submenu-item {{ activeState('admin.transaksi.presensi.index') }}">
                                <a href="{{ route('admin.transaksi.presensi.index') }}" class="sidebar-link">
                                    <i class="bi bi-calendar-date"></i>
                                    <span>Presensi</span>
                                </a>
                            </li>

                            <li class="submenu-item {{ activeState('admin.transaksi.cuti-karyawan.index') }}">
                                <a href="{{ route('admin.transaksi.cuti-karyawan.index') }}" class="sidebar-link">
                                    <i class="bi bi-person-check"></i>
                                    <span>Cuti</span>
                                </a>
                            </li>

                            <li class="submenu-item {{ activeState('admin.transaksi.izin-karyawan.index') }}">
                                <a href="{{ route('admin.transaksi.izin-karyawan.index') }}" class="sidebar-link">
                                    <i class="bi bi-ticket-perforated"></i>
                                    <span>Izin</span>
                                </a>
                            </li>

                            <li class="submenu-item {{ activeState('admin.transaksi.lembur-karyawan.index') }}">
                                <a href="{{ route('admin.transaksi.lembur-karyawan.index') }}" class="sidebar-link">
                                    <i class="bi bi-clock-history"></i>
                                    <span>Lembur</span>
                                </a>
                            </li>

                            <li class="submenu-item {{ activeState('admin.transaksi.surat-peringatan.index') }}">
                                <a href="{{ route('admin.transaksi.surat-peringatan.index') }}" class="sidebar-link">
                                    <i class="bi bi-exclamation-circle"></i>
                                    <span>SP</span>
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
