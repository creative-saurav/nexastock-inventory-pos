<x-guest-layout title="Login">

    <h1 class="mb-1">Welcome back</h1>
    <p class="text-muted mb-4">Sign in to your account to continue.</p>

    @if(session('status'))
        <div class="alert alert-success small">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="mb-3">

            <label for="email" class="form-label fw-medium">Email</label>

            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    placeholder="you@example.com"
                    required
                    autofocus
                    autocomplete="username"
                >
            </div>

            @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror

        </div>


        {{-- Password --}}
        <div class="mb-3">

            <div class="d-flex justify-content-between">
                <label for="password" class="form-label fw-medium">Password</label>
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="small text-decoration-none">Forgot password?</a>
                @endif
            </div>

            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Your password"
                    required
                    autocomplete="current-password"
                >
                <button type="button" class="btn btn-toggle-password" data-toggle-password="password" aria-label="Show password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>

            @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror

        </div>


        <div class="form-check mb-4">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label small">Remember me</label>
        </div>


        <button type="submit" class="btn btn-primary w-100 py-2">
            <i class="bi bi-box-arrow-in-right me-1"></i>
            Log in
        </button>

    </form>

    @if(Route::has('register'))
        <p class="text-center text-muted small mt-4 mb-0">
            New customer?
            <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">Create an account</a>
        </p>
    @endif

</x-guest-layout>
