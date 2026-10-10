<x-guest-layout title="Confirm Password">

    <h1 class="mb-1">Confirm your password</h1>
    <p class="text-muted mb-4">This is a secure area. Please enter your password to continue.</p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-4">

            <label for="password" class="form-label fw-medium">Password</label>

            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    required
                    autofocus
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

        <button type="submit" class="btn btn-primary w-100 py-2">
            Confirm
        </button>

    </form>

</x-guest-layout>
