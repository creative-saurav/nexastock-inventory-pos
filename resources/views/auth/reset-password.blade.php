<x-guest-layout title="Reset Password">

    <h1 class="mb-1">Choose a new password</h1>
    <p class="text-muted mb-4">Use at least 8 characters.</p>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-3">

            <label for="email" class="form-label fw-medium">Email</label>

            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', $request->email) }}"
                    required
                    autocomplete="username"
                >
            </div>

            @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror

        </div>


        <div class="mb-3">

            <label for="password" class="form-label fw-medium">New password</label>

            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    required
                    autofocus
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


        <div class="mb-4">

            <label for="password_confirmation" class="form-label fw-medium">Confirm new password</label>

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

            @error('password_confirmation')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror

        </div>


        <button type="submit" class="btn btn-primary w-100 py-2">
            <i class="bi bi-check-lg me-1"></i>
            Reset password
        </button>

    </form>

</x-guest-layout>
