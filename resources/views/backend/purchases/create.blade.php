<form
    action="{{ route('purchases.store') }}"
    method="POST"
    id="purchaseCreateForm"
>

@csrf

{{-- PURCHASE INFORMATION --}}
<div class="mb-4">

    <div class="d-flex align-items-center mb-3">

        <div class="me-2">
            <span class="badge bg-primary-subtle text-primary rounded-circle p-2">
                <i class="bi bi-cart-check"></i>
            </span>
        </div>

        <div>
            <h6 class="mb-0 fw-semibold">
                Purchase Information
            </h6>

            <small class="text-muted">
                Select supplier and enter purchase information.
            </small>
        </div>

    </div>


    <div class="row g-3">

        {{-- SUPPLIER --}}
        <div class="col-md-6">

            <label class="form-label fw-medium">
                Supplier <span class="text-danger">*</span>
            </label>

            <select
                name="supplier_id"
                class="form-select"
                required
            >

                <option value="">
                    Select Supplier
                </option>

                @foreach($suppliers as $supplier)

                    <option
                        value="{{ $supplier->id }}"
                        {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}
                    >
                        {{ $supplier->name }}
                        @if($supplier->phone)
                            - {{ $supplier->phone }}
                        @endif
                    </option>

                @endforeach

            </select>

        </div>


        {{-- PURCHASE DATE --}}
        <div class="col-md-6">

            <label class="form-label fw-medium">
                Purchase Date <span class="text-danger">*</span>
            </label>

            <div class="input-group">

                <span class="input-group-text">
                    <i class="bi bi-calendar3"></i>
                </span>

                <input
                    type="date"
                    name="purchase_date"
                    class="form-control"
                    value="{{ old('purchase_date', date('Y-m-d')) }}"
                    required
                >

            </div>

        </div>

    </div>

</div>


{{-- PURCHASE ITEMS --}}
<div class="border-top pt-4 mb-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div class="d-flex align-items-center">

            <div class="me-2">

                <span class="badge bg-info-subtle text-info rounded-circle p-2">
                    <i class="bi bi-boxes"></i>
                </span>

            </div>

            <div>

                <h6 class="mb-0 fw-semibold">
                    Purchase Items
                </h6>

                <small class="text-muted">
                    Add products included in this purchase.
                </small>

            </div>

        </div>


        <button
            type="button"
            class="btn btn-sm btn-outline-primary"
            id="addPurchaseItemBtn"
        >
            <i class="bi bi-plus-lg me-1"></i>
            Add Product
        </button>

    </div>


    <div class="table-responsive">

        <table class="table table-bordered align-middle mb-0">

            <thead class="table-light">

                <tr>

                    <th style="min-width: 250px;">
                        Product
                    </th>

                    <th style="width: 130px;">
                        Quantity
                    </th>

                    <th style="width: 160px;">
                        Purchase Price
                    </th>

                    <th style="width: 160px;">
                        Subtotal
                    </th>

                    <th style="width: 70px;" class="text-center">
                        Action
                    </th>

                </tr>

            </thead>


            <tbody id="purchaseItemsContainer">

                <tr class="purchase-item-row">

                    <td>

                        <select
                            name="items[0][product_id]"
                            class="form-select purchase-product"
                            required
                        >

                            <option value="">
                                Select Product
                            </option>

                            @foreach($products as $product)

                                <option
                                    value="{{ $product->id }}"
                                    data-price="{{ $product->purchase_price }}"
                                >
                                    {{ $product->name }}
                                    @if($product->sku)
                                        ({{ $product->sku }})
                                    @endif
                                </option>

                            @endforeach

                        </select>

                    </td>


                    <td>

                        <input
                            type="number"
                            name="items[0][quantity]"
                            class="form-control purchase-quantity"
                            value="1"
                            min="0.01"
                            step="0.01"
                            required
                        >

                    </td>


                    <td>

                        <input
                            type="number"
                            name="items[0][purchase_price]"
                            class="form-control purchase-price"
                            value="0"
                            min="0"
                            step="0.01"
                            required
                        >

                    </td>


                    <td>

                        <input
                            type="number"
                            name="items[0][subtotal]"
                            class="form-control purchase-subtotal"
                            value="0"
                            readonly
                        >

                    </td>


                    <td class="text-center">

                        <button
                            type="button"
                            class="btn btn-sm btn-light border text-danger remove-purchase-item"
                            title="Remove"
                            disabled
                        >
                            <i class="bi bi-trash"></i>
                        </button>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>


