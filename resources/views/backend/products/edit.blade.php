<form
    action="{{ route('products.update', $product->id) }}"
    method="POST"
    enctype="multipart/form-data"
    id="productEditForm"
>

    @csrf


    {{-- =====================================================
         BASIC INFORMATION
    ====================================================== --}}

    <div class="mb-4">

        <div class="d-flex align-items-center mb-3">

            <div class="me-2">
                <span class="badge bg-primary-subtle text-primary rounded-circle p-2">
                    <i class="bi bi-box-seam"></i>
                </span>
            </div>

            <div>
                <h6 class="mb-0 fw-semibold">
                    Basic Information
                </h6>

                <small class="text-muted">
                    Update the basic details of your product.
                </small>
            </div>

        </div>


        <div class="row g-3">


            {{-- Current Image --}}
            @if($product->image)

                <div class="col-md-4">

                    <div class="mb-3">

                        <label class="form-label fw-medium">
                            Current Image
                        </label>

                        <div class="product-current-image">

                            <img
                                src="{{ asset($product->image) }}"
                                alt="{{ $product->name }}"
                            >

                        </div>

                    </div>

                </div>

            @endif


            {{-- Product Name --}}
            <div class="{{ $product->image ? 'col-md-8' : 'col-md-12' }}">

                <div class="mb-3">

                    <label class="form-label fw-medium">
                        Product Name
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        placeholder="e.g. Samsung Galaxy A55"
                        value="{{ old('name', $product->name) }}"
                        required
                    >

                </div>

            </div>


            {{-- Change Image --}}
            <div class="col-md-4">

                <div class="mb-3">

                    <label class="form-label fw-medium">
                        {{ $product->image ? 'Change Image' : 'Product Image' }}
                    </label>

                    <input
                        type="file"
                        name="image"
                        class="form-control"
                        accept="image/*"
                    >

                    <small class="text-muted">
                        JPG, JPEG, PNG or WEBP. Maximum 2MB.
                    </small>

                    @if($product->image)

                        <small class="d-block text-muted mt-1">
                            Leave empty to keep the current image.
                        </small>

                    @endif

                </div>

            </div>


            {{-- Category --}}
            <div class="col-md-4">

                <div class="mb-3">

                    <label class="form-label fw-medium">
                        Category
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="category_id"
                        class="form-select"
                        required
                    >

                        @foreach($categories as $category)

                            <option
                                value="{{ $category->id }}"
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}
                            >
                                {{ $category->name }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            {{-- Brand --}}
            <div class="col-md-4">

                <div class="mb-3">

                    <label class="form-label fw-medium">
                        Brand
                    </label>

                    <select
                        name="brand_id"
                        class="form-select"
                    >

                        <option value="">
                            Select Brand
                        </option>

                        @foreach($brands as $brand)

                            <option
                                value="{{ $brand->id }}"
                                {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}
                            >
                                {{ $brand->name }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            {{-- Unit --}}
            <div class="col-md-4">

                <div class="mb-3">

                    <label class="form-label fw-medium">
                        Unit
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="unit_id"
                        class="form-select"
                        required
                    >

                        @foreach($units as $unit)

                            <option
                                value="{{ $unit->id }}"
                                {{ old('unit_id', $product->unit_id) == $unit->id ? 'selected' : '' }}
                            >
                                {{ $unit->name }}
                                ({{ $unit->short_name }})
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>

        </div>

    </div>



    {{-- =====================================================
         PRODUCT IDENTIFICATION
    ====================================================== --}}

    <div class="border-top pt-4 mb-4">

        <div class="d-flex align-items-center mb-3">

            <div class="me-2">
                <span class="badge bg-info-subtle text-info rounded-circle p-2">
                    <i class="bi bi-upc-scan"></i>
                </span>
            </div>

            <div>
                <h6 class="mb-0 fw-semibold">
                    Product Identification
                </h6>

                <small class="text-muted">
                    Product identifiers are fixed after creation.
                </small>
            </div>

        </div>


        <div class="row g-3">


            {{-- SKU --}}
            <div class="col-md-6">

                <div class="mb-3">

                    <label class="form-label fw-medium">
                        SKU
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-tag"></i>
                        </span>

                        <input
                            type="text"
                            name="sku"
                            class="form-control"
                            value="{{ $product->sku }}"
                            readonly
                        >

                    </div>

                    <small class="text-muted">
                        SKU cannot be changed after product creation.
                    </small>

                </div>

            </div>


            {{-- Barcode --}}
            <div class="col-md-6">

                <div class="mb-3">

                    <label class="form-label fw-medium">
                        Barcode
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-upc"></i>
                        </span>

                        <input
                            type="text"
                            name="barcode"
                            class="form-control"
                            value="{{ $product->barcode }}"
                            readonly
                        >

                    </div>

                    <small class="text-muted">
                        Barcode cannot be changed after product creation.
                    </small>

                </div>

            </div>

        </div>

    </div>



    {{-- =====================================================
         PRICING & INVENTORY
    ====================================================== --}}

    <div class="border-top pt-4 mb-4">

        <div class="d-flex align-items-center mb-3">

            <div class="me-2">
                <span class="badge bg-success-subtle text-success rounded-circle p-2">
                    <i class="bi bi-cash-stack"></i>
                </span>
            </div>

            <div>
                <h6 class="mb-0 fw-semibold">
                    Pricing & Inventory
                </h6>

                <small class="text-muted">
                    Update pricing and inventory warning settings.
                </small>
            </div>

        </div>


        <div class="row g-3">


            {{-- Purchase Price --}}
            <div class="col-md-6">

                <div class="mb-3">

                    <label class="form-label fw-medium">
                        Purchase Price
                        <span class="text-danger">*</span>
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            ৳
                        </span>

                        <input
                            type="number"
                            name="purchase_price"
                            class="form-control"
                            value="{{ old('purchase_price', $product->purchase_price) }}"
                            min="0"
                            step="0.01"
                            required
                        >

                    </div>

                    <small class="text-muted">
                        Current/default purchase cost per unit.
                    </small>

                </div>

            </div>


            {{-- Selling Price --}}
            <div class="col-md-6">

                <div class="mb-3">

                    <label class="form-label fw-medium">
                        Selling Price
                        <span class="text-danger">*</span>
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            ৳
                        </span>

                        <input
                            type="number"
                            name="selling_price"
                            class="form-control"
                            value="{{ old('selling_price', $product->selling_price) }}"
                            min="0"
                            step="0.01"
                            required
                        >

                    </div>

                    <small class="text-muted">
                        Current/default selling price per unit.
                    </small>

                </div>

            </div>


            {{-- Current Stock --}}
            <div class="col-md-6">

                <div class="mb-3">

                    <label class="form-label fw-medium">
                        Current Stock
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-boxes"></i>
                        </span>

                        <input
                            type="number"
                            class="form-control"
                            value="{{ $product->stock }}"
                            readonly
                        >

                    </div>

                    <small class="text-muted">
                        Stock is managed through purchases, sales and adjustments.
                    </small>

                </div>

            </div>


            {{-- Alert Quantity --}}
            <div class="col-md-6">

                <div class="mb-3">

                    <label class="form-label fw-medium">
                        Alert Quantity
                        <span class="text-danger">*</span>
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-exclamation-triangle"></i>
                        </span>

                        <input
                            type="number"
                            name="alert_quantity"
                            class="form-control"
                            value="{{ old('alert_quantity', $product->alert_quantity) }}"
                            min="0"
                            step="0.01"
                            required
                        >

                    </div>

                    <small class="text-muted">
                        Low stock warning threshold.
                    </small>

                </div>

            </div>

        </div>

    </div>



    {{-- =====================================================
         ADDITIONAL INFORMATION
    ====================================================== --}}

    <div class="border-top pt-4 mb-4">

        <div class="d-flex align-items-center mb-3">

            <div class="me-2">
                <span class="badge bg-secondary-subtle text-secondary rounded-circle p-2">
                    <i class="bi bi-info-circle"></i>
                </span>
            </div>

            <div>
                <h6 class="mb-0 fw-semibold">
                    Additional Information
                </h6>

                <small class="text-muted">
                    Update description and product availability.
                </small>
            </div>

        </div>


        <div class="row g-3">


            {{-- Description --}}
            <div class="col-md-8">

                <div class="mb-3">

                    <label class="form-label fw-medium">
                        Description
                    </label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="4"
                        placeholder="Enter product description..."
                    >{{ old('description', $product->description) }}</textarea>

                </div>

            </div>


            {{-- Status --}}
            <div class="col-md-4">

                <div class="mb-3">

                    <label class="form-label fw-medium">
                        Status
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="status"
                        class="form-select"
                        required
                    >

                        <option
                            value="1"
                            {{ old('status', $product->status) == 1 ? 'selected' : '' }}
                        >
                            Active
                        </option>

                        <option
                            value="0"
                            {{ old('status', $product->status) == 0 ? 'selected' : '' }}
                        >
                            Inactive
                        </option>

                    </select>

                    <small class="text-muted">
                        Inactive products cannot be used for normal sales.
                    </small>

                </div>

            </div>

        </div>

    </div>



    {{-- =====================================================
         FORM ACTIONS
    ====================================================== --}}

    <div class="border-top pt-3">

        <div class="d-flex justify-content-between align-items-center">

            <small class="text-muted">

                <i class="bi bi-info-circle me-1"></i>

                Fields marked with
                <span class="text-danger">*</span>
                are required.

            </small>


            <div class="d-flex gap-2">

                <button
                    type="button"
                    class="btn btn-light border"
                    data-bs-dismiss="modal"
                >
                    <i class="bi bi-x-lg me-1"></i>
                    Cancel
                </button>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="bi bi-check-lg me-1"></i>
                    Update Product
                </button>

            </div>

        </div>

    </div>

</form>