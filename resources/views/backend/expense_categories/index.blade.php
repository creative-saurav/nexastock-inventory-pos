@extends('backend.layouts.admin')

@section('title', 'Expense Categories')
@section('page-title', 'Expense Categories')

@section('content')

<div class="container-fluid px-0">

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h4 class="fw-bold mb-1">
            Expense Categories
        </h4>

        <p class="text-muted mb-0">
            Group your expenses, e.g. Rent, Salary, Utility Bills
        </p>

    </div>

    <div class="d-flex gap-2">

        <a
            href="{{ route('expenses') }}"
            class="btn btn-light border"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Expenses
        </a>

        <button
            type="button"
            class="btn btn-primary"
            onclick="modal(
                'modal-md',
                '{{ route('expense_categories.create') }}',
                'Add Expense Category'
            )"
        >
            <i class="bi bi-plus-lg me-1"></i>
            Add Category
        </button>

    </div>

</div>


<div class="card border-0 shadow-sm">

    <div class="card-body p-0">

        <div class="p-4 border-bottom">

            <div class="row align-items-center">

                <div class="col-md-6">

                    <h6 class="fw-bold mb-1">
                        All Expense Categories
                    </h6>

                    <small class="text-muted">
                        {{ $categories->total() }} categories found
                    </small>

                </div>


                <div class="col-md-6">

                    <form
                        action="{{ route('expense_categories') }}"
                        method="GET"
                    >

                        <div class="input-group">

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Search category..."
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


        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>
                            Category Name
                        </th>

                        <th>
                            Expenses
                        </th>

                        <th>
                            Total Spent
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Created
                        </th>

                        <th class="text-end">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($categories as $category)

                        <tr>

                            <td>
                                {{ $categories->firstItem() + $loop->index }}
                            </td>

                            <td>

                                <div class="fw-semibold">
                                    {{ $category->name }}
                                </div>

                                @if($category->description)
                                    <small class="text-muted">
                                        {{ \Illuminate\Support\Str::limit($category->description, 60) }}
                                    </small>
                                @endif

                            </td>

                            <td>
                                <span class="badge bg-secondary">
                                    {{ $category->expenses_count }}
                                </span>
                            </td>

                            <td class="fw-semibold">
                                ৳{{ number_format($category->expenses_sum_amount ?? 0, 2) }}
                            </td>

                            <td>

                                @if($category->status)

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Inactive
                                    </span>

                                @endif

                            </td>

                            <td>
                                {{ $category->created_at->format('d M, Y') }}
                            </td>

                            <td class="text-end">

                                <div class="btn-group">

                                    <button
                                        class="btn btn-sm btn-light"
                                        onclick="edit_modal(
                                            'modal-md',
                                            '{{ route('expense_categories.edit', $category->id) }}',
                                            'Edit Expense Category'
                                        )"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </button>


                                    <button
                                        class="btn btn-sm btn-light text-danger"
                                        onclick="delete_modal(
                                            '{{ route('expense_categories.delete', $category->id) }}'
                                        )"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5"
                            >
                                No expense categories found
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($categories->hasPages())

            <div class="p-4 border-top">

                {{ $categories->links() }}

            </div>

        @endif

    </div>

</div>

</div>

@endsection
