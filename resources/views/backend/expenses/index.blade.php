@extends('backend.layouts.admin')

@section('page-title', 'Expenses')
@section('title', 'Expenses')


@section('content')

<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-semibold">
                Expenses
            </h4>

            <p class="text-muted mb-0">
                Track shop expenses like rent, salary and bills.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('expense_categories') }}"
                class="btn btn-light border"
            >
                <i class="bi bi-tags me-1"></i>
                Categories
            </a>

            <button
                type="button"
                class="btn btn-primary"
                onclick="modal(
                    'modal-lg',
                    '{{ route('expenses.create') }}',
                    'Add Expense'
                )"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Add Expense
            </button>

        </div>

    </div>



    {{-- SUMMARY CARDS --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body d-flex align-items-center gap-3">

                    <span class="badge bg-primary-subtle text-primary rounded-3 p-3">
                        <i class="bi bi-calendar-day fs-5"></i>
                    </span>

                    <div>
                        <small class="text-muted d-block">Today</small>
                        <h5 class="fw-bold mb-0">৳{{ number_format($todayTotal, 2) }}</h5>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body d-flex align-items-center gap-3">

                    <span class="badge bg-warning-subtle text-warning rounded-3 p-3">
                        <i class="bi bi-calendar-month fs-5"></i>
                    </span>

                    <div>
                        <small class="text-muted d-block">This Month</small>
                        <h5 class="fw-bold mb-0">৳{{ number_format($thisMonthTotal, 2) }}</h5>
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body d-flex align-items-center gap-3">

                    <span class="badge bg-danger-subtle text-danger rounded-3 p-3">
                        <i class="bi bi-wallet2 fs-5"></i>
                    </span>

                    <div>
                        <small class="text-muted d-block">
                            {{ $isFiltered ? 'Filtered Total' : 'All Time' }}
                        </small>
                        <h5 class="fw-bold mb-0">৳{{ number_format($totalAmount, 2) }}</h5>
                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- EXPENSE TABLE --}}
    <div class="card border-0 shadow-sm">


        <div class="p-4 border-bottom">

            <div class="mb-3">

                <h6 class="fw-bold mb-1">
                    Expense List
                </h6>

                <small class="text-muted">
                    Total Expenses:
                    <strong>{{ $expenses->total() }}</strong>
                </small>

            </div>


            <form
                action="{{ route('expenses') }}"
                method="GET"
            >

                <div class="row g-2">

                    <div class="col-lg-4">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search by expense no., reference or note..."
                            value="{{ request('search') }}"
                        >

                    </div>


                    <div class="col-lg-3 col-md-4">

                        <select
                            name="category"
                            class="form-select"
                        >

                            <option value="">
                                All Categories
                            </option>

                            @foreach($categories as $category)

                                <option
                                    value="{{ $category->id }}"
                                    @selected(request('category') == $category->id)
                                >
                                    {{ $category->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-lg-2 col-md-4">

                        <input
                            type="date"
                            name="from"
                            class="form-control"
                            title="From date"
                            value="{{ request('from') }}"
                        >

                    </div>


                    <div class="col-lg-2 col-md-4">

                        <input
                            type="date"
                            name="to"
                            class="form-control"
                            title="To date"
                            value="{{ request('to') }}"
                        >

                    </div>


                    <div class="col-lg-1 d-flex gap-1">

                        <button
                            class="btn btn-primary flex-fill"
                            type="submit"
                            title="Filter"
                        >
                            <i class="bi bi-funnel"></i>
                        </button>

                        @if($isFiltered)

                            <a
                                href="{{ route('expenses') }}"
                                class="btn btn-light border"
                                title="Clear filters"
                            >
                                <i class="bi bi-x-lg"></i>
                            </a>

                        @endif

                    </div>

                </div>

            </form>

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
                                Expense No.
                            </th>

                            <th>
                                Category
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Amount
                            </th>

                            <th>
                                Payment
                            </th>

                            <th>
                                Note
                            </th>

                            <th>
                                Added By
                            </th>

                            <th class="text-end pe-4">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($expenses as $expense)

                            <tr>

                                {{-- SERIAL --}}
                                <td class="ps-4">
                                    {{ $expenses->firstItem() + $loop->index }}
                                </td>


                                {{-- EXPENSE NUMBER --}}
                                <td>

                                    <span class="fw-semibold">
                                        {{ $expense->expense_no }}
                                    </span>

                                    @if($expense->reference)
                                        <div>
                                            <small class="text-muted">
                                                Ref: {{ $expense->reference }}
                                            </small>
                                        </div>
                                    @endif

                                </td>


                                {{-- CATEGORY --}}
                                <td>

                                    <span class="badge bg-primary-subtle text-primary">
                                        {{ $expense->category->name ?? 'N/A' }}
                                    </span>

                                </td>


                                {{-- DATE --}}
                                <td>

                                    <span>
                                        <i class="bi bi-calendar3 me-1 text-muted"></i>
                                        {{ $expense->expense_date->format('d M Y') }}
                                    </span>

                                </td>


                                {{-- AMOUNT --}}
                                <td>

                                    <span class="fw-semibold text-danger">
                                        ৳{{ number_format($expense->amount, 2) }}
                                    </span>

                                </td>


                                {{-- PAYMENT METHOD --}}
                                <td>

                                    <small>
                                        {{ \App\Http\Controllers\ExpenseController::PAYMENT_METHODS[$expense->payment_method] ?? ucfirst($expense->payment_method) }}
                                    </small>

                                </td>


                                {{-- NOTE --}}
                                <td>

                                    <small class="text-muted" title="{{ $expense->note }}">
                                        {{ $expense->note ? \Illuminate\Support\Str::limit($expense->note, 40) : '—' }}
                                    </small>

                                </td>


                                {{-- ADDED BY --}}
                                <td>

                                    <span class="fw-medium">
                                        {{ $expense->user->name ?? 'N/A' }}
                                    </span>

                                </td>


                                {{-- ACTION --}}
                                <td class="text-end pe-4">

                                    <div class="d-flex justify-content-end gap-1">

                                        {{-- EDIT --}}
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-light border"
                                            onclick="edit_modal(
                                                'modal-lg',
                                                '{{ route('expenses.edit', $expense->id) }}',
                                                'Edit Expense'
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
                                                '{{ route('expenses.delete', $expense->id) }}'
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
                                    colspan="9"
                                    class="text-center py-5"
                                >

                                    <div class="mb-2">
                                        <i class="bi bi-wallet2 fs-1 text-muted"></i>
                                    </div>

                                    <h6 class="fw-semibold">
                                        No Expenses Found
                                    </h6>

                                    <p class="text-muted mb-0">

                                        @if($isFiltered)

                                            No expense matched your filters.

                                        @else

                                            No expenses have been added yet.

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
        @if($expenses->hasPages())

            <div class="card-footer bg-white border-0 py-3">

                {{ $expenses->links() }}

            </div>

        @endif

    </div>

</div>

@endsection
