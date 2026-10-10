@extends('backend.layouts.admin')

@section('title', 'Incoming Stock')
@section('page-title', 'Incoming Stock')

@section('content')

@php
    $qty = fn ($value) => rtrim(rtrim(number_format($value, 2), '0'), '.');
@endphp

<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="mb-4">

        <h4 class="mb-1 fw-semibold">
            Incoming Stock
        </h4>

        <p class="text-muted mb-0">
            Products received from suppliers.
        </p>

    </div>



    <div class="card border-0 shadow-sm">

        {{-- FILTERS --}}
        <div class="p-4 border-bottom">

            <form action="{{ route('staff.purchases') }}" method="GET">

                <div class="row g-2 align-items-end">

                    <div class="col-lg-6 col-md-12">

                        <label class="form-label small text-muted mb-1">Search</label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Purchase number or supplier name..."
                            value="{{ request('search') }}"
                        >

                    </div>


                    <div class="col-lg-3 col-md-6">

                        <label class="form-label small text-muted mb-1">Status</label>

                        <select name="status" class="form-select">
                            <option value="">All</option>
                            <option value="received" @selected(request('status') === 'received')>Received</option>
                            <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                            <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
                        </select>

                    </div>


                    <div class="col-lg-3 col-md-6 d-flex gap-1">

                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-funnel me-1"></i>
                            Filter
                        </button>

                        @if(request()->filled('search') || request()->filled('status'))
                            <a href="{{ route('staff.purchases') }}" class="btn btn-light border" title="Clear filters">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif

                    </div>

                </div>

            </form>

        </div>



        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Purchase No.</th>
                        <th>Date</th>
                        <th>Supplier</th>
                        <th class="text-center">Products</th>
                        <th class="text-center">Total Qty</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($purchases as $purchase)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $purchase->purchase_no }}</td>
                            <td>
                                <i class="bi bi-calendar3 me-1 text-muted"></i>
                                {{ $purchase->purchase_date->format('d M Y') }}
                            </td>
                            <td>{{ $purchase->supplier?->name ?? 'N/A' }}</td>
                            <td class="text-center">{{ $purchase->items_count }}</td>
                            <td class="text-center fw-semibold">{{ $qty($purchase->items_sum_quantity ?? 0) }}</td>
                            <td>
                                @include('backend.staff._purchase_status', ['status' => $purchase->status])
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('staff.purchases.show', $purchase->id) }}" class="btn btn-sm btn-light border" title="View items">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">

                                <div class="mb-2">
                                    <i class="bi bi-truck fs-1 text-muted"></i>
                                </div>

                                <h6 class="fw-semibold">No Purchases Found</h6>

                                <p class="text-muted mb-0">No purchase matched your filters.</p>

                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>


        @if($purchases->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $purchases->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
