@extends('backend.layouts.admin')

@section('title', 'My Purchases')
@section('page-title', 'My Purchases')

@section('content')

<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="mb-4">

        <h4 class="mb-1 fw-semibold">
            My Purchases
        </h4>

        <p class="text-muted mb-0">
            All your invoices from {{ setting('name') }}. View details or print any invoice.
        </p>

    </div>



    <div class="card border-0 shadow-sm">

        {{-- FILTERS --}}
        <div class="p-4 border-bottom">

            <form action="{{ route('customer.purchases') }}" method="GET">

                <div class="row g-2 align-items-end">

                    <div class="col-lg-4 col-md-12">

                        <label class="form-label small text-muted mb-1">Invoice No.</label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search by invoice number..."
                            value="{{ request('search') }}"
                        >

                    </div>


                    <div class="col-lg-3 col-md-5">

                        <label class="form-label small text-muted mb-1">From</label>

                        <input type="date" name="from" class="form-control" value="{{ request('from') }}">

                    </div>


                    <div class="col-lg-3 col-md-5">

                        <label class="form-label small text-muted mb-1">To</label>

                        <input type="date" name="to" class="form-control" value="{{ request('to') }}">

                    </div>


                    <div class="col-lg-2 col-md-2 d-flex gap-1">

                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-funnel"></i>
                            <span class="d-lg-none d-xl-inline ms-1">Filter</span>
                        </button>

                        @if(request()->filled('search') || request()->filled('from') || request()->filled('to'))
                            <a href="{{ route('customer.purchases') }}" class="btn btn-light border" title="Clear filters">
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
                        <th class="ps-4">#</th>
                        <th>Invoice No.</th>
                        <th>Date</th>
                        <th class="text-center">Items</th>
                        <th class="text-end">Discount</th>
                        <th class="text-end">Total Paid</th>
                        <th>Payment</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($purchases as $sale)
                        <tr>
                            <td class="ps-4">{{ $purchases->firstItem() + $loop->index }}</td>
                            <td class="fw-semibold">{{ $sale->invoice_no }}</td>
                            <td>
                                <i class="bi bi-calendar3 me-1 text-muted"></i>
                                {{ $sale->created_at->format('d M Y, h:i A') }}
                            </td>
                            <td class="text-center">{{ (int) $sale->items_sum_quantity }}</td>
                            <td class="text-end text-success">
                                @if($sale->discount > 0)
                                    ৳{{ number_format($sale->discount, 2) }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-end fw-semibold">৳{{ number_format($sale->grand_total, 2) }}</td>
                            <td>
                                <span class="badge bg-success-subtle text-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    {{ ucfirst($sale->payment_status) }}
                                </span>
                                <div>
                                    <small class="text-muted">{{ ucwords(str_replace('_', ' ', $sale->payment_method)) }}</small>
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('customer.purchases.show', $sale->id) }}" class="btn btn-sm btn-light border" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('customer.purchases.invoice', ['id' => $sale->id, 'print' => 1]) }}" target="_blank" class="btn btn-sm btn-light border" title="Print Invoice">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">

                                <div class="mb-2">
                                    <i class="bi bi-bag fs-1 text-muted"></i>
                                </div>

                                <h6 class="fw-semibold">No Purchases Found</h6>

                                <p class="text-muted mb-0">
                                    @if(request()->filled('search') || request()->filled('from') || request()->filled('to'))
                                        No purchase matched your filters.
                                    @else
                                        You haven't made any purchases yet.
                                    @endif
                                </p>

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
