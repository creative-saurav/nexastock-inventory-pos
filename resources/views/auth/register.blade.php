<x-guest-layout title="Create Account">

    <h1 class="mb-1">Create your account</h1>
    <p class="text-muted mb-4">See your purchases and print invoices any time.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Name --}}
        <div class="mb-3">

            <label for="name" class="form-label fw-medium">Full name</label>

            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input
                    id="name"
                    type="text"
                    name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                >
            </div>

            @error('name')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror

        </div>


        {{-- Phone --}}
        <div class="mb-3">

            <label for="phone" class="form-label fw-medium">Phone</label>

            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                <input
                    id="phone"
                    type="tel"
                    name="phone"
                    class="form-control @error('phone') is-invalid @enderror"
                    value="{{ old('phone') }}"
                    placeholder="01XXXXXXXXX"
                    required
                    autocomplete="tel"
                >
            </div>

            @error('phone')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror

        </div>


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
                    required
                    autocomplete="username"
                >
            </div>

            @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror

        </div>


        {{-- Password --}}
        <div class="mb-3">

            <label for="password" class="form-label fw-medium">Password</label>

            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    required
                    autocomplete="new-password"
                >
                <button type="button" class="btn btn-toggle-password" data-toggle-password="password" aria-label="Show password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>

            @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror

        </div>


        {{-- Confirm Password --}}
        <div class="mb-4">

            <label for="password_confirmation" class="form-label fw-medium">Confirm password</label>

            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    class="form-control"
                    required
                    autocomplete="new-password"
                >
            </div>

        </div>


        <button type="submit" class="btn btn-primary w-100 py-2">
            <i class="bi bi-person-plus me-1"></i>
            Create account
        </button>

    </form>

    <p class="text-center text-muted small mt-4 mb-0">
        Already registered?
        <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">Log in</a>
    </p>

</x-guest-layout>
