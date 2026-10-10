<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', 'Dashboard') - {{ setting('name') }}
    </title>

    {{-- Bootstrap CSS --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- Bootstrap Icons --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >


    {{-- Backend CSS --}}
    <link
        rel="stylesheet"
        href="{{ asset('backend/css/style.css') }}"
    >

    @stack('styles')
</head>

<body>

    <div class="backend-wrapper">

        {{-- =========================
            SIDEBAR
        ========================== --}}
        <aside class="sidebar" id="sidebar">

            {{-- Brand --}}
            <div class="sidebar-brand">

                <a href="{{ route('dashboard') }}" class="brand-link">

                    <div class="brand-icon {{ setting('logo') ? 'has-logo' : '' }}">
                        @if(setting('logo'))
                            <img src="{{ asset(setting('logo')) }}" alt="{{ setting('name') }}">
                        @else
                            <i class="bi bi-box-seam"></i>
                        @endif
                    </div>

                    <div class="brand-content">
                        <span class="brand-name">{{ setting('name') }}</span>
                        <small>{{ setting('tagline') }}</small>
                    </div>

                </a>

            </div>


            {{-- Sidebar Navigation --}}
            <div class="sidebar-menu">

                <div class="menu-label">
                    MAIN
                </div>

                <ul class="nav flex-column">

                    {{-- Dashboard --}}
                    <li class="nav-item">

                        <a
                            href="{{ route('dashboard') }}"
                            class="nav-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }}"
                        >
                            <span class="menu-icon">
                                <i class="bi bi-grid-1x2-fill"></i>
                            </span>

                            <span>Dashboard</span>
                        </a>

                    </li>

                </ul>


                {{-- Staff Panel --}}
                @if(auth()->user()->hasRole('staff'))

                    <div class="menu-label mt-4">
                        INVENTORY
                    </div>

                    <ul class="nav flex-column">

                        <li class="nav-item">

                            <a
                                href="{{ route('staff.stock') }}"
                                class="nav-link {{ request()->routeIs('staff.stock') ? 'active' : '' }}"
                            >

                                <span class="menu-icon">
                                    <i class="bi bi-boxes"></i>
                                </span>

                                <span>Stock List</span>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a
                                href="{{ route('staff.purchases') }}"
                                class="nav-link {{ request()->routeIs('staff.purchases*') ? 'active' : '' }}"
                            >

                                <span class="menu-icon">
                                    <i class="bi bi-truck"></i>
                                </span>

                                <span>Incoming Stock</span>

                            </a>

                        </li>

                    </ul>

                @endif


                {{-- Customer Panel --}}
                @if(auth()->user()->hasRole('customer'))

                    <div class="menu-label mt-4">
                        MY ACCOUNT
                    </div>

                    <ul class="nav flex-column">

                        <li class="nav-item">

                            <a
                                href="{{ route('customer.purchases') }}"
                                class="nav-link {{ request()->routeIs('customer.purchases*') ? 'active' : '' }}"
                            >

                                <span class="menu-icon">
                                    <i class="bi bi-bag-check"></i>
                                </span>

                                <span>My Purchases</span>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a
                                href="{{ route('profile.edit') }}"
                                class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"
                            >

                                <span class="menu-icon">
                                    <i class="bi bi-person-circle"></i>
                                </span>

                                <span>My Profile</span>

                            </a>

                        </li>

                    </ul>

                @endif


                @if(auth()->user()->hasRole('admin', 'manager'))

                {{-- Inventory --}}
                <div class="menu-label mt-4">
                    INVENTORY
                </div>

                <ul class="nav flex-column">

                    <li class="nav-item">

                        <a
                            href="{{ route('products') }}"
                            class="nav-link {{ request()->routeIs('products*') ? 'active' : '' }}"
                        >

                            <span class="menu-icon">
                                <i class="bi bi-box-seam"></i>
                            </span>

                            <span>Products</span>

                        </a>

                    </li>


                    <li class="nav-item">

                        <a
                            href="{{ route('categories') }}"
                            class="nav-link {{ request()->routeIs('categories*') ? 'active' : '' }}"
                        >

                            <span class="menu-icon">
                                <i class="bi bi-tags"></i>
                            </span>

                            <span>Categories</span>

                        </a>

                    </li>


                    <li class="nav-item">

                         <a
                            href="{{ route('brands') }}"
                            class="nav-link {{ request()->routeIs('brands*') ? 'active' : '' }}"
                        >

                            <span class="menu-icon">
                                <i class="bi bi-award"></i>
                            </span>

                            <span>Brands</span>

                        </a>

                    </li>
                    <li class="nav-item">

                         <a
                            href="{{ route('units') }}"
                            class="nav-link {{ request()->routeIs('units*') ? 'active' : '' }}"
                        >

                            <span class="menu-icon">
                                <i class="bi bi-speedometer2"></i>
                            </span>

                            <span>Units</span>

                        </a>

                    </li>


                    <li class="nav-item">

                        <a
                            href="{{ route('suppliers') }}"
                            class="nav-link {{ request()->routeIs('suppliers*') ? 'active' : '' }}"
                        >

                            <span class="menu-icon">
                                <i class="bi bi-truck"></i>
                            </span>

                            <span>Suppliers</span>

                        </a>

                    </li>


                </ul>

                @endif


                @if(auth()->user()->hasRole('admin', 'manager', 'cashier'))

                {{-- Sales --}}
                <div class="menu-label mt-4">
                    SALES
                </div>

                <ul class="nav flex-column">

                    <li class="nav-item">

                        <a
                            href="{{ route('pos') }}"
                            class="nav-link {{ request()->routeIs('pos*') ? 'active' : '' }}"
                        >

                            <span class="menu-icon">
                                <i class="bi bi-cart-check"></i>
                            </span>

                            <span>POS</span>

                            <span class="menu-badge">
                                New
                            </span>

                        </a>

                    </li>


                    <li class="nav-item">

                        <a
                            href="{{ route('sales') }}"
                            class="nav-link {{ request()->routeIs('sales*') ? 'active' : '' }}"
                        >

                            <span class="menu-icon">
                                <i class="bi bi-receipt"></i>
                            </span>

                            <span>Sales</span>

                        </a>

                    </li>

                </ul>

                @endif


                @if(auth()->user()->hasRole('admin', 'manager'))

                {{-- Purchase --}}
                <div class="menu-label mt-4">
                    PURCHASE
                </div>

                <ul class="nav flex-column">

                    <li class="nav-item">

                        <a
                            href="{{ route('purchases') }}"
                            class="nav-link {{ request()->routeIs('purchases*') ? 'active' : '' }}"
                        >

                            <span class="menu-icon">
                                <i class="bi bi-bag-plus"></i>
                            </span>

                            <span>Purchases</span>

                        </a>

                    </li>


                    <li class="nav-item">

                        <a
                            href="{{ route('expenses') }}"
                            class="nav-link {{ request()->routeIs('expenses*', 'expense_categories*') ? 'active' : '' }}"
                        >

                            <span class="menu-icon">
                                <i class="bi bi-wallet2"></i>
                            </span>

                            <span>Expenses</span>

                        </a>

                    </li>

                </ul>

                @endif


                @if(auth()->user()->hasRole('admin', 'manager'))

                {{-- Reports --}}
                <div class="menu-label mt-4">
                    ANALYTICS
                </div>

                <ul class="nav flex-column">

                    <li class="nav-item">

                        <a
                            href="{{ route('reports') }}"
                            class="nav-link {{ request()->routeIs('reports*') ? 'active' : '' }}"
                        >

                            <span class="menu-icon">
                                <i class="bi bi-bar-chart-line"></i>
                            </span>

                            <span>Reports</span>

                        </a>

                    </li>

                </ul>

                @endif


                {{-- System --}}
                @if(auth()->user()->hasRole('admin'))

                    <div class="menu-label mt-4">
                        SYSTEM
                    </div>

                    <ul class="nav flex-column">

                        <li class="nav-item">

                            <a
                            href="{{ route('users') }}"
                            class="nav-link {{ request()->routeIs('users*') ? 'active' : '' }}"
                        >

                                <span class="menu-icon">
                                    <i class="bi bi-person-gear"></i>
                                </span>

                                <span>Users</span>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a
                                href="{{ route('settings') }}"
                                class="nav-link {{ request()->routeIs('settings*') ? 'active' : '' }}"
                            >

                                <span class="menu-icon">
                                    <i class="bi bi-gear"></i>
                                </span>

                                <span>Settings</span>

                            </a>

                        </li>

                    </ul>

                @endif

            </div>


            {{-- Sidebar Bottom --}}
            <div class="sidebar-footer">

                <div class="system-status">

                    <span class="status-dot"></span>

                    <div>
                        <strong>System Online</strong>
                        <small>Everything is working</small>
                    </div>

                </div>

            </div>

        </aside>


        {{-- =========================
            MAIN AREA
        ========================== --}}
        <div class="main-area">


            {{-- =========================
                TOP NAVBAR
            ========================== --}}
            <header class="topbar">

                <div class="topbar-left">

                    {{-- Mobile Sidebar Button --}}
                    <button
                        type="button"
                        class="sidebar-toggle"
                        id="sidebarToggle"
                    >
                        <i class="bi bi-list"></i>
                    </button>

                    <div class="page-heading">

                        <h6>
                            @yield('page-title', 'Dashboard')
                        </h6>

                        <span>
                            {{ auth()->user()->hasRole('customer') ? 'Your purchases at ' . setting('name') : 'Manage your business efficiently' }}
                        </span>

                    </div>

                </div>


                <div class="topbar-right">


                    {{-- Search --}}
                    <button
                        type="button"
                        class="topbar-action"
                        title="Search"
                    >
                        <i class="bi bi-search"></i>
                    </button>


                    {{-- Notifications --}}
                    <button
                        type="button"
                        class="topbar-action notification-button"
                        title="Notifications"
                    >
                        <i class="bi bi-bell"></i>

                        <span class="notification-dot"></span>

                    </button>


                    {{-- Divider --}}
                    <div class="topbar-divider"></div>


                    {{-- User --}}
                    <div class="user-dropdown">

                        <button
                            class="user-profile"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >

                            <div class="user-avatar">
                                @if(auth()->user()->image)
                                    <img src="{{ asset(auth()->user()->image) }}" alt="{{ auth()->user()->name }}">
                                @else
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                @endif
                            </div>

                            <div class="user-info">

                                <strong>
                                    {{ auth()->user()->name }}
                                </strong>

                                <small>
                                    {{ ucfirst(auth()->user()->role) }}
                                </small>

                            </div>

                            <i class="bi bi-chevron-down user-arrow"></i>

                        </button>


                        <ul class="dropdown-menu dropdown-menu-end user-menu">

                            <li class="user-menu-header">

                                <strong>
                                    {{ auth()->user()->name }}
                                </strong>

                                <span>
                                    {{ auth()->user()->email }}
                                </span>

                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>

                                <a
                                    class="dropdown-item"
                                    href="{{ route('profile.edit') }}"
                                >
                                    <i class="bi bi-person"></i>
                                    Profile
                                </a>

                            </li>

                            @if(auth()->user()->hasRole('admin'))

                                <li>

                                    <a
                                        class="dropdown-item"
                                        href="{{ route('settings') }}"
                                    >
                                        <i class="bi bi-gear"></i>
                                        Settings
                                    </a>

                                </li>

                            @endif

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>

                                <form
                                    method="POST"
                                    action="{{ route('logout') }}"
                                >
                                    @csrf

                                    <button
                                        type="submit"
                                        class="dropdown-item logout-item"
                                    >
                                        <i class="bi bi-box-arrow-right"></i>
                                        Logout
                                    </button>

                                </form>

                            </li>

                        </ul>

                    </div>

                </div>

            </header>


            {{-- =========================
                PAGE CONTENT
            ========================== --}}
            <main class="content-area">


                @yield('content')

            </main>


            {{-- =========================
                FOOTER
            ========================== --}}
            <footer class="backend-footer">

                <div>
                    © {{ date('Y') }} <strong>{{ setting('name') }}</strong>. All rights reserved.
                </div>

                <div>
                    Inventory & POS Management System
                </div>

            </footer>

        </div>

    </div>


    {{-- Mobile Sidebar Overlay --}}
    <div
        class="sidebar-overlay"
        id="sidebarOverlay"
    ></div>


    {{-- Bootstrap JS --}}
    {{-- jQuery --}}
    <script
        src="https://code.jquery.com/jquery-3.7.1.min.js">
    </script>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>


    

    {{-- Backend JS --}}
    <script
        src="{{ asset('backend/js/scripts.js') }}">
    </script>
    @include('layouts.modal')
    @include('layouts.toaster')

    @stack('scripts')

    

</body>

</html>