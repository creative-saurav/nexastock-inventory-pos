<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Account' }} - {{ setting('name') }}</title>

    @if(setting('logo'))
        <link rel="icon" href="{{ asset(setting('logo')) }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="{{ asset('frontend/css/style.css') }}"
    >

</head>

<body>

    <div class="auth-wrapper">

        {{-- BRAND SIDE --}}
        <div class="auth-side">

            <a href="{{ route('home') }}" class="brand-link">

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

            <div style="max-width: 440px;">

                <h2 class="mb-3">Manage your shop. Track every purchase.</h2>

                <p class="mb-4">
                    Staff sign in to run the store. Customers sign in to see their purchases and print invoices.
                </p>

                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #a5b4fc;"></i>Point of sale and printed invoices</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill me-2" style="color: #a5b4fc;"></i>Live stock and low stock alerts</li>
                    <li><i class="bi bi-check-circle-fill me-2" style="color: #a5b4fc;"></i>Your full purchase history online</li>
                </ul>

            </div>

            <small style="color: #64748b;">© {{ date('Y') }} {{ setting('name') }}</small>

        </div>


        {{-- FORM SIDE --}}
        <div class="auth-main">

            <div class="w-100" style="max-width: 420px;">

                <a href="{{ route('home') }}" class="brand-link justify-content-center mb-4 d-lg-none">

                    <div class="brand-icon {{ setting('logo') ? 'has-logo' : '' }}">
                        @if(setting('logo'))
                            <img src="{{ asset(setting('logo')) }}" alt="{{ setting('name') }}">
                        @else
                            <i class="bi bi-box-seam"></i>
                        @endif
                    </div>

                    <span class="brand-name">{{ setting('name') }}</span>

                </a>

                <div class="panel auth-card">
                    {{ $slot }}
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('home') }}" class="text-muted text-decoration-none small">
                        <i class="bi bi-arrow-left me-1"></i>
                        Back to website
                    </a>
                </div>

            </div>

        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Show/hide password fields
        document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
            button.addEventListener('click', function () {
                const input = document.getElementById(button.dataset.togglePassword);
                const show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                button.querySelector('i').className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
                button.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
            });
        });
    </script>

</body>

</html>