{{-- SUMMARY --}}
<div class="border-top pt-4 mb-4">

    <div class="d-flex align-items-center mb-3">

        <div class="me-2">

            <span class="badge bg-success-subtle text-success rounded-circle p-2">
                <i class="bi bi-calculator"></i>
            </span>

        </div>

        <div>

            <h6 class="mb-0 fw-semibold">
                Purchase Summary
            </h6>

            <small class="text-muted">
                Review purchase amount and payment information.
            </small>

        </div>

    </div>


    <div class="row g-3 justify-content-end">

        <div class="col-md-5">

            <div class="d-flex justify-content-between mb-2">

                <span class="text-muted">
                    Subtotal
                </span>

                <strong>
                    ৳ <span id="purchaseSubtotalDisplay">0.00</span>
                </strong>

            </div>


            <div class="mb-3">

                <label class="form-label fw-medium">
                    Discount
                </label>

                <div class="input-group">

                    <span class="input-group-text">
                        ৳
                    </span>

                    <input
                        type="number"
                        name="discount"
                        id="purchaseDiscount"
                        class="form-control"
                        value="{{ old('discount', 0) }}"
                        min="0"
                        step="0.01"
                    >

                </div>

            </div>


            <div class="d-flex justify-content-between mb-3">

                <span class="fw-semibold">
                    Grand Total
                </span>

                <strong class="text-primary">
                    ৳ <span id="purchaseGrandTotalDisplay">0.00</span>
                </strong>

            </div>


            <div class="mb-3">

                <label class="form-label fw-medium">
                    Paid Amount
                </label>

                <div class="input-group">

                    <span class="input-group-text">
                        ৳
                    </span>

                    <input
                        type="number"
                        name="paid_amount"
                        id="purchasePaidAmount"
                        class="form-control"
                        value="{{ old('paid_amount', 0) }}"
                        min="0"
                        step="0.01"
                    >

                </div>

            </div>


            <div class="d-flex justify-content-between mb-3">

                <span class="fw-semibold">
                    Due Amount
                </span>

                <strong class="text-danger">
                    ৳ <span id="purchaseDueAmountDisplay">0.00</span>
                </strong>

            </div>

        </div>

    </div>

</div>


{{-- ADDITIONAL INFORMATION --}}
<div class="border-top pt-4 mb-4">

    <div class="row g-3">

        <div class="col-md-8">

            <label class="form-label fw-medium">
                Note
            </label>

            <textarea
                name="note"
                class="form-control"
                rows="3"
                placeholder="Enter any additional purchase note..."
            >{{ old('note') }}</textarea>

        </div>


        <div class="col-md-4">

            <label class="form-label fw-medium">
                Purchase Status
            </label>

            <select
                name="status"
                class="form-select"
            >

                <option
                    value="received"
                    {{ old('status', 'received') === 'received' ? 'selected' : '' }}
                >
                    Received
                </option>

                <option
                    value="pending"
                    {{ old('status') === 'pending' ? 'selected' : '' }}
                >
                    Pending
                </option>

            </select>

            <small class="text-muted">
                Stock will be increased when the purchase is received.
            </small>

        </div>

    </div>

</div>


{{-- FORM ACTIONS --}}
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
                Save Purchase
            </button>

        </div>

    </div>

</div>


</form>
