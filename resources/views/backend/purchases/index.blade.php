@extends('backend.layouts.admin')

@section('page-title', 'Purchases')
@section('title', 'Purchases')


@section('content')

<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-semibold">
                Purchases
            </h4>

            <p class="text-muted mb-0">
                Manage  purchases and inventory stock.
            </p>
        </div>

        <button
            type="button"
            class="btn btn-primary"
            onclick="modal(
                'modal-xl',
                '{{ route('purchases.create') }}',
                'Create Purchase'
            )"
        >
            <i class="bi bi-plus-lg me-1"></i>
            New Purchase
        </button>

    </div>



    {{-- PURCHASE TABLE --}}
    <div class="card border-0 shadow-sm">


        <div class="p-4 border-bottom">

            <div class="row align-items-center">

                <div class="col-md-6">

                    <h6 class="fw-bold mb-1">
                        Purchase List
                    </h6>

                   <small class="text-muted">
                        Total Purchases:
                        <strong>{{ $purchases->total() }}</strong>
                    </small>

                </div>


                <div class="col-md-6">

                    <form
                        action="{{ route('purchases') }}"
                        method="GET"
                    >

                        <div class="input-group">

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Search by purchase number or supplier name..."
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
                                Purchase No.
                            </th>

                            <th>
                                Supplier
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Grand Total
                            </th>

                            <th>
                                Paid
                            </th>

                            <th>
                                Due
                            </th>

                            <th>
                                Payment
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end pe-4">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($purchases as $purchase)

                            <tr>

                                {{-- SERIAL --}}
                                <td class="ps-4">
                                    {{ $purchases->firstItem() + $loop->index }}
                                </td>


                                {{-- PURCHASE NUMBER --}}
                                <td>

                                    <span class="fw-semibold">
                                        {{ $purchase->purchase_no }}
                                    </span>

                                </td>


                                {{-- SUPPLIER --}}
                                <td>

                                    <div class="d-flex align-items-center">

                                        <span class="badge bg-primary-subtle text-primary rounded-circle p-2 me-2">
                                            <i class="bi bi-person"></i>
                                        </span>

                                        <span class="fw-medium">
                                            {{ $purchase->supplier->name ?? 'N/A' }}
                                        </span>

                                    </div>

                                </td>


                                {{-- DATE --}}
                                <td>

                                    <span>
                                        <i class="bi bi-calendar3 me-1 text-muted"></i>
                                        {{ $purchase->purchase_date->format('d M Y') }}
                                    </span>

                                </td>


                                {{-- GRAND TOTAL --}}
                                <td>

                                    <span class="fw-semibold">
                                        ৳{{ number_format($purchase->grand_total, 2) }}
                                    </span>

                                </td>


                                {{-- PAID --}}
                                <td>

                                    <span class="text-success fw-medium">
                                        ৳{{ number_format($purchase->paid_amount, 2) }}
                                    </span>

                                </td>


                                {{-- DUE --}}
                                <td>

                                    @if($purchase->due_amount > 0)

                                        <span class="text-danger fw-medium">
                                            ৳{{ number_format($purchase->due_amount, 2) }}
                                        </span>

                                    @else

                                        <span class="text-muted">
                                            ৳0.00
                                        </span>

                                    @endif

                                </td>


                                {{-- PAYMENT STATUS --}}
                                <td>

                                    @if($purchase->payment_status === 'paid')

                                        <span class="badge bg-success-subtle text-success">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Paid
                                        </span>

                                    @elseif($purchase->payment_status === 'partial')

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

                                </td>


                                {{-- PURCHASE STATUS --}}
                                <td>

                                    @if($purchase->status === 'received')

                                        <span class="badge bg-success-subtle text-success">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Received
                                        </span>

                                    @elseif($purchase->status === 'pending')

                                        <span class="badge bg-warning-subtle text-warning">
                                            <i class="bi bi-clock me-1"></i>
                                            Pending
                                        </span>

                                    @else

                                        <span class="badge bg-danger-subtle text-danger">
                                            <i class="bi bi-x-circle me-1"></i>
                                            Cancelled
                                        </span>

                                    @endif

                                </td>


                                {{-- ACTION --}}
                                <td class="text-end pe-4">

                                    <div class="d-flex justify-content-end gap-1">

                                        {{-- VIEW --}}
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-light border"
                                            onclick="modal(
                                                'modal-xl',
                                                '{{ route('purchases.show', $purchase->id) }}',
                                                'Purchase Details'
                                            )"
                                            title="View"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </button>


                                        {{-- EDIT --}}
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-light border"
                                            onclick="edit_modal(
                                                'modal-xl',
                                                '{{ route('purchases.edit', $purchase->id) }}',
                                                'Edit Purchase'
                                            )"
                                            title="Edit"
                                        >
                                            <i class="bi bi-pencil-square"></i>
                                        </button>


                                        {{-- DELETE --}}
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-light border text-danger"
                                            onclick="delete_modal(
                                                '{{ route('purchases.delete', $purchase->id) }}'
                                            )"
                                            title="Delete"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="10"
                                    class="text-center py-5"
                                >

                                    <div class="mb-2">
                                        <i class="bi bi-cart-x fs-1 text-muted"></i>
                                    </div>

                                    <h6 class="fw-semibold">
                                        No Purchases Found
                                    </h6>

                                    <p class="text-muted mb-0">

                                        @if(request('search'))

                                            No purchase matched your search.

                                        @else

                                            No purchases have been created yet.

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
        @if($purchases->hasPages())

            <div class="card-footer bg-white border-0 py-3">

                {{ $purchases->links() }}

            </div>

        @endif

    </div>

</div>

@endsection