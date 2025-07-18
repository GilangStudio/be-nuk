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
                
                <li class="nav-item {{ Route::is('home-page.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('home-page.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-home fs-2"></i>
                        </span>
                        <span class="nav-link-title">Home Page</span>
                    </a>
                </li>

                {{-- About Page Management --}}
                <li class="nav-item dropdown {{ Route::is('about-page.*') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#navbar-about" data-bs-toggle="dropdown" 
                       data-bs-auto-close="false" role="button" aria-expanded="{{ Route::is('about-page.*') ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-info-circle fs-2"></i>
                        </span>
                        <span class="nav-link-title">About Page</span>
                    </a>
                    <div class="dropdown-menu {{ Route::is('about-page.*') ? 'show' : '' }}" id="navbar-about">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item {{ Route::is('about-page.index') ? 'active' : '' }}" 
                                   href="{{ route('about-page.index') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="ti ti-settings"></i>
                                    </span>
                                    Page Settings
                                </a>
                                <a class="dropdown-item {{ Route::is('about-page.certifications.*') ? 'active' : '' }}" 
                                   href="{{ route('about-page.certifications.index') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="ti ti-certificate"></i>
                                    </span>
                                    Certifications
                                </a>
                                <a class="dropdown-item {{ Route::is('about-page.what-different.*') ? 'active' : '' }}" 
                                   href="{{ route('about-page.what-different.index') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="ti ti-star"></i>
                                    </span>
                                    What Different
                                </a>
                                <a class="dropdown-item {{ Route::is('about-page.why-choose.*') ? 'active' : '' }}" 
                                   href="{{ route('about-page.why-choose.index') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="ti ti-heart"></i>
                                    </span>
                                    Why Choose
                                </a>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item dropdown {{ Route::is('services.*') ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#navbar-services" data-bs-toggle="dropdown" 
                       data-bs-auto-close="false" role="button" aria-expanded="{{ Route::is('services.*') ? 'true' : 'false' }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-briefcase fs-2"></i>
                        </span>
                        <span class="nav-link-title">Services</span>
                    </a>
                    <div class="dropdown-menu {{ Route::is('services.*') ? 'show' : '' }}" id="navbar-services">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                <a class="dropdown-item {{ Route::is('services.index') ? 'active' : '' }}" 
                                   href="{{ route('services.index') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="ti ti-settings"></i>
                                    </span>
                                    Page Settings
                                </a>
                                <a class="dropdown-item {{ Route::is('services.services.*') ? 'active' : '' }}" 
                                   href="{{ route('services.services.index') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="ti ti-list"></i>
                                    </span>
                                    Services
                                </a>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item {{ Route::is('articles.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('articles.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-file-text fs-2"></i>
                        </span>
                        <span class="nav-link-title">Articles</span>
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