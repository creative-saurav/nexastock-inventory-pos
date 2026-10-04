@extends('backend.layouts.admin')

@section('title', 'Products')
@section('page-title', 'Products')

@section('content')

<div class="container-fluid px-0">

    {{-- Page Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Products
            </h4>

            <p class="text-muted mb-0">
                Manage your products and inventory
            </p>
        </div>

        <div class="mt-3 mt-md-0">

            <button
                type="button"
                class="btn btn-primary px-4"
                onclick="modal(
                    'modal-lg',
                    '{{ route('products.create') }}',
                    'Add Product'
                )"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Add Product
            </button>

        </div>

    </div>


    {{-- Product Card --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            {{-- Search Header --}}
            <div class="p-4 border-bottom">

                <div class="row align-items-center">

                    <div class="col-md-6">

                        <h6 class="fw-bold mb-1">
                            All Products
                        </h6>

                        <small class="text-muted">

                            @if(request('search'))

                                {{ $products->total() }}
                                result(s) found for
                                <strong>"{{ request('search') }}"</strong>

                            @else

                                {{ $products->total() }} products found

                            @endif

                        </small>

                    </div>


                    <div class="col-md-6 mt-3 mt-md-0">

                        <form
                            action="{{ route('products') }}"
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
                                    placeholder="Search product, SKU or barcode..."
                                    value="{{ request('search') }}"
                                >

                                @if(request('search'))

                                    <a
                                        href="{{ route('products') }}"
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


            {{-- Product Table --}}
            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead>

                        <tr>

                            <th class="ps-4">#</th>

                            <th>Product</th>

                            <th>Category</th>

                            <th>Brand</th>

                            <th>SKU</th>

                            <th>Stock</th>

                            <th>Price</th>

                            <th>Status</th>

                            <th class="text-end pe-4">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($products as $product)

                            <tr>

                                {{-- Serial --}}
                                <td class="ps-4">
                                    {{ $products->firstItem() + $loop->index }}
                                </td>


                                {{-- Product --}}
                                <td>

                                    <div class="d-flex align-items-center gap-3">

                                        <div class="product-list-image">

                                            @if($product->image)

                                                <img
                                                    src="{{ asset($product->image) }}"
                                                    alt="{{ $product->name }}"
                                                >

                                            @else

                                                <i class="bi bi-box-seam text-muted"></i>

                                            @endif

                                        </div>


                                        <div>

                                            <div class="fw-semibold">
                                                {{ $product->name }}
                                            </div>

                                            @if($product->description)

                                                <small class="text-muted">
                                                    {{ Str::limit($product->description, 35) }}
                                                </small>

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                {{-- Category --}}
                                <td>
                                    {{ $product->category->name ?? 'N/A' }}
                                </td>


                                {{-- Brand --}}
                                <td>
                                    {{ $product->brand->name ?? 'N/A' }}
                                </td>


                                {{-- SKU --}}
                                <td>
                                    <code>
                                        {{ $product->sku }}
                                    </code>
                                </td>


                                {{-- Stock --}}
                                <td>

                                    <span
                                        class="
                                            {{ $product->stock <= $product->alert_quantity
                                                ? 'text-danger fw-semibold'
                                                : 'text-success fw-semibold'
                                            }}
                                        "
                                    >
                                        {{ $product->stock }}
                                        {{ $product->unit->short_name ?? '' }}
                                    </span>

                                </td>


                                {{-- Price --}}
                                <td>

                                    <div class="fw-semibold">
                                        ৳ {{ number_format($product->selling_price, 2) }}
                                    </div>

                                    <small class="text-muted">
                                        Buy:
                                        ৳ {{ number_format($product->purchase_price, 2) }}
                                    </small>

                                </td>


                                {{-- Status --}}
                                <td>

                                    @if($product->status)

                                        <span class="badge bg-success-subtle text-success">
                                            Active
                                        </span>

                                    @else

                                        <span class="badge bg-danger-subtle text-danger">
                                            Inactive
                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td class="text-end pe-4">

                                    <div class="btn-group">

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-light"
                                            title="Edit"
                                            onclick="edit_modal(
                                                'modal-lg',
                                                '{{ route('products.edit', $product->id) }}',
                                                'Edit Product'
                                            )"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </button>


                                        <button
                                            type="button"
                                            class="btn btn-sm btn-light text-danger"
                                            title="Delete"
                                            onclick="delete_modal(
                                                '{{ route('products.delete', $product->id) }}'
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
                                    colspan="9"
                                    class="text-center py-5"
                                >

                                    <div class="empty-state">

                                        <div class="empty-state-icon">
                                            <i class="bi bi-box-seam"></i>
                                        </div>

                                        <h6 class="fw-bold mt-3">
                                            No Products Found
                                        </h6>

                                        <p class="text-muted mb-0">
                                            Add your first product to get started.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if($products->hasPages())

                <div class="p-4 border-top">

                    {{ $products->links() }}

                </div>

            @endif

        </div>

    </div>

</div>

@endsection