<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"
                ><i class="fas fa-bars"></i
            ></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <button
                class="btn btn-primary btn-sm"
                data-toggle="dropdown"
                href="#"
            >
                @php $profile = base64_decode(Cookie::get('X-PERSONAL'));
                @endphp
                {{ json_decode($profile)->name }}
            </button>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <div class="dropdown-divider"></div>
                <a href="{{ route('auth.profile') }}" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i>Profil
                </a>
                <div class="dropdown-divider"></div>
                <a
                    href="{{ route('auth.change_password') }}"
                    class="dropdown-item"
                >
                    <i class="fas fa-lock mr-2"></i>Ubah Password
                </a>
                <div class="dropdown-divider"></div>
                <a
                    href="{{ route('auth.logout') }}"
                    class="dropdown-item dropdown-footer"
                    >Logout</a
                >
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
