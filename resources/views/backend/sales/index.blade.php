@extends('backend.layouts.admin')

@section('page-title', 'Sales')
@section('title', 'Sales')


@section('content')

<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-semibold">
                Sales
            </h4>

            <p class="text-muted mb-0">
                {{ auth()->user()->hasRole('cashier') ? 'Sales you have made from the POS.' : 'View all sales made from the POS.' }}
            </p>
        </div>

        <a
            href="{{ route('pos') }}"
            class="btn btn-primary"
        >
            <i class="bi bi-plus-lg me-1"></i>
            New Sale
        </a>

    </div>



    {{-- SALE TABLE --}}
    <div class="card border-0 shadow-sm">


        <div class="p-4 border-bottom">

            <div class="row align-items-center">

                <div class="col-md-6">

                    <h6 class="fw-bold mb-1">
                        Sale List
                    </h6>

                    <small class="text-muted">
                        Total Sales:
                        <strong>{{ $sales->total() }}</strong>
                    </small>

                </div>


                <div class="col-md-6">

                    <form
                        action="{{ route('sales') }}"
                        method="GET"
                    >

                        <div class="input-group">

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Search by invoice number or customer name..."
                                value="{{ request('search') }}"
                            >

                            <button
                                class="btn btn-primary"
                                type="submit"
                            >
                                Search
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>



        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="ps-4">
                                #
                            </th>

                            <th>
                                Invoice No.
                            </th>

                            <th>
                                Customer
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Items
                            </th>

                            <th>
                                Discount
                            </th>

                            <th>
                                Grand Total
                            </th>

                            <th>
                                Paid
                            </th>

                            <th>
                                Payment
                            </th>

                            <th class="pe-4">
                                Sold By
                            </th>
                            <th class="pe-4">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($sales as $sale)

                            <tr>

                                {{-- SERIAL --}}
                                <td class="ps-4">
                                    {{ $sales->firstItem() + $loop->index }}
                                </td>


                                {{-- INVOICE NUMBER --}}
                                <td>

                                    <span class="fw-semibold">
                                        {{ $sale->invoice_no }}
                                    </span>

                                </td>


                                {{-- CUSTOMER --}}
                                <td>

                                    <div class="d-flex align-items-center">

                                        <span class="badge bg-primary-subtle text-primary rounded-circle p-2 me-2">
                                            <i class="bi bi-person"></i>
                                        </span>

                                        <span class="fw-medium">
                                            {{ $sale->customer->name ?? 'Walk-in Customer' }}
                                        </span>

                                    </div>

                                </td>


                                {{-- DATE --}}
                                <td>

                                    <span>
                                        <i class="bi bi-calendar3 me-1 text-muted"></i>
                                        {{ $sale->created_at->format('d M Y, h:i A') }}
                                    </span>

                                </td>


                                {{-- ITEMS --}}
                                <td>

                                    <span class="badge bg-light text-dark border">
                                        {{ $sale->items_count }}
                                    </span>

                                </td>


                                {{-- DISCOUNT --}}
                                <td>

                                    <span class="text-muted">
                                        ৳{{ number_format($sale->discount, 2) }}
                                    </span>

                                </td>


                                {{-- GRAND TOTAL --}}
                                <td>

                                    <span class="fw-semibold">
                                        ৳{{ number_format($sale->grand_total, 2) }}
                                    </span>

                                </td>


                                {{-- PAID --}}
                                <td>

                                    <span class="text-success fw-medium">
                                        ৳{{ number_format($sale->paid_amount, 2) }}
                                    </span>

                                </td>


                                {{-- PAYMENT --}}
                                <td>

                                    @if($sale->payment_status === 'paid')

                                        <span class="badge bg-success-subtle text-success">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Paid
                                        </span>

                                    @elseif($sale->payment_status === 'partial')

                                        <span class="badge bg-warning-subtle text-warning">
                                            <i class="bi bi-clock me-1"></i>
                                            Partial
                                        </span>

                                    @else

                                        <span class="badge bg-danger-subtle text-danger">
                                            <i class="bi bi-exclamation-circle me-1"></i>
                                            Due
                                        </span>

                                    @endif

                                    <div>
                                        <small class="text-muted">
                                            {{ ucwords(str_replace('_', ' ', $sale->payment_method)) }}
                                        </small>
                                    </div>

                                </td>


                                {{-- SOLD BY --}}
                                <td class="pe-4">

                                    <span class="fw-medium">
                                        {{ $sale->user->name ?? 'N/A' }}
                                    </span>

                                </td>

                                {{-- ACTION --}}
                            <td class="pe-4">
                                <a href="{{ route('sales.show', $sale->id) }}"
                                class="btn btn-sm btn-outline-primary"
                                title="View Sale Details">
                                    <i class="bi bi-eye me-1"></i>
                                    View
                                </a>
                            </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="10"
                                    class="text-center py-5"
                                >

                                    <div class="mb-2">
                                        <i class="bi bi-receipt fs-1 text-muted"></i>
                                    </div>

                                    <h6 class="fw-semibold">
                                        No Sales Found
                                    </h6>

                                    <p class="text-muted mb-0">

                                        @if(request('search'))

                                            No sale matched your search.

                                        @else

                                            No sales have been made yet.

                                        @endif

                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- PAGINATION --}}
        @if($sales->hasPages())

            <div class="card-footer bg-white border-0 py-3">

                {{ $sales->links() }}

            </div>

        @endif

    </div>

</div>

@endsection
