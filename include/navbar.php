<nav class="app-header navbar navbar-expand bg-body"> <!--begin::Container-->
    <div class="container-fluid"> <!--begin::Start Navbar Links-->
        <ul class="navbar-nav">
            <li class="nav-item"> <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"> <i class="fas fa-fw fa-bars"></i> </a> </li>
        </ul> <!--end::Start Navbar Links--> <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto"> <!--begin::Navbar Search-->
            <li class="nav-item"> <a class="nav-link" href="#" data-lte-toggle="fullscreen"> <i data-lte-icon="maximize" class="fas fa-fw fa-expand"></i> <i data-lte-icon="minimize" class="fas fa-fw fa-compress" style="display: none;"></i> </a> </li> <!--end::Fullscreen Toggle--> <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="assets/img/profiles/<?= $dataUser['foto']; ?>" class="user-image rounded-circle shadow" alt="User Image">
                    <span class="d-none d-md-inline"><?= $dataUser['username']; ?></span>
                </a>
                <ul class="dropdown-menu custom-dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                    <li>
                        <a href="profile.php" class="dropdown-item py-2"><i class="fas fa-fw fa-user"></i> <span class="mx-2">Profile</span></a>
                    </li>
                    <li>
                        <a href="logout.php" class="dropdown-item py-2"><i class="fas fa-fw fa-sign-out-alt"></i> <span class="mx-2">Logout</span></a>
                    </li>
                </ul>
            </li><!--end::User Menu Dropdown-->
        </ul> <!--end::End Navbar Links-->
    </div> <!--end::Container-->
</nav> <!--end::Header--> <!--begin::Sidebar-->
