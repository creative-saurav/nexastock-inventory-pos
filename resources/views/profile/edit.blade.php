@extends('backend.layouts.admin')

@section('page-title', 'My Profile')
@section('title', 'My Profile')


@section('content')

<div class="container-fluid">

    {{-- PROFILE HEADER --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body p-4 d-flex flex-wrap align-items-center gap-4">

            <div
                class="rounded-circle overflow-hidden d-flex align-items-center justify-content-center bg-primary-subtle text-primary fw-bold flex-shrink-0"
                style="width: 88px; height: 88px; font-size: 34px;"
            >
                @if($user->image)
                    <img
                        src="{{ asset($user->image) }}"
                        alt="{{ $user->name }}"
                        style="width: 100%; height: 100%; object-fit: cover;"
                    >
                @else
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                @endif
            </div>

            <div class="flex-grow-1">

                <h4 class="fw-bold mb-1">{{ $user->name }}</h4>

                <div class="d-flex flex-wrap gap-3 text-muted small">
                    <span><i class="bi bi-envelope me-1"></i>{{ $user->email }}</span>

                    @if($user->phone)
                        <span><i class="bi bi-telephone me-1"></i>{{ $user->phone }}</span>
                    @endif

                    <span><i class="bi bi-calendar3 me-1"></i>Joined {{ $user->created_at->format('d M Y') }}</span>
                </div>

            </div>

            <span class="badge bg-primary-subtle text-primary px-3 py-2 fs-6">
                <i class="bi bi-shield-check me-1"></i>
                {{ ucfirst($user->role) }}
            </span>

        </div>

    </div>



    <div class="row g-4">

        {{-- PROFILE INFORMATION --}}
        <div class="col-xl-7">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-person me-2 text-primary"></i>
                        Profile Information
                    </h6>
                    <small class="text-muted">Update your name, contact details and photo.</small>
                </div>

                <div class="card-body p-4">

                    <form
                        action="{{ route('profile.update') }}"
                        method="POST"
                        enctype="multipart/form-data"
                    >

                        @csrf
                        @method('PATCH')

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="form-label">
                                    Full Name <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}"
                                    required
                                >

                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    Phone
                                </label>

                                <input
                                    type="text"
                                    name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $user->phone) }}"
                                >

                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>


                            <div class="col-12">

                                <label class="form-label">
                                    Email Address <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}"
                                    required
                                >

                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>


                            <div class="col-12">

                                <label class="form-label">
                                    Profile Photo
                                </label>

                                <input
                                    type="file"
                                    name="image"
                                    class="form-control @error('image') is-invalid @enderror"
                                    accept="image/png,image/jpeg,image/webp"
                                >

                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <small class="text-muted">JPG, PNG or WEBP, max 2 MB.</small>

                                @if($user->image)
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="remove_image">
                                        <label class="form-check-label text-danger" for="remove_image">
                                            Remove current photo
                                        </label>
                                    </div>
                                @endif

                            </div>

                        </div>


                        <div class="d-flex justify-content-end mt-4">

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>
                                Save Changes
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>



        {{-- CHANGE PASSWORD --}}
        <div class="col-xl-5">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-shield-lock me-2 text-primary"></i>
                        Change Password
                    </h6>
                    <small class="text-muted">Use a long password that you don't use anywhere else.</small>
                </div>

                <div class="card-body p-4">

                    <form
                        action="{{ route('password.update') }}"
                        method="POST"
                    >

                        @csrf
                        @method('PUT')

                        <div class="mb-3">

                            <label class="form-label">
                                Current Password
                            </label>

                            <input
                                type="password"
                                name="current_password"
                                class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                                autocomplete="current-password"
                                required
                            >

                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                New Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                                autocomplete="new-password"
                                required
                            >

                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>


                        <div class="mb-4">

                            <label class="form-label">
                                Confirm New Password
                            </label>

                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                                autocomplete="new-password"
                                required
                            >

                            @error('password_confirmation', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>


                        <button type="submit" class="btn btn-dark w-100">
                            <i class="bi bi-key me-1"></i>
                            Update Password
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@if(session('status') === 'password-updated')

    @push('scripts')
        <script>
            "use strict";

            $(function () {
                success('Password updated successfully!');
            });
        </script>
    @endpush

@endif
