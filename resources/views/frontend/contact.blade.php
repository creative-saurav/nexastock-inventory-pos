@extends('frontend.layouts.app')

@section('title', 'Contact Us')

@section('content')

@php
    $phoneHref = setting('phone') ? 'tel:' . preg_replace('/[^0-9+]/', '', setting('phone')) : null;
@endphp

<section class="page-head">

    <div class="container">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Contact</li>
            </ol>
        </nav>

        <h1>Get in touch</h1>

        <p class="text-muted mb-0">Visit the store, call us, or send an email. We usually reply the same day.</p>

    </div>

</section>



<section class="section pt-4">

    <div class="container">

        <div class="row g-4 mb-4">

            @if(setting('address'))
                <div class="col-md-4">
                    <div class="panel contact-card">
                        <div class="icon"><i class="bi bi-geo-alt"></i></div>
                        <h5 class="fw-bold">Visit our store</h5>
                        <p class="text-muted mb-3">{{ setting('address') }}</p>
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode(setting('address')) }}" target="_blank" rel="noopener" class="link-arrow">
                            Get directions <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endif

            @if($phoneHref)
                <div class="col-md-4">
                    <div class="panel contact-card">
                        <div class="icon"><i class="bi bi-telephone"></i></div>
                        <h5 class="fw-bold">Call us</h5>
                        <p class="text-muted mb-3">{{ setting('phone') }}</p>
                        <a href="{{ $phoneHref }}" class="link-arrow">
                            Call now <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endif

            @if(setting('email'))
                <div class="col-md-4">
                    <div class="panel contact-card">
                        <div class="icon"><i class="bi bi-envelope"></i></div>
                        <h5 class="fw-bold">Email us</h5>
                        <p class="text-muted mb-3">{{ setting('email') }}</p>
                        <a href="mailto:{{ setting('email') }}" class="link-arrow">
                            Send an email <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endif

        </div>


        <div class="row g-4">

            @if(setting('address'))
                <div class="col-lg-8">
                    <iframe
                        class="map-frame shadow-sm"
                        src="https://maps.google.com/maps?q={{ urlencode(setting('address')) }}&z=15&output=embed"
                        title="Map showing {{ setting('name') }}"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen
                    ></iframe>
                </div>
            @endif

            <div class="{{ setting('address') ? 'col-lg-4' : 'col-12' }}">

                <div class="promo promo-light h-100 d-flex flex-column justify-content-center">

                    <span class="eyebrow">Already a customer?</span>

                    <h3 class="mb-2">Your purchases, online.</h3>

                    <p class="text-muted mb-4">Sign in to see every purchase and print any invoice, any time.</p>

                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary align-self-start">
                            <i class="bi bi-person-circle me-1"></i>
                            My account
                        </a>
                    @else
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('login') }}" class="btn btn-primary">Sign in</a>
                            <a href="{{ route('register') }}" class="btn btn-soft">Create account</a>
                        </div>
                    @endauth

                    <i class="bi bi-receipt promo-icon" aria-hidden="true"></i>

                </div>

            </div>

        </div>

    </div>

</section>

<div class="pb-5"></div>

@endsection
