<!--  BEGIN SIDEBAR  -->
<aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
    <div class="container-fluid">
        <!-- BEGIN NAVBAR TOGGLER -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
            aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- END NAVBAR TOGGLER -->
        <!-- BEGIN NAVBAR LOGO -->
        <div class="navbar-brand navbar-brand-autodark">
            <a href="." aria-label="Tabler"><img src="/logo.png" alt="" class="navbar-brand-image"></a>
        </div>
        <!-- END NAVBAR LOGO -->
        
        <div class="collapse navbar-collapse" id="sidebar-menu">
            <!-- BEGIN NAVBAR MENU -->
            <ul class="navbar-nav pt-lg-3">
                <li class="nav-item {{ Route::is('dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block"><i
                                class="ti ti-dashboard fs-2"></i></span>
                        <span class="nav-link-title"> Dashboard </span>
                    </a>
                </li>
                <li class="nav-item {{ Route::is('home-page.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('home-page.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-home fs-2"></i>
                        </span>
                        <span class="nav-link-title">Home Page</span>
                    </a>
                </li>

                <li class="nav-item dropdown {{ Route::is('about-us.*') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="false"
                        role="button" aria-expanded="false">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-building-bank fs-2"></i>
                        </span>
                        <span class="nav-link-title">About Us</span>
                    </a>
                    <div class="dropdown-menu {{ Route::is('about-us.*') ? 'show' : '' }}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item {{ Route::is('about-us.index') ? 'active' : '' }}"
                                    href="{{ route('about-us.index') }}">
                                    <i class="ti ti-settings me-2"></i>
                                    Page Settings
                                </a>
                                <a class="dropdown-item {{ Route::is('about-us.executive-summary.*') ? 'active' : '' }}"
                                    href="{{ route('about-us.executive-summary.index') }}">
                                    <i class="ti ti-chart-line me-2"></i>
                                    Executive Summary
                                </a>
                                <a class="dropdown-item {{ Route::is('about-us.functions.*') ? 'active' : '' }}"
                                    href="{{ route('about-us.functions.index') }}">
                                    <i class="ti ti-tools me-2"></i>
                                    Functions
                                </a>
                                <a class="dropdown-item {{ Route::is('about-us.services.*') ? 'active' : '' }}"
                                    href="{{ route('about-us.services.index') }}">
                                    <i class="ti ti-heart-handshake me-2"></i>
                                    Services
                                </a>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item dropdown {{ Route::is('new-residents.*') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="false"
                        role="button" aria-expanded="false">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-users fs-2"></i>
                        </span>
                        <span class="nav-link-title">New Residents</span>
                    </a>
                    <div class="dropdown-menu {{ Route::is('new-residents.*') ? 'show' : '' }}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item {{ Route::is('new-residents.index') ? 'active' : '' }}"
                                    href="{{ route('new-residents.index') }}">
                                    <i class="ti ti-settings me-2"></i>
                                    Page Settings
                                </a>
                                <a class="dropdown-item {{ Route::is('new-residents.categories.*') ? 'active' : '' }}"
                                    href="{{ route('new-residents.categories.index') }}">
                                    <i class="ti ti-folder me-2"></i>
                                    Categories
                                </a>
                                <a class="dropdown-item {{ Route::is('new-residents.places.*') ? 'active' : '' }}"
                                    href="{{ route('new-residents.places.index') }}">
                                    <i class="ti ti-map-pin me-2"></i>
                                    Places
                                </a>
                                <a class="dropdown-item {{ Route::is('new-residents.transportation.*') ? 'active' : '' }}"
                                    href="{{ route('new-residents.transportation.index') }}">
                                    <i class="ti ti-car me-2"></i>
                                    Transportation
                                </a>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item dropdown {{ Route::is('services.*') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="false"
                        role="button" aria-expanded="false">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-tools fs-2"></i>
                        </span>
                        <span class="nav-link-title">Our Services</span>
                    </a>
                    <div class="dropdown-menu {{ Route::is('services.*') ? 'show' : '' }}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item {{ Route::is('services.index') ? 'active' : '' }}"
                                    href="{{ route('services.index') }}">
                                    <i class="ti ti-settings me-2"></i>
                                    Page Settings
                                </a>
                                <a class="dropdown-item {{ Route::is('services.sections.*') ? 'active' : '' }}"
                                    href="{{ route('services.sections.index') }}">
                                    <i class="ti ti-layout-grid me-2"></i>
                                    Service Sections
                                </a>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item {{ Route::is('e-town.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('e-town.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-device-mobile fs-2"></i>
                        </span>
                        <span class="nav-link-title">E-Town App</span>
                    </a>
                </li>

                <li class="nav-item dropdown {{ Route::is('news.*') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="false"
                        role="button" aria-expanded="false">
                        <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-news fs-2"></i></span>
                        <span class="nav-link-title"> News </span>
                    </a>
                    <div class="dropdown-menu {{ Route::is('news.*') ? 'show' : '' }}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item {{ Route::is('news.categories.*') ? 'active' : '' }}"
                                    href="{{ route('news.categories.index') }}">
                                    Category
                                </a>
                                <a class="dropdown-item {{ Route::is('news.*') && !Route::is('news.categories.*') ? 'active' : '' }}"
                                    href="{{ route('news.index') }}">
                                    All News
                                </a>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item dropdown {{ Route::is('partnerships.*') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="false"
                        role="button" aria-expanded="false">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-users-group fs-2"></i>
                        </span>
                        <span class="nav-link-title">Partnership & Programs</span>
                    </a>
                    <div class="dropdown-menu {{ Route::is('partnerships.*') ? 'show' : '' }}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item {{ Route::is('partnerships.index') ? 'active' : '' }}"
                                    href="{{ route('partnerships.index') }}">
                                    <i class="ti ti-settings me-2"></i>
                                    Page Settings
                                </a>
                                <a class="dropdown-item {{ Route::is('partnerships.items.*') ? 'active' : '' }}"
                                    href="{{ route('partnerships.items.index') }}">
                                    <i class="ti ti-users-group me-2"></i>
                                    Partnership Items
                                </a>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item dropdown {{ Route::is('careers.*') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="false"
                        role="button" aria-expanded="false">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-briefcase fs-2"></i>
                        </span>
                        <span class="nav-link-title">Career</span>
                    </a>
                    <div class="dropdown-menu {{ Route::is('careers.*') ? 'show' : '' }}">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item {{ Route::is('careers.index') ? 'active' : '' }}"
                                    href="{{ route('careers.index') }}">
                                    <i class="ti ti-settings me-2"></i>
                                    Page Settings
                                </a>
                                <a class="dropdown-item {{ Route::is('careers.positions.*') ? 'active' : '' }}"
                                    href="{{ route('careers.positions.index') }}">
                                    <i class="ti ti-briefcase me-2"></i>
                                    Job Positions
                                </a>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item {{ Route::is('promos.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('promos.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-photo fs-2"></i>
                        </span>
                        <span class="nav-link-title">Promos</span>
                    </a>
                </li>

                <li class="nav-item {{ Route::is('settings.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('settings.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-settings fs-2"></i>
                        </span>
                        <span class="nav-link-title">Settings</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-danger" href="{{ route('logout') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-logout fs-2"></i></span>
                        <span class="nav-link-title"> Logout </span>
                    </a>
                </li>

            </ul>
            <!-- END NAVBAR MENU -->
        </div>
    </div>
</aside>
<!--  END SIDEBAR  -->