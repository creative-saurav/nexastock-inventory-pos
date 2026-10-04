@extends('backend.layouts.admin')

@section('title', 'Brands')
@section('page-title', 'Brands')

@section('content')

<div class="container-fluid px-0">


{{-- Page Header --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

    <div>
        <h4 class="fw-bold mb-1">
            Brands
        </h4>

        <p class="text-muted mb-0">
            Manage your product brands
        </p>
    </div>

    <div class="mt-3 mt-md-0">

        {{-- Add Brand --}}
        <button
            type="button"
            class="btn btn-primary px-4"
            onclick="modal(
                'modal-md',
                '{{ route('brands.create') }}',
                'Add Brand'
            )"
        >
            <i class="bi bi-plus-lg me-1"></i>
            Add Brand
        </button>

    </div>

</div>


{{-- Brand Card --}}
<div class="card border-0 shadow-sm">

    <div class="card-body p-0">

        {{-- Table Header --}}
        <div class="p-4 border-bottom">

            <div class="row align-items-center">

                <div class="col-md-6">

                    <h6 class="fw-bold mb-1">
                        All Brands
                    </h6>

                    <small class="text-muted">

                        @if(request('search'))

                            {{ $brands->total() }}
                            result(s) found for
                            <strong>"{{ request('search') }}"</strong>

                        @else

                            {{ $brands->total() }} brands found

                        @endif

                    </small>

                </div>


                <div class="col-md-6 mt-3 mt-md-0">

                    <form
                        action="{{ route('brands') }}"
                        method="GET"
                    >

                        <div class="input-group">

                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Search brand..."
                                value="{{ request('search') }}"
                            >

                            @if(request('search'))

                                <a
                                    href="{{ route('brands') }}"
                                    class="btn btn-light border"
                                    title="Clear Search"
                                >
                                    <i class="bi bi-x-lg"></i>
                                </a>

                            @endif

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Search
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        {{-- Table --}}
        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead>

                    <tr>

                        <th class="ps-4">
                            #
                        </th>

                        <th>
                            Brand
                        </th>

                        <th>
                            Slug
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Created
                        </th>

                        <th class="text-end pe-4">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($brands as $brand)

                        <tr>

                            {{-- Serial --}}
                            <td class="ps-4">
                                {{ $brands->firstItem() + $loop->index }}
                            </td>


                            {{-- Brand --}}
                            <td>

                                <div class="d-flex align-items-center gap-3">

                                    <div
                                        class="brand-logo"
                                        style="
                                            width: 42px;
                                            height: 42px;
                                            border-radius: 10px;
                                            overflow: hidden;
                                            background: #f8f9fa;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                        "
                                    >

                                        @if($brand->logo)

                                            <img
                                                src="{{ asset($brand->logo) }}"
                                                alt="{{ $brand->name }}"
                                                style="
                                                    width: 100%;
                                                    height: 100%;
                                                    object-fit: contain;
                                                "
                                            >

                                        @else

                                            <i class="bi bi-award text-muted"></i>

                                        @endif

                                    </div>


                                    <div>

                                        <div class="fw-semibold">
                                            {{ $brand->name }}
                                        </div>

                                        @if($brand->description)

                                            <small class="text-muted">
                                                {{ Str::limit($brand->description, 45) }}
                                            </small>

                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- Slug --}}
                            <td>

                                <code>
                                    {{ $brand->slug }}
                                </code>

                            </td>


                            {{-- Status --}}
                            <td>

                                @if($brand->status)

                                    <span class="badge bg-success-subtle text-success">
                                        Active
                                    </span>

                                @else

                                    <span class="badge bg-danger-subtle text-danger">
                                        Inactive
                                    </span>

                                @endif

                            </td>


                            {{-- Created --}}
                            <td>
                                {{ $brand->created_at->format('d M, Y') }}
                            </td>


                            {{-- Actions --}}
                            <td class="text-end pe-4">

                                <div class="btn-group">

                                    {{-- Edit --}}
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-light"
                                        title="Edit"
                                        onclick="edit_modal(
                                            'modal-md',
                                            '{{ route('brands.edit', $brand->id) }}',
                                            'Edit Brand'
                                        )"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </button>


                                    {{-- Delete --}}
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-light text-danger"
                                        title="Delete"
                                        onclick="delete_modal(
                                            '{{ route('brands.delete', $brand->id) }}'
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

                                <div class="empty-state">

                                    <div class="empty-state-icon">
                                        <i class="bi bi-award"></i>
                                    </div>

                                    <h6 class="fw-bold mt-3">
                                        No Brands Found
                                    </h6>

                                    <p class="text-muted mb-0">
                                        Create your first product brand.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if($brands->hasPages())

            <div class="p-4 border-top">

                {{ $brands->links() }}

            </div>

        @endif

    </div>

</div>


</div>

@endsection
