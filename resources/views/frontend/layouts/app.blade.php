<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@hasSection('title')@yield('title') - @endif{{ setting('name') }}</title>

    <meta name="description" content="@yield('meta_description', setting('name') . ' - ' . setting('tagline'))">

    @if(setting('logo'))
        <link rel="icon" href="{{ asset(setting('logo')) }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">

    @stack('styles')

</head>

<body>

    @php
        $phoneHref = setting('phone') ? 'tel:' . preg_replace('/[^0-9+]/', '', setting('phone')) : null;
    @endphp


    {{-- =========================
         TOP BAR
    ========================== --}}
    <div class="topbar d-none d-md-block">

        <div class="container d-flex justify-content-between align-items-center gap-3">

            <div class="d-flex gap-4">

                @if(setting('address'))
                    <span><i class="bi bi-geo-alt me-1"></i>{{ \Illuminate\Support\Str::limit(setting('address'), 60) }}</span>
                @endif

                @if(setting('email'))
                    <a href="mailto:{{ setting('email') }}"><i class="bi bi-envelope me-1"></i>{{ setting('email') }}</a>
                @endif

            </div>

            <div class="d-flex gap-4">

                <span><i class="bi bi-shop me-1"></i>Visit us for in-store shopping</span>

                @if($phoneHref)
                    <a href="{{ $phoneHref }}"><i class="bi bi-telephone me-1"></i>{{ setting('phone') }}</a>
                @endif

            </div>

        </div>

    </div>



    {{-- =========================
         HEADER
    ========================== --}}
    <header class="site-header" id="siteHeader">

        <div class="container">

            <div class="header-main d-flex align-items-center">

                <button
                    class="icon-btn d-lg-none"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#mobileMenu"
                    aria-controls="mobileMenu"
                    aria-label="Open menu"
                >
                    <i class="bi bi-list"></i>
                </button>


                {{-- Logo --}}
                <a href="{{ route('home') }}" class="brand-link">

                    <div class="brand-icon {{ setting('logo') ? 'has-logo' : '' }}">
                        @if(setting('logo'))
                            <img src="{{ asset(setting('logo')) }}" alt="{{ setting('name') }}">
                        @else
                            <i class="bi bi-box-seam"></i>
                        @endif
                    </div>

                    <div class="d-none d-sm-block">
                        <span class="brand-name">{{ setting('name') }}</span>
                        @if(setting('tagline'))
                            <span class="brand-tagline">{{ setting('tagline') }}</span>
                        @endif
                    </div>

                </a>


                {{-- Main navigation --}}
                <nav class="main-nav d-none d-lg-flex" aria-label="Main">

                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>

                    <a href="{{ route('shop.products') }}" class="{{ request()->routeIs('shop.products*') ? 'active' : '' }}">Shop</a>

                    @if($navCategories->isNotEmpty())
                        <div class="dropdown">

                            <a href="#" class="dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Categories
                            </a>

                            <ul class="dropdown-menu nav-dropdown">

                                @foreach($navCategories as $navCategory)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('shop.products', ['category' => $navCategory->slug]) }}">
                                            {{ $navCategory->name }}
                                            <span class="count">{{ $navCategory->products_count }}</span>
                                        </a>
                                    </li>
                                @endforeach

                                <li><hr class="dropdown-divider"></li>

                                <li>
                                    <a class="dropdown-item fw-semibold text-primary" href="{{ route('shop.products') }}">
                                        All products
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </li>

                            </ul>

                        </div>
                    @endif

                    <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>

                </nav>


                <div class="header-actions d-flex align-items-center ms-auto">

                    {{-- Search --}}
                    <form action="{{ route('shop.products') }}" method="GET" class="header-search d-none d-md-block" role="search">
                        <i class="bi bi-search" aria-hidden="true"></i>
                        <input
                            type="search"
                            name="search"
                            class="form-control"
                            placeholder="Search products..."
                            value="{{ request()->routeIs('shop.products') ? request('search') : '' }}"
                            aria-label="Search products"
                        >
                    </form>


                    {{-- Account --}}
                    @auth

                        @php
                            $authUser = auth()->user();
                            $isCustomer = $authUser->hasRole('customer');
                            $initial = strtoupper(mb_substr($authUser->name, 0, 1));
                        @endphp

                        <div class="dropdown">

                            <button class="account-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Account menu">

                                <span class="avatar">
                                    @if($authUser->image)
                                        <img src="{{ asset($authUser->image) }}" alt="">
                                    @else
                                        {{ $initial }}
                                    @endif
                                </span>

                                <span class="account-name d-none d-xl-block">{{ \Illuminate\Support\Str::of($authUser->name)->explode(' ')->first() }}</span>

                                <i class="bi bi-chevron-down small d-none d-sm-inline"></i>

                            </button>

                            <ul class="dropdown-menu dropdown-menu-end account-menu">

                                <li class="account-head">
                                    <span class="avatar avatar-lg">
                                        @if($authUser->image)
                                            <img src="{{ asset($authUser->image) }}" alt="">
                                        @else
                                            {{ $initial }}
                                        @endif
                                    </span>
                                    <div class="text-truncate">
                                        <strong class="d-block text-truncate">{{ $authUser->name }}</strong>
                                        <small class="text-muted d-block text-truncate">{{ $authUser->email ?? $authUser->phone }}</small>
                                    </div>
                                </li>

                                <li><hr class="dropdown-divider"></li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('dashboard') }}">
                                        <i class="bi bi-grid"></i>
                                        {{ $isCustomer ? 'My Account' : 'Dashboard' }}
                                    </a>
                                </li>

                                @if($isCustomer)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('customer.purchases') }}">
                                            <i class="bi bi-bag-check"></i>
                                            My Purchases
                                        </a>
                                    </li>
                                @endif

                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person"></i>
                                        Profile
                                    </a>
                                </li>

                                <li><hr class="dropdown-divider"></li>

                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right"></i>
                                            Logout
                                        </button>
                                    </form>
                                </li>

                            </ul>

                        </div>

                    @else

                        <a href="{{ route('login') }}" class="btn btn-soft d-none d-sm-inline-flex">Sign in</a>

                        <a href="{{ route('login') }}" class="icon-btn d-sm-none" aria-label="Sign in">
                            <i class="bi bi-person"></i>
                        </a>

                        @if(Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary d-none d-sm-inline-flex">Register</a>
                        @endif

                    @endauth

                </div>

            </div>

        </div>

    </header>



    {{-- =========================
         MOBILE MENU
    ========================== --}}
    <div class="offcanvas offcanvas-start mobile-menu" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">

        <div class="offcanvas-header">
            <h5 class="offcanvas-title fw-bold" id="mobileMenuLabel">{{ setting('name') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">

            <form action="{{ route('shop.products') }}" method="GET" class="mb-3" role="search">
                <div class="input-group">
                    <input type="search" name="search" class="form-control" placeholder="Search products..." aria-label="Search products">
                    <button class="btn btn-primary" type="submit" aria-label="Search"><i class="bi bi-search"></i></button>
                </div>
            </form>

            <a href="{{ route('home') }}" class="menu-link {{ request()->routeIs('home') ? 'active' : '' }}">Home <i class="bi bi-chevron-right"></i></a>
            <a href="{{ route('shop.products') }}" class="menu-link {{ request()->routeIs('shop.products*') ? 'active' : '' }}">Shop <i class="bi bi-chevron-right"></i></a>
            <a href="{{ route('contact') }}" class="menu-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact <i class="bi bi-chevron-right"></i></a>

            @if($navCategories->isNotEmpty())
                <div class="menu-label">Categories</div>

                @foreach($navCategories as $navCategory)
                    <a href="{{ route('shop.products', ['category' => $navCategory->slug]) }}" class="menu-link">
                        {{ $navCategory->name }}
                        <small class="text-muted">{{ $navCategory->products_count }}</small>
                    </a>
                @endforeach
            @endif

            <div class="menu-label">Account</div>

            @auth
                <a href="{{ route('dashboard') }}" class="menu-link">My Account <i class="bi bi-person"></i></a>
            @else
                <a href="{{ route('login') }}" class="menu-link">Sign in <i class="bi bi-box-arrow-in-right"></i></a>
                <a href="{{ route('register') }}" class="menu-link">Create account <i class="bi bi-person-plus"></i></a>
            @endauth

        </div>

    </div>



    <main>
        @yield('content')
    </main>



    {{-- =========================
         FOOTER
    ========================== --}}
    <div class="container">

        <div class="footer-cta d-flex flex-wrap justify-content-between align-items-center gap-4">

            <div>
                <h3 class="mb-1">Can't find what you're looking for?</h3>
                <p class="mb-0 opacity-75">Give us a call and we'll check if we can get it for you.</p>
            </div>

            <div class="d-flex flex-wrap gap-2">

                @if($phoneHref)
                    <a href="{{ $phoneHref }}" class="btn btn-white btn-lg">
                        <i class="bi bi-telephone me-1"></i>
                        {{ setting('phone') }}
                    </a>
                @endif

                <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
                    Contact us
                </a>

            </div>

        </div>

    </div>


    <footer class="site-footer">

        <div class="container">

            <div class="row g-4 g-lg-5">

                <div class="col-lg-4">

                    <a href="{{ route('home') }}" class="brand-link mb-3">

                        <div class="brand-icon {{ setting('logo') ? 'has-logo' : '' }}">
                            @if(setting('logo'))
                                <img src="{{ asset(setting('logo')) }}" alt="{{ setting('name') }}">
                            @else
                                <i class="bi bi-box-seam"></i>
                            @endif
                        </div>

                        <div>
                            <span class="brand-name">{{ setting('name') }}</span>
                            @if(setting('tagline'))
                                <span class="brand-tagline">{{ setting('tagline') }}</span>
                            @endif
                        </div>

                    </a>

                    <p class="mt-3 mb-0" style="max-width: 340px;">
                        Quality products at fair prices. Browse our range online, then visit or call us to buy.
                    </p>

                </div>


                <div class="col-6 col-lg-2">

                    <h6>Shop</h6>

                    <ul class="list-unstyled footer-links mb-0">
                        <li><a href="{{ route('shop.products') }}">All Products</a></li>
                        @foreach($navCategories->take(4) as $navCategory)
                            <li><a href="{{ route('shop.products', ['category' => $navCategory->slug]) }}">{{ $navCategory->name }}</a></li>
                        @endforeach
                    </ul>

                </div>


                <div class="col-6 col-lg-2">

                    <h6>Account</h6>

                    <ul class="list-unstyled footer-links mb-0">
                        @auth
                            <li><a href="{{ route('dashboard') }}">My Account</a></li>
                        @else
                            <li><a href="{{ route('login') }}">Sign in</a></li>
                            <li><a href="{{ route('register') }}">Create account</a></li>
                        @endauth
                        <li><a href="{{ route('contact') }}">Contact us</a></li>
                    </ul>

                </div>


                <div class="col-lg-4">

                    <h6>Get in touch</h6>

                    <ul class="list-unstyled footer-contact mb-0">

                        @if(setting('address'))
                            <li><i class="bi bi-geo-alt"></i><span>{{ setting('address') }}</span></li>
                        @endif

                        @if($phoneHref)
                            <li><i class="bi bi-telephone"></i><a href="{{ $phoneHref }}">{{ setting('phone') }}</a></li>
                        @endif

                        @if(setting('email'))
                            <li><i class="bi bi-envelope"></i><a href="mailto:{{ setting('email') }}">{{ setting('email') }}</a></li>
                        @endif

                    </ul>

                </div>

            </div>


            <div class="footer-bottom d-flex flex-wrap justify-content-between gap-2">
                <span>© {{ date('Y') }} {{ setting('name') }}. All rights reserved.</span>
                <span>Prices are in BDT and may change without notice.</span>
            </div>

        </div>

    </footer>


    <button type="button" class="back-to-top" id="backToTop" aria-label="Back to top">
        <i class="bi bi-arrow-up"></i>
    </button>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        "use strict";

        (function () {

            const header = document.getElementById('siteHeader');
            const toTop = document.getElementById('backToTop');

            function onScroll() {
                header.classList.toggle('is-scrolled', window.scrollY > 10);
                toTop.classList.toggle('show', window.scrollY > 600);
            }

            window.addEventListener('scroll', onScroll, { passive: true });
            onScroll();

            toTop.addEventListener('click', function () {
                window.scrollTo({ top: 0 });
            });

            // Fade sections in as they scroll into view
            const items = document.querySelectorAll('.reveal');

            if ('IntersectionObserver' in window) {

                const observer = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                            observer.unobserve(entry.target);
                        }
                    });
                }, { rootMargin: '0px 0px -60px 0px' });

                items.forEach(function (item) { observer.observe(item); });

            } else {
                items.forEach(function (item) { item.classList.add('is-visible'); });
            }

        })();
    </script>

    @stack('scripts')

</body>

</html>
