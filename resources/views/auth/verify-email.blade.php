<x-guest-layout title="Verify Email">

    <h1 class="mb-1">Verify your email</h1>
    <p class="text-muted mb-4">
        Thanks for signing up! Please click the link we just emailed you. Didn't get it? We can send another.
    </p>

    @if(session('status') == 'verification-link-sent')
        <div class="alert alert-success small">
            A new verification link has been sent to your email address.
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center gap-2">

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-send me-1"></i>
                Resend email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link text-muted text-decoration-none">
                Log out
            </button>
        </form>

    </div>

</x-guest-layout>
