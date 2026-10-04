@extends('backend.layouts.admin')

@section('content')

<div class="container-fluid">
    <h2 class="fw-bold">Cashier Dashboard</h2>

    <p class="text-muted">
        Welcome, {{ auth()->user()->name }}.
    </p>
</div>

@endsection