@extends('backend.layouts.admin')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h2 class="fw-bold">Dashboard</h2>

        <p class="text-muted mb-0">
            Welcome back, {{ auth()->user()->name }}.
        </p>
    </div>

    <div class="row g-4">

        {{-- Products --}}
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-2">Total Products</p>
                    <h3 class="fw-bold mb-0">0</h3>
                </div>
            </div>
        </div>

        {{-- Customers --}}
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-2">Total Customers</p>
                    <h3 class="fw-bold mb-0">0</h3>
                </div>
            </div>
        </div>

        {{-- Sales --}}
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-2">Total Sales</p>
                    <h3 class="fw-bold mb-0">0</h3>
                </div>
            </div>
        </div>

        {{-- Revenue --}}
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-2">Total Revenue</p>
                    <h3 class="fw-bold mb-0">৳ 0</h3>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection