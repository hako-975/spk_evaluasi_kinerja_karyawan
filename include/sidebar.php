<style>
    .nav-link.active {
        background-color: #0d6efd!important;
        color: #fff!important;
    }
</style>

<aside class="app-sidebar bg-white shadow" data-bs-theme="dark">
    <div class="sidebar-brand border-danger border-bottom"> 
        <a href="index.php" class="brand-link"> 
            <img src="assets/img/properties/favicon.png" alt="Logo" class="brand-image-xl">
            <span class="brand-text fw-light text-dark small text-wrap">Sistem Pendukung Keputusan Evaluasi Kinerja Karyawan</span>
        </a> 
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2"> <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <li class="nav-item"> 
                    <a href="index.php" class="nav-link text-dark <?= ($_SERVER['REQUEST_URI'] == '/spk_ekskul_fadilah/index.php') ? 'active' : ''; ?>"> <i class="nav-icon fas fa-fw fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item"> 
                    <a href="spk.php" class="nav-link text-dark <?= ($_SERVER['REQUEST_URI'] == '/spk_ekskul_fadilah/spk.php') ? 'active' : ''; ?>"> <i class="nav-icon fas fa-fw fa-calculator"></i>
                        <p>SPK</p>
                    </a> 
                </li>
                <li class="nav-item"> 
                    <a href="kriteria.php" class="nav-link text-dark <?= ($_SERVER['REQUEST_URI'] == '/spk_ekskul_fadilah/kriteria.php') ? 'active' : ''; ?>"> <i class="nav-icon fas fa-fw fa-clipboard-list"></i>
                        <p>Kriteria</p>
                    </a> 
                </li>
                <li class="nav-item"> 
                    <a href="karyawan.php" class="nav-link text-dark <?= ($_SERVER['REQUEST_URI'] == '/spk_ekskul_fadilah/karyawan.php') ? 'active' : ''; ?>"> <i class="nav-icon fas fa-fw fa-users"></i>
                        <p>Karyawan</p>
                    </a> 
                </li>
                <li class="nav-item"> 
                    <a href="user.php" class="nav-link text-dark <?= ($_SERVER['REQUEST_URI'] == '/spk_ekskul_fadilah/user.php') ? 'active' : ''; ?>"> <i class="nav-icon fas fa-fw fa-user"></i>
                        <p>User</p>
                    </a> 
                </li>
                <li class="nav-item"> 
                    <a href="laporan.php" class="nav-link text-dark <?= ($_SERVER['REQUEST_URI'] == '/spk_ekskul_fadilah/laporan.php') ? 'active' : ''; ?>"> <i class="nav-icon fas fa-fw fa-file-alt"></i>
                        <p>Laporan</p>
                    </a>
                </li>
                <hr class="sidebar-divider">
                <li class="nav-item"> 
                    <a href="log.php" class="nav-link text-dark <?= ($_SERVER['REQUEST_URI'] == '/spk_ekskul_fadilah/log.php') ? 'active' : ''; ?>"> <i class="nav-icon fas fa-fw fa-history"></i>
                        <p>Log</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div> 
</aside> 