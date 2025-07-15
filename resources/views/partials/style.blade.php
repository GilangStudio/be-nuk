<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="{{ asset('css/tabler.min.css') }}" rel="stylesheet" />
<link href="{{ asset('icons/tabler-icons.min.css') }}" rel="stylesheet" />
<!-- END GLOBAL MANDATORY STYLES -->

<style>
    @import url("https://rsms.me/inter/inter.css");

    .modal-blur {
        margin: 0 !important;
    }

    .navbar-vertical.navbar-expand-lg .navbar-collapse .dropdown-menu .dropdown-item.active, .navbar-vertical.navbar-expand-lg .navbar-collapse .dropdown-menu .dropdown-item:active,
    .dropdown-item:focus, .dropdown-item:hover {
        color: var(--tblr-navbar-active-color) !important;
        background-color: var(--tblr-dropdown-link-hover-bg) !important;
    }

    #search-input {
        min-width: 200px;
    }
</style>

@stack('styles')