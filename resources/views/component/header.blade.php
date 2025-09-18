{{-- header --}}

<nav class="container-fluid navbar navbar-expand-lg navbar-light bg-white px-4 px-lg-5 py-3 py-lg-0 ">
    <a href="/" class="navbar-brand p-0">
        {{-- <h1 class="m-0">DGital</h1> --}}
        {{-- <img src="{{asset('asset/img/logo-icon.jpg')}}" alt="" class="logo"> --}}
        <img src="{{ asset('asset/img/logo-w-removebg-preview.png') }}" alt="Logo" class="logo">

        <!-- <img src="img/logo.png" alt="Logo"> -->
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="fa fa-bars"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav mx-auto py-0">
            <a href="/" class="nav-item nav-link {{ request()->is('/') ? 'active ' : '' }}">
                Home
            </a>
             <a href="{{ route('packages') }}" class="nav-item nav-link {{ request()->is('packages') ? 'active ' : '' }}">
                Packages
            </a>
            <a href="{{ route('about') }}" class="nav-item nav-link {{ request()->routeIs('about') ? 'active ' : '' }}">
                About
            </a>
            <a href="{{route('blog')}}" class="nav-item nav-link {{ request()->routeIs('blog') ? 'active ' : '' }}">
                Blog
            </a>
            <a href="{{ route('service') }}" class="nav-item nav-link {{ request()->routeIs('service') ? 'active ' : '' }}">
                Service
            </a>
            <a href="{{ route('project') }}" class="nav-item nav-link {{ request()->routeIs('project') ? 'active ' : '' }}">
                Project
            </a>
            <a href="{{ route('contact') }}" class="nav-item nav-link {{ request()->routeIs('contact') ? 'active ' : '' }}">
                Contact
            </a>
        </div>
        <button style="background-color: #040505; color: white;" class=" w-auto rounded-pill py-2 px-4 px-sm-3 mt-3 mt-sm-0">
            <a href="https://post.realvictorygroups.com/" class="text-white">  Download Your Post</a>
        </button>

    </div>
</nav>
