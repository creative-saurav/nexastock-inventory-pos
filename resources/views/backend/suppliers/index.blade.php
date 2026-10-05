@extends('backend.layouts.admin')

@section('page-title', 'Suppliers')
@section('title', 'Suppliers')

@section('content')

<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-semibold">
                <i class="bi bi-truck me-2"></i>
                Suppliers
            </h4>

            <p class="text-muted mb-0">
                Manage your suppliers and supplier information.
            </p>
        </div>

        <button
            type="button"
            class="btn btn-primary"
            onclick="modal('modal-md', '{{ route('suppliers.create') }}', 'Add Supplier')"
        >
            <i class="bi bi-plus-lg me-1"></i>
            Add Supplier
        </button>

    </div>




    {{-- SUPPLIER TABLE --}}
    <div class="card border-0 shadow-sm">

         <div class="p-4 border-bottom">

            <div class="row align-items-center">

                <div class="col-md-6">

                    <h6 class="fw-bold mb-1">
                        All Suppliers
                    </h6>

                    <small class="text-muted">
                        {{ $suppliers->total() }} units found
                    </small>

                </div>


                <div class="col-md-6">

                    <form
                        action="{{ route('suppliers') }}"
                        method="GET"
                    >

                        <div class="input-group">

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Search suppliers..."
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
                                Supplier
                            </th>

                            <th>
                                Phone
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                Address
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

                        @forelse($suppliers as $supplier)

                            <tr>

                                <td class="ps-4">
                                    {{ $suppliers->firstItem() + $loop->index }}
                                </td>


                                {{-- NAME --}}
                                <td>

                                    <div class="d-flex align-items-center">

                                        <div class="me-2">

                                            <span class="badge bg-primary-subtle text-primary rounded-circle p-2">
                                                <i class="bi bi-person"></i>
                                            </span>

                                        </div>

                                        <div>

                                            <div class="fw-semibold">
                                                {{ $supplier->name }}
                                            </div>


                                        </div>

                                    </div>

                                </td>


                                {{-- PHONE --}}
                                <td>

                                    @if($supplier->phone)

                                        <span>
                                            <i class="bi bi-telephone me-1 text-muted"></i>
                                            {{ $supplier->phone }}
                                        </span>

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- EMAIL --}}
                                <td>

                                    @if($supplier->email)

                                        <span>
                                            <i class="bi bi-envelope me-1 text-muted"></i>
                                            {{ $supplier->email }}
                                        </span>

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- ADDRESS --}}
                                <td>

                                    @if($supplier->address)

                                        <span>
                                            {{ Str::limit($supplier->address, 35) }}
                                        </span>

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- STATUS --}}
                                <td>

                                    @if($supplier->status)

                                        <span class="badge bg-success-subtle text-success">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Active
                                        </span>

                                    @else

                                        <span class="badge bg-danger-subtle text-danger">
                                            <i class="bi bi-x-circle me-1"></i>
                                            Inactive
                                        </span>

                                    @endif

                                </td>


                                {{-- ACTION --}}
                                <td class="text-end pe-4">

                                    <div class="d-flex justify-content-end gap-1">

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-light border"
                                            onclick="edit_modal(
                                                'modal-md',
                                                '{{ route('suppliers.edit', $supplier->id) }}',
                                                'Edit Supplier'
                                            )"
                                            title="Edit"
                                        >
                                            <i class="bi bi-pencil-square"></i>
                                        </button>


                                        <button
                                            type="button"
                                            class="btn btn-sm btn-light border text-danger"
                                            onclick="delete_modal(
                                                '{{ route('suppliers.delete', $supplier->id) }}'
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
                                    colspan="7"
                                    class="text-center py-5"
                                >

                                    <div class="mb-2">
                                        <i class="bi bi-truck fs-1 text-muted"></i>
                                    </div>

                                    <h6 class="fw-semibold">
                                        No Suppliers Found
                                    </h6>

                                    <p class="text-muted mb-0">
                                        @if(request('search'))
                                            No supplier matched your search.
                                        @else
                                            No suppliers have been added yet.
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
        @if($suppliers->hasPages())

            <div class="card-footer bg-white border-0 py-3">

                {{ $suppliers->links() }}

            </div>

        @endif

    </div>

</div>

@endsection