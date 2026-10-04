@extends('backend.layouts.admin')

@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')

<div class="container-fluid px-0">

    {{-- Page Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Categories
            </h4>

            <p class="text-muted mb-0">
                Manage your product categories
            </p>
        </div>

        <div class="mt-3 mt-md-0">

            {{-- Add Category --}}
            <button
                type="button"
                class="btn btn-primary px-4"
                onclick="modal(
                    'modal-md',
                    '{{ route('categories.create') }}',
                    'Add Category'
                )"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Add Category
            </button>

        </div>

    </div>


    {{-- Category Card --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            {{-- Table Header --}}
            <div class="p-4 border-bottom">

                <div class="row align-items-center">

                    <div class="col-md-6">

                        <h6 class="fw-bold mb-1">
                            All Categories
                        </h6>

                        <small class="text-muted">
                            {{ $categories->total() }} categories found
                        </small>

                    </div>

                    <div class="col-md-6 mt-3 mt-md-0">

                        <form
                            action="{{ route('categories') }}"
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
                                    placeholder="Search category..."
                                    value="{{ request('search') }}"
                                >

                                @if(request('search'))

                                    <a
                                        href="{{ route('categories') }}"
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
                                Category
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

                        @forelse($categories as $category)

                            <tr>

                                <td class="ps-4">
                                    {{ $categories->firstItem() + $loop->index }}
                                </td>

                                <td>

                                    <div class="d-flex align-items-center gap-3">

                                        {{-- <div class="category-icon">
                                            <i class="bi bi-folder2"></i>
                                        </div> --}}

                                        <div>

                                            <div class="fw-semibold">
                                                {{ $category->name }}
                                            </div>

                                            @if($category->description)

                                                <small class="text-muted">
                                                    {{ Str::limit($category->description, 45) }}
                                                </small>

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                <td>
                                    <code>
                                        {{ $category->slug }}
                                    </code>
                                </td>


                                <td>

                                    @if($category->status)

                                        <span class="badge bg-success-subtle text-success">
                                            Active
                                        </span>

                                    @else

                                        <span class="badge bg-danger-subtle text-danger">
                                            Inactive
                                        </span>

                                    @endif

                                </td>


                                <td>
                                    {{ $category->created_at->format('d M, Y') }}
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
                                                '{{ route('categories.edit', $category->id) }}',
                                                'Edit Category'
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
                                                '{{ route('categories.delete', $category->id) }}'
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
                                            <i class="bi bi-folder-x"></i>
                                        </div>

                                        <h6 class="fw-bold mt-3">
                                            No Categories Found
                                        </h6>

                                        <p class="text-muted mb-0">
                                            Create your first product category.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if($categories->hasPages())

                <div class="p-4 border-top">

                    {{ $categories->links() }}

                </div>

            @endif

        </div>

    </div>

</div>

@endsection