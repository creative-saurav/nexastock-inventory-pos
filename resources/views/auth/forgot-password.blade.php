<x-guest-layout title="Forgot Password">

    <h1 class="mb-1">Forgot password?</h1>
    <p class="text-muted mb-4">Enter your email and we'll send you a link to choose a new password.</p>

    @if(session('status'))
        <div class="alert alert-success small">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">

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
                    autofocus
                >
            </div>

            @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror

        </div>

        <button type="submit" class="btn btn-primary w-100 py-2">
            <i class="bi bi-send me-1"></i>
            Email reset link
        </button>

    </form>

    <p class="text-center text-muted small mt-4 mb-0">
        Remembered it?
        <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">Back to login</a>
    </p>

</x-guest-layout>
