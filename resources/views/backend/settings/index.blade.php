@extends('backend.layouts.admin')

@section('page-title', 'Settings')
@section('title', 'Settings')


@section('content')

<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="mb-4">

        <h4 class="mb-1 fw-semibold">
            Settings
        </h4>

        <p class="text-muted mb-0">
            Shop information shown in the panel and on printed invoices.
        </p>

    </div>


    <form
        action="{{ route('settings.update') }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf

        <div class="row g-4">

            <div class="col-xl-8">

                {{-- SHOP INFORMATION --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-shop me-2 text-primary"></i>
                            Shop Information
                        </h6>
                    </div>

                    <div class="card-body p-4">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="form-label">
                                    Shop Name <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', setting('name')) }}"
                                    required
                                >

                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    Tagline
                                </label>

                                <input
                                    type="text"
                                    name="tagline"
                                    class="form-control @error('tagline') is-invalid @enderror"
                                    placeholder="Example: Inventory & POS"
                                    value="{{ old('tagline', setting('tagline')) }}"
                                >

                                @error('tagline')
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
                                    value="{{ old('phone', setting('phone')) }}"
                                >

                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', setting('email')) }}"
                                >

                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>


                            <div class="col-12">

                                <label class="form-label">
                                    Address
                                </label>

                                <textarea
                                    name="address"
                                    class="form-control @error('address') is-invalid @enderror"
                                    rows="2"
                                >{{ old('address', setting('address')) }}</textarea>

                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>



                {{-- INVOICE --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-receipt me-2 text-primary"></i>
                            Invoice
                        </h6>
                    </div>

                    <div class="card-body p-4">

                        <div class="mb-3">

                            <label class="form-label">
                                Invoice Note
                            </label>

                            <textarea
                                name="invoice_note"
                                class="form-control @error('invoice_note') is-invalid @enderror"
                                rows="3"
                                placeholder="Return policy or terms printed under the totals"
                            >{{ old('invoice_note', setting('invoice_note')) }}</textarea>

                            @error('invoice_note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>


                        <div>

                            <label class="form-label">
                                Footer Message
                            </label>

                            <input
                                type="text"
                                name="invoice_footer"
                                class="form-control @error('invoice_footer') is-invalid @enderror"
                                placeholder="Example: Thank you for shopping with us!"
                                value="{{ old('invoice_footer', setting('invoice_footer')) }}"
                            >

                            @error('invoice_footer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>

                    </div>

                </div>

            </div>



            <div class="col-xl-4">

                {{-- LOGO --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-image me-2 text-primary"></i>
                            Logo
                        </h6>
                    </div>

                    <div class="card-body p-4 text-center">

                        <div
                            class="mx-auto mb-3 rounded-3 border d-flex align-items-center justify-content-center bg-light overflow-hidden"
                            style="width: 120px; height: 120px;"
                        >

                            @if(setting('logo'))
                                <img
                                    id="logo-preview"
                                    src="{{ asset(setting('logo')) }}"
                                    alt="Shop logo"
                                    style="width: 100%; height: 100%; object-fit: contain;"
                                >
                            @else
                                <img
                                    id="logo-preview"
                                    src=""
                                    alt="Shop logo"
                                    class="d-none"
                                    style="width: 100%; height: 100%; object-fit: contain;"
                                >
                                <i id="logo-placeholder" class="bi bi-box-seam fs-1 text-muted"></i>
                            @endif

                        </div>

                        <input
                            type="file"
                            name="logo"
                            class="form-control @error('logo') is-invalid @enderror"
                            accept="image/png,image/jpeg,image/webp"
                            onchange="previewLogo(this)"
                        >

                        @error('logo')
                            <div class="invalid-feedback text-start">{{ $message }}</div>
                        @enderror

                        <small class="text-muted d-block mt-2">
                            Square PNG works best. JPG, PNG or WEBP, max 2 MB.
                        </small>

                        @if(setting('logo'))
                            <div class="form-check d-inline-block mt-3">
                                <input class="form-check-input" type="checkbox" name="remove_logo" value="1" id="remove_logo">
                                <label class="form-check-label text-danger" for="remove_logo">
                                    Remove current logo
                                </label>
                            </div>
                        @endif

                    </div>

                </div>



                {{-- WHERE IT SHOWS --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-body p-4">

                        <h6 class="fw-bold mb-3">Where these appear</h6>

                        <ul class="list-unstyled small text-muted mb-0">
                            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Sidebar brand and browser tab title</li>
                            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Printed invoice header and footer</li>
                            <li><i class="bi bi-check2 text-success me-2"></i>Invoice note under the totals</li>
                        </ul>

                    </div>

                </div>


                <button type="submit" class="btn btn-primary w-100 py-2">
                    <i class="bi bi-check-lg me-1"></i>
                    Save Settings
                </button>

            </div>

        </div>

    </form>

</div>

@endsection


@push('scripts')

<script>
    "use strict";

    function previewLogo(input) {

        if (! input.files || ! input.files[0]) {
            return;
        }

        const reader = new FileReader();

        reader.onload = function (e) {
            $('#logo-preview').attr('src', e.target.result).removeClass('d-none');
            $('#logo-placeholder').addClass('d-none');
        };

        reader.readAsDataURL(input.files[0]);
    }
</script>

@endpush
