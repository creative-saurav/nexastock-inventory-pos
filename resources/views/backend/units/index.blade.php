@extends('backend.layouts.admin')

@section('title', 'Units')
@section('page-title', 'Units')

@section('content')

<div class="container-fluid px-0">

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h4 class="fw-bold mb-1">
            Units
        </h4>

        <p class="text-muted mb-0">
            Manage product units
        </p>

    </div>

    <button
        type="button"
        class="btn btn-primary"
        onclick="modal(
            'modal-md',
            '{{ route('units.create') }}',
            'Add Unit'
        )"
    >
        <i class="bi bi-plus-lg me-1"></i>
        Add Unit
    </button>

</div>


<div class="card border-0 shadow-sm">

    <div class="card-body p-0">

        <div class="p-4 border-bottom">

            <div class="row align-items-center">

                <div class="col-md-6">

                    <h6 class="fw-bold mb-1">
                        All Units
                    </h6>

                    <small class="text-muted">
                        {{ $units->total() }} units found
                    </small>

                </div>


                <div class="col-md-6">

                    <form
                        action="{{ route('units') }}"
                        method="GET"
                    >

                        <div class="input-group">

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Search unit..."
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
                            Unit Name
                        </th>

                        <th>
                            Short Name
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

                    @forelse($units as $unit)

                        <tr>

                            <td>
                                {{ $units->firstItem() + $loop->index }}
                            </td>

                            <td>
                                {{ $unit->name }}
                            </td>

                            <td>
                                <span class="badge bg-secondary">
                                    {{ $unit->short_name }}
                                </span>
                            </td>

                            <td>

                                @if($unit->status)

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
                                {{ $unit->created_at->format('d M, Y') }}
                            </td>

                            <td class="text-end">

                                <div class="btn-group">

                                    <button
                                        class="btn btn-sm btn-light"
                                        onclick="edit_modal(
                                            'modal-md',
                                            '{{ route('units.edit', $unit->id) }}',
                                            'Edit Unit'
                                        )"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </button>


                                    <button
                                        class="btn btn-sm btn-light text-danger"
                                        onclick="delete_modal(
                                            '{{ route('units.delete', $unit->id) }}'
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
                                colspan="6"
                                class="text-center py-5"
                            >
                                No units found
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($units->hasPages())

            <div class="p-4 border-top">

                {{ $units->links() }}

            </div>

        @endif

    </div>

</div>


</div>

@endsection
