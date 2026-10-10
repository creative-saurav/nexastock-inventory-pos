@extends('backend.layouts.admin')

@section('title', get_phrase('POS'))
@section('page-title', get_phrase('Point of Sale'))

@section('content')

<div class="container-fluid">

    {{-- =========================================================
         Header
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                {{ get_phrase('Point of Sale') }}
            </h4>

            <p class="text-muted mb-0">
                {{ get_phrase('Create a new sale') }}
            </p>
        </div>

        <div>
            <span class="badge bg-primary px-3 py-2">
                <i class="bi bi-receipt me-1"></i>
                {{ get_phrase('New Sale') }}
            </span>
        </div>

    </div>


    <div class="row g-4">

        {{-- =========================================================
             Left Side: Products
        ========================================================== --}}
        <div class="col-lg-7">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    {{-- Product Search --}}
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            {{ get_phrase('Search Product') }}
                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>

                            <input type="text"
                                   id="product_search"
                                   class="form-control"
                                   placeholder="{{ get_phrase('Search by product name...') }}"
                                   autocomplete="off">

                        </div>

                    </div>


                    {{-- Search Results --}}
                    <div id="product_results" class="row g-3">

                        <div class="col-12 text-center py-5 text-muted">

                            <i class="bi bi-search fs-1 d-block mb-2"></i>

                            <p class="mb-0">
                                {{ get_phrase('Search for a product to add it to the cart.') }}
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
             Right Side: Cart
        ========================================================== --}}
        <div class="col-lg-5">

            <div class="card border-0 shadow-sm">

                {{-- Cart Header --}}
                <div class="card-header bg-white border-0 py-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="mb-0">

                            <i class="bi bi-cart3 me-2"></i>

                            {{ get_phrase('Cart') }}

                        </h5>

                        <span class="badge bg-light text-dark" id="cart_count">
                            0
                        </span>

                    </div>

                </div>


                <div class="card-body">

                    {{-- =================================================
                         Cart Items
                    ================================================== --}}
                    <div id="cart_items">

                        <div class="text-center py-5 text-muted"
                             id="empty_cart">

                            <i class="bi bi-cart-x fs-1 d-block mb-2"></i>

                            <p class="mb-0">
                                {{ get_phrase('Your cart is empty.') }}
                            </p>

                        </div>

                    </div>


                    {{-- =================================================
                         Sale Information
                    ================================================== --}}
                    <div id="sale_information" class="d-none">

                        <hr>


                        {{-- Customer --}}
                        <div class="mb-3">

                            <div class="d-flex justify-content-between align-items-center mb-2">

                                <label class="form-label fw-semibold mb-0" for="customer_id">
                                    {{ get_phrase('Customer') }}
                                </label>

                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary py-0"
                                    data-bs-toggle="modal"
                                    data-bs-target="#posCustomerModal"
                                >
                                    <i class="bi bi-person-plus me-1"></i>
                                    {{ get_phrase('New') }}
                                </button>

                            </div>

                            <select id="customer_id"
                                    class="form-select">

                                <option value="">
                                    {{ get_phrase('Walk-in Customer') }}
                                </option>

                                @foreach($customers as $customer)

                                    <option value="{{ $customer->id }}">
                                        {{ $customer->name }} - {{ $customer->phone }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Discount --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                {{ get_phrase('Discount') }}
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    ৳
                                </span>

                                <input type="number"
                                       id="discount_input"
                                       class="form-control"
                                       value="0"
                                       min="0"
                                       step="0.01"
                                       placeholder="0.00">

                            </div>

                        </div>


                        {{-- =================================================
                             Cart Summary
                        ================================================== --}}
                        <div id="cart_summary">

                            {{-- Subtotal --}}
                            <div class="d-flex justify-content-between mb-2">

                                <span>
                                    {{ get_phrase('Subtotal') }}
                                </span>

                                <strong id="subtotal">
                                    0.00
                                </strong>

                            </div>


                            {{-- Discount --}}
                            <div class="d-flex justify-content-between mb-2">

                                <span>
                                    {{ get_phrase('Discount') }}
                                </span>

                                <strong id="discount">
                                    0.00
                                </strong>

                            </div>


                            {{-- Grand Total --}}
                            <div class="d-flex justify-content-between fs-5 mb-3">

                                <strong>
                                    {{ get_phrase('Total') }}
                                </strong>

                                <strong id="grand_total">
                                    0.00
                                </strong>

                            </div>

                        </div>


                        {{-- Payment Method --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                {{ get_phrase('Payment Method') }}
                            </label>

                            <select id="payment_method"
                                    class="form-select">

                                <option value="cash">
                                    {{ get_phrase('Cash') }}
                                </option>

                                <option value="card">
                                    {{ get_phrase('Card') }}
                                </option>

                                <option value="mobile_banking">
                                    {{ get_phrase('Mobile Banking') }}
                                </option>

                            </select>

                        </div>


                        {{-- Paid Amount --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                {{ get_phrase('Paid Amount') }}
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    ৳
                                </span>

                                <input type="number"
                                       id="paid_amount"
                                       class="form-control"
                                       value="0"
                                       min="0"
                                       step="0.01"
                                       placeholder="0.00">

                            </div>

                        </div>


                        {{-- Change Amount --}}
                        <div class="d-flex justify-content-between mb-3">

                            <span>
                                {{ get_phrase('Change') }}
                            </span>

                            <strong id="change_amount">
                                0.00
                            </strong>

                        </div>


                        {{-- Complete Sale --}}
                        <button type="button"
                                id="complete_sale"
                                class="btn btn-success w-100 py-2">

                            <i class="bi bi-check-circle me-1"></i>

                            {{ get_phrase('Complete Sale') }}

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- NEW CUSTOMER MODAL --}}
<div class="modal fade" id="posCustomerModal" tabindex="-1" aria-labelledby="posCustomerModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <form class="modal-content" id="posCustomerForm" autocomplete="off">

            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="posCustomerModalLabel">
                    <i class="bi bi-person-plus me-2 text-primary"></i>
                    {{ get_phrase('New Customer') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label" for="new_customer_name">
                        {{ get_phrase('Name') }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="new_customer_name" class="form-control" maxlength="255" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="new_customer_phone">
                        {{ get_phrase('Phone') }} <span class="text-danger">*</span>
                    </label>
                    <input type="tel" id="new_customer_phone" class="form-control" maxlength="20" placeholder="01XXXXXXXXX" required>
                </div>

                <div>
                    <label class="form-label" for="new_customer_email">
                        {{ get_phrase('Email') }}
                        <small class="text-muted">({{ get_phrase('optional') }})</small>
                    </label>
                    <input type="email" id="new_customer_email" class="form-control" maxlength="255">
                </div>

                <div class="text-danger small mt-3 d-none" id="posCustomerErrors"></div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    {{ get_phrase('Cancel') }}
                </button>
                <button type="submit" class="btn btn-primary" id="posCustomerSave">
                    <i class="bi bi-check-lg me-1"></i>
                    {{ get_phrase('Save & Select') }}
                </button>
            </div>

        </form>

    </div>

</div>


{{-- SALE COMPLETE MODAL --}}
<div class="modal fade" id="saleCompleteModal" tabindex="-1" aria-labelledby="saleCompleteTitle" aria-hidden="true" data-bs-backdrop="static">

    <div class="modal-dialog modal-dialog-centered modal-sm">

        <div class="modal-content text-center">

            <div class="modal-body p-4">

                <div class="mx-auto mb-3 rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                    <i class="bi bi-check-lg fs-1"></i>
                </div>

                <h5 class="fw-bold mb-1" id="saleCompleteTitle">{{ get_phrase('Sale Completed') }}</h5>

                <p class="text-muted mb-3" id="saleCompleteInvoice"></p>

                <div class="d-flex justify-content-between bg-light rounded-3 px-3 py-2 mb-4">
                    <span class="text-muted">{{ get_phrase('Change to return') }}</span>
                    <strong class="fs-5" id="saleCompleteChange">৳0.00</strong>
                </div>

                <div class="d-grid gap-2">

                    <a href="#" target="_blank" class="btn btn-primary" id="salePrintBtn">
                        <i class="bi bi-printer me-1"></i>
                        {{ get_phrase('Print Invoice') }}
                    </a>

                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        <i class="bi bi-cart-plus me-1"></i>
                        {{ get_phrase('New Sale') }}
                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script>

let cart = [];

// Paid amount follows the total until the cashier types their own amount
let paidAmountEdited = false;


/*
|--------------------------------------------------------------------------
| Search Products
|--------------------------------------------------------------------------
*/

$('#product_search').on('keyup', function () {

    let search = $(this).val().trim();


    /*
    |--------------------------------------------------------------------------
    | Empty Search
    |--------------------------------------------------------------------------
    */

    if (search.length < 1) {

        $('#product_results').html(`

            <div class="col-12 text-center py-5 text-muted">

                <i class="bi bi-search fs-1 d-block mb-2"></i>

                <p class="mb-0">
                    Search for a product to add it to the cart.
                </p>

            </div>

        `);

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Ajax Request
    |--------------------------------------------------------------------------
    */

    $.ajax({

        url: "{{ route('pos.products.search') }}",

        type: "GET",

        data: {
            search: search
        },


        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */

        success: function (products) {

            let html = '';


            /*
            |--------------------------------------------------------------------------
            | Store Products
            |--------------------------------------------------------------------------
            */

            window.posProducts = products;


            /*
            |--------------------------------------------------------------------------
            | No Products Found
            |--------------------------------------------------------------------------
            */

            if (products.length === 0) {

                html = `

                    <div class="col-12 text-center py-5 text-muted">

                        <i class="bi bi-box-seam fs-1 d-block mb-2"></i>

                        <p class="mb-0">
                            No products found.
                        </p>

                    </div>

                `;

            }


            /*
            |--------------------------------------------------------------------------
            | Products Found
            |--------------------------------------------------------------------------
            */

            else {

                products.forEach(function (product) {

                    html += `

                        <div class="col-md-6">

                            <div class="card border h-100">

                                <div class="card-body">

                                    <h6 class="mb-1">
                                        ${product.name}
                                    </h6>


                                    <p class="text-muted small mb-1">

                                        Price:
                                        ${parseFloat(product.selling_price || 0).toFixed(2)}

                                    </p>


                                    <p class="text-muted small mb-3">

                                        Stock:
                                        ${parseFloat(product.stock || 0)}

                                    </p>


                                    <button type="button"
                                            class="btn btn-sm btn-primary w-100"
                                            onclick="addToCartById(${product.id})">

                                        <i class="bi bi-cart-plus me-1"></i>

                                        Add to Cart

                                    </button>

                                </div>

                            </div>

                        </div>

                    `;

                });

            }


            $('#product_results').html(html);

        },


        /*
        |--------------------------------------------------------------------------
        | Error
        |--------------------------------------------------------------------------
        */

        error: function () {

            $('#product_results').html(`

                <div class="col-12">

                    <div class="alert alert-danger">

                        Unable to load products.

                    </div>

                </div>

            `);

        }

    });

});


/*
|--------------------------------------------------------------------------
| Add Product By ID
|--------------------------------------------------------------------------
*/

function addToCartById(productId) {

    let product = window.posProducts.find(function (item) {

        return item.id == productId;

    });


    if (!product) {
        error('Product not found.');

        return;

    }


    addToCart(product);

}


/*
|--------------------------------------------------------------------------
| Add Product To Cart
|--------------------------------------------------------------------------
*/

function addToCart(product) {

    let existingItem = cart.find(function (item) {

        return item.id === product.id;

    });


    /*
    |--------------------------------------------------------------------------
    | Existing Product
    |--------------------------------------------------------------------------
    */

    if (existingItem) {

        if (existingItem.quantity >= parseFloat(product.stock)) {

            error('Not enough stock available.');

            return;

        }


        existingItem.quantity++;

    }


    /*
    |--------------------------------------------------------------------------
    | New Product
    |--------------------------------------------------------------------------
    */

    else {

        cart.push({

            id: product.id,

            name: product.name,

            price: parseFloat(product.selling_price || 0),

            stock: parseFloat(product.stock || 0),

            quantity: 1

        });

    }


    renderCart();

}


/*
|--------------------------------------------------------------------------
| Render Cart
|--------------------------------------------------------------------------
*/

function renderCart() {

    let cartHtml = '';


    /*
    |--------------------------------------------------------------------------
    | Empty Cart
    |--------------------------------------------------------------------------
    */

    if (cart.length === 0) {

        $('#cart_items').html(`

            <div class="text-center py-5 text-muted"
                 id="empty_cart">

                <i class="bi bi-cart-x fs-1 d-block mb-2"></i>

                <p class="mb-0">
                    Your cart is empty.
                </p>

            </div>

        `);

        $('#sale_information').addClass('d-none');

        $('#cart_count').text(0);

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | Show Sale Information
    |--------------------------------------------------------------------------
    */

    $('#sale_information').removeClass('d-none');


    let subtotal = 0;

    let totalQuantity = 0;


    /*
    |--------------------------------------------------------------------------
    | Loop Cart Items
    |--------------------------------------------------------------------------
    */

    cart.forEach(function (item, index) {

        let itemTotal = item.price * item.quantity;


        subtotal += itemTotal;

        totalQuantity += item.quantity;


        cartHtml += `

            <div class="border rounded p-3 mb-2">

                {{-- Product Information --}}
                <div class="d-flex justify-content-between">

                    <div>

                        <h6 class="mb-1">
                            ${item.name}
                        </h6>

                        <small class="text-muted">

                            ${item.price.toFixed(2)}
                            ×
                            ${item.quantity}

                        </small>

                    </div>


                    <strong>

                        ${itemTotal.toFixed(2)}

                    </strong>

                </div>


                {{-- Quantity Controls --}}
                <div class="d-flex justify-content-between align-items-center mt-2">


                    <div class="btn-group btn-group-sm">

                        {{-- Decrease --}}
                        <button type="button"
                                class="btn btn-outline-secondary"
                                onclick="decreaseQuantity(${index})">

                            -

                        </button>


                        {{-- Quantity --}}
                        <button type="button"
                                class="btn btn-outline-secondary disabled">

                            ${item.quantity}

                        </button>


                        {{-- Increase --}}
                        <button type="button"
                                class="btn btn-outline-secondary"
                                onclick="increaseQuantity(${index})">

                            +

                        </button>

                    </div>


                    {{-- Remove --}}
                    <button type="button"
                            class="btn btn-sm btn-outline-danger"
                            onclick="removeFromCart(${index})">

                        <i class="bi bi-trash"></i>

                    </button>

                </div>


                {{-- Available Stock --}}
                <small class="text-muted d-block mt-2">

                    Available stock:
                    ${item.stock}

                </small>

            </div>

        `;

    });


    $('#cart_items').html(cartHtml);


    $('#cart_count').text(totalQuantity);


    /*
    |--------------------------------------------------------------------------
    | Calculate Total
    |--------------------------------------------------------------------------
    */

    calculateTotal();

}


/*
|--------------------------------------------------------------------------
| Calculate Total
|--------------------------------------------------------------------------
*/

function calculateTotal() {

    let subtotal = 0;


    /*
    |--------------------------------------------------------------------------
    | Calculate Subtotal
    |--------------------------------------------------------------------------
    */

    cart.forEach(function (item) {

        subtotal += item.price * item.quantity;

    });


    /*
    |--------------------------------------------------------------------------
    | Discount
    |--------------------------------------------------------------------------
    */

    let discount = parseFloat($('#discount_input').val()) || 0;


    /*
    |--------------------------------------------------------------------------
    | Discount Cannot Exceed Subtotal
    |--------------------------------------------------------------------------
    */

    if (discount > subtotal) {

        discount = subtotal;

        $('#discount_input').val(discount.toFixed(2));

    }


    /*
    |--------------------------------------------------------------------------
    | Grand Total
    |--------------------------------------------------------------------------
    */

    let grandTotal = subtotal - discount;


    /*
    |--------------------------------------------------------------------------
    | Paid Amount
    |--------------------------------------------------------------------------
    */

    grandTotal = Math.round(grandTotal * 100) / 100;

    if (!paidAmountEdited) {

        $('#paid_amount').val(grandTotal.toFixed(2));

    }

    let paidAmount = parseFloat($('#paid_amount').val()) || 0;


    /*
    |--------------------------------------------------------------------------
    | Change
    |--------------------------------------------------------------------------
    */

    let changeAmount = paidAmount - grandTotal;


    /*
    |--------------------------------------------------------------------------
    | Update UI
    |--------------------------------------------------------------------------
    */

    $('#subtotal').text(subtotal.toFixed(2));

    $('#discount').text(discount.toFixed(2));

    $('#grand_total').text(grandTotal.toFixed(2));


    if (changeAmount < 0) {

        $('#change_amount').text('0.00');

    } else {

        $('#change_amount').text(changeAmount.toFixed(2));

    }

}


/*
|--------------------------------------------------------------------------
| Discount Change
|--------------------------------------------------------------------------
*/

$('#discount_input').on('input', function () {

    calculateTotal();

});


/*
|--------------------------------------------------------------------------
| Paid Amount Change
|--------------------------------------------------------------------------
*/

$('#paid_amount').on('input', function () {

    paidAmountEdited = $(this).val() !== '';

    calculateTotal();

});


/*
|--------------------------------------------------------------------------
| Increase Quantity
|--------------------------------------------------------------------------
*/

function increaseQuantity(index) {

    let item = cart[index];


    if (item.quantity >= item.stock) {

        error('Not enough stock available.');

        return;

    }


    item.quantity++;


    renderCart();

}


/*
|--------------------------------------------------------------------------
| Decrease Quantity
|--------------------------------------------------------------------------
*/

function decreaseQuantity(index) {

    if (cart[index].quantity > 1) {

        cart[index].quantity--;

    }

    else {

        cart.splice(index, 1);

    }


    renderCart();

}


/*
|--------------------------------------------------------------------------
| Remove From Cart
|--------------------------------------------------------------------------
*/

function removeFromCart(index) {

    cart.splice(index, 1);

    renderCart();

}


/*
|--------------------------------------------------------------------------
| Complete Sale
|--------------------------------------------------------------------------
*/

function resetPos() {

    cart = [];

    paidAmountEdited = false;

    window.posProducts = [];

    $('#customer_id').val('');

    $('#discount_input').val(0);

    $('#paid_amount').val(0);

    $('#subtotal, #discount, #grand_total, #change_amount').text('0.00');

    // Clear search so old stock numbers are not shown
    $('#product_search').val('').trigger('keyup');

    renderCart();

}


$('#complete_sale').on('click', function () {

    if (cart.length === 0) {
        warning('Please add at least one product.');
        return;
    }

    let subtotal = 0;

    cart.forEach(function (item) {

        subtotal += item.price * item.quantity;

    });

    let discount = parseFloat($('#discount_input').val()) || 0;

    let grandTotal = subtotal - discount;

    let paidAmount = parseFloat($('#paid_amount').val()) || 0;

    let customerId = $('#customer_id').val();

    let paymentMethod = $('#payment_method').val();



    if (discount < 0) {

        warning('Discount cannot be negative.');

        return;
    }

    if (discount > subtotal) {

        warning('Discount cannot be greater than subtotal.');

        return;
    }

    grandTotal = Math.round(grandTotal * 100) / 100;

    if (paidAmount < grandTotal) {

        warning('Paid amount is less than the total amount.');

        return;
    }


    let button = $(this);

    button.prop('disabled', true);

    $.ajax({

        url: "{{ route('pos.store') }}",

        type: "POST",

        data: {

            _token: "{{ csrf_token() }}",

            customer_id: customerId,

            cart: cart,

            subtotal: subtotal,

            discount: discount,

            grand_total: grandTotal,

            paid_amount: paidAmount,

            change_amount: paidAmount - grandTotal,

            payment_method: paymentMethod

        },

        success: function (response) {
            if (response.status) {
               
                const changeAmount = paidAmount - grandTotal;

                resetPos();

                showSaleComplete(response, changeAmount);

            } else {

                error(response.message);

            }

        },

        error: function (xhr) {

                if (xhr.responseJSON && xhr.responseJSON.message) {

                    error(xhr.responseJSON.message);

                } else {

                    error('Something went wrong.');

                }

            },

        complete: function () {

            button.prop('disabled', false);

        }

    });

});


/*
|--------------------------------------------------------------------------
| Sale complete: offer to print the invoice
|--------------------------------------------------------------------------
*/

const invoiceUrlTemplate = "{{ route('sales.invoice', ['id' => '__ID__', 'print' => 1]) }}";

function showSaleComplete(response, changeAmount) {

    $('#saleCompleteInvoice').text('Invoice ' + response.invoice_no);

    $('#saleCompleteChange').text('৳' + Number(changeAmount).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }));

    $('#salePrintBtn').attr('href', invoiceUrlTemplate.replace('__ID__', response.sale_id));

    bootstrap.Modal.getOrCreateInstance('#saleCompleteModal').show();
}

// Printing counts as done: close the dialog so the next customer can be served
$('#salePrintBtn').on('click', function () {
    bootstrap.Modal.getOrCreateInstance('#saleCompleteModal').hide();
});

// Put the cursor back in product search for the next sale
$('#saleCompleteModal').on('hidden.bs.modal', function () {
    $('#product_search').trigger('focus');
});


/*
|--------------------------------------------------------------------------
| Quick-add customer
|--------------------------------------------------------------------------
*/

$('#posCustomerModal').on('shown.bs.modal', function () {
    $('#new_customer_name').trigger('focus');
});

$('#posCustomerModal').on('hidden.bs.modal', function () {
    $('#posCustomerForm')[0].reset();
    $('#posCustomerErrors').addClass('d-none').empty();
});

$('#posCustomerForm').on('submit', function (e) {

    e.preventDefault();

    const saveButton = $('#posCustomerSave');
    const errorBox = $('#posCustomerErrors');

    saveButton.prop('disabled', true);
    errorBox.addClass('d-none').empty();

    $.ajax({

        url: "{{ route('pos.customers.store') }}",

        type: "POST",

        data: {
            _token: "{{ csrf_token() }}",
            name: $('#new_customer_name').val(),
            phone: $('#new_customer_phone').val(),
            email: $('#new_customer_email').val(),
        },

        success: function (response) {

            const customer = response.customer;
            const select = $('#customer_id');

            if (! select.find('option[value="' + customer.id + '"]').length) {
                select.append($('<option>', {
                    value: customer.id,
                    text: customer.name + ' - ' + (customer.phone || ''),
                }));
            }

            select.val(customer.id);

            bootstrap.Modal.getOrCreateInstance('#posCustomerModal').hide();

            response.existing ? warning(response.message) : success(response.message);
        },

        error: function (xhr) {

            const errors = xhr.responseJSON && xhr.responseJSON.errors;

            if (errors) {
                errorBox.html(Object.values(errors).map(function (messages) {
                    return $('<div>').text(messages[0]).html();
                }).join('<br>'));
            } else {
                errorBox.text('Could not save the customer. Please try again.');
            }

            errorBox.removeClass('d-none');
        },

        complete: function () {
            saveButton.prop('disabled', false);
        }

    });

});
</script>

@endpush