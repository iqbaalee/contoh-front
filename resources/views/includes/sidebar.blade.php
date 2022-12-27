<style>
    .nav-sidebar .nav-item > a.active {
        background-color: whitesmoke !important;
    }

    .nav-sidebar .nav-item a {
        color: white !important;
    }

    .nav-sidebar .nav-item > a.active {
        color: black !important;
        background-color: whitesmoke !important;
    }
</style>
<!-- Main Sidebar Container -->
@if(Cookie::get('X-PERSONAL')) @php $profile =
base64_decode(Cookie::get('X-PERSONAL')); $permission =
json_decode($profile)->role->permissions; @endphp @endif
<aside class="main-sidebar sidebar-dark-teal bg-primary">
    <!-- Brand Logo -->
    <a href="#" class="brand-link border-bottom border-light">
        <div class="row">
            <div class="col-sm-3 d-flex align-items-center">
                <img
                    src="{{ asset('images/lunch.png') }}"
                    alt="NELONGSO"
                    class="brand-image"
                />
            </div>
            ADMIN RESTO
        </div>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul
                class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false"
            >
                <li class="nav-item">
                    <a
                        href="{{ route('dashboard.index') }}"
                        class=" {{Request::route()->getPrefix() == 'dashboard' ? 'active' : ''}} nav-link"
                    >
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @foreach($permission as $key => $value)
                <li class="nav-item">
                    <a
                        href="{{$value->menu->url}}"
                        class="nav-link {{Route::current()->getPrefix() == $value->menu->url ? 'active' : ''}}"
                    >
                        <i class="nav-icon {{$value->menu->icon}}"></i>
                        <p>
                            {{$value->menu->name }}
                        </p>
                    </a>
                </li>
                @endforeach
            </ul>
        </nav>
    </div>
</aside>
