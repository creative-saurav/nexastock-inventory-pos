$(document).ready(function () {

    /* =====================================================
       SIDEBAR
    ===================================================== */

    const sidebar = $('#sidebar');
    const sidebarToggle = $('#sidebarToggle');
    const sidebarOverlay = $('#sidebarOverlay');

    sidebarToggle.on('click', function () {
        sidebar.toggleClass('show');
        sidebarOverlay.toggleClass('show');
    });

    sidebarOverlay.on('click', function () {
        sidebar.removeClass('show');
        sidebarOverlay.removeClass('show');
    });

    $(window).on('resize', function () {

        if ($(window).width() >= 992) {
            sidebar.removeClass('show');
            sidebarOverlay.removeClass('show');
        }

    });


    /* =====================================================
   PRODUCT SKU & BARCODE GENERATOR
===================================================== */

/* =====================================================
   PRODUCT SKU & BARCODE GENERATOR
===================================================== */


/* =====================================================
   GENERATE SKU
===================================================== */

$(document).on('click', '#generateSkuBtn', function (e) {

    e.preventDefault();

    const productName =
        document.getElementById('productName');

    const brandId =
        document.getElementById('brandId');

    const productSku =
        document.getElementById('productSku');


    /*
    |--------------------------------------------------------------------------
    | Check Product Name
    |--------------------------------------------------------------------------
    */

    if (!productName || !productSku) {
        return;
    }


    const name =
        productName.value.trim();


    if (!name) {

        warning(
            'Please enter product name first.'
        );

        productName.focus();

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Brand Prefix
    |--------------------------------------------------------------------------
    */

    let brandPrefix = 'PRD';


    if (brandId && brandId.value) {

        const selectedOption =
            brandId.options[brandId.selectedIndex];


        if (selectedOption) {

            const brandName =
                selectedOption.text.trim();


            const cleanBrandName =
                brandName.replace(
                    /[^a-zA-Z0-9]/g,
                    ''
                );


            if (cleanBrandName) {

                brandPrefix =
                    cleanBrandName
                        .substring(0, 3)
                        .toUpperCase();
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Product Prefix
    |--------------------------------------------------------------------------
    */

    const words =
        name
            .split(/\s+/)
            .filter(function (word) {
                return word.length > 0;
            })
            .slice(0, 2);


    let productPrefix = '';


    words.forEach(function (word) {

        const cleanWord =
            word.replace(
                /[^a-zA-Z0-9]/g,
                ''
            );


        productPrefix +=
            cleanWord
                .substring(0, 3)
                .toUpperCase();
    });


    if (!productPrefix) {

        productPrefix = 'ITEM';
    }


    /*
    |--------------------------------------------------------------------------
    | Generate SKU
    |--------------------------------------------------------------------------
    */

    const sku =
        brandPrefix +
        '-' +
        productPrefix +
        '-001';


    productSku.value = sku;


    /*
    |--------------------------------------------------------------------------
    | Success Toaster
    |--------------------------------------------------------------------------
    */

    success(
        'SKU generated successfully.'
    );

});



/* =====================================================
   GENERATE BARCODE
===================================================== */

$(document).on(
    'click',
    '#generateBarcodeBtn',
    function (e) {

        e.preventDefault();


        const productBarcode =
            document.getElementById(
                'productBarcode'
            );


        /*
        |--------------------------------------------------------------------------
        | Check Barcode Input
        |--------------------------------------------------------------------------
        */

        if (!productBarcode) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Generate Internal Barcode
        |--------------------------------------------------------------------------
        */

        const randomNumber =
            Math.floor(
                10000000 +
                Math.random() * 90000000
            );


        const barcode =
            'INT' + randomNumber;


        productBarcode.value =
            barcode;


        /*
        |--------------------------------------------------------------------------
        | Success Toaster
        |--------------------------------------------------------------------------
        */

        success(
            'Barcode generated successfully.'
        );

    }
);


/* =====================================================
   PURCHASE MODULE
   DYNAMIC ITEMS & CALCULATIONS
===================================================== */

$(document).on('shown.bs.modal modal:loaded', function () {

    const purchaseContainer =
        document.getElementById('purchaseItemsContainer');

    if (!purchaseContainer) {
        return;
    }

    calculatePurchaseTotals();

});


/* =====================================================
   ADD PURCHASE ITEM
===================================================== */

$(document).on(
    'click',
    '#addPurchaseItemBtn',
    function (e) {

        e.preventDefault();

        const container =
            document.getElementById(
                'purchaseItemsContainer'
            );

        if (!container) {
            return;
        }

        const rows =
            container.querySelectorAll(
                '.purchase-item-row'
            );

        const index = rows.length;

        /*
        |--------------------------------------------------------------------------
        | Get Product Options
        |--------------------------------------------------------------------------
        */

        const firstProductSelect =
            container.querySelector(
                '.purchase-product'
            );

        if (!firstProductSelect) {
            return;
        }

        const productOptions =
            firstProductSelect.innerHTML;


        /*
        |--------------------------------------------------------------------------
        | Create New Row
        |--------------------------------------------------------------------------
        */

        const row =
            document.createElement('tr');

        row.className =
            'purchase-item-row';

        row.innerHTML = `

            <td>

                <select
                    name="items[${index}][product_id]"
                    class="form-select purchase-product"
                    required
                >

                    ${productOptions}

                </select>

            </td>


            <td>

                <input
                    type="number"
                    name="items[${index}][quantity]"
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
                    name="items[${index}][purchase_price]"
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
                    name="items[${index}][subtotal]"
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
                >

                    <i class="bi bi-trash"></i>

                </button>

            </td>

        `;


        container.appendChild(row);


        updatePurchaseRemoveButtons();

    }
);


/* =====================================================
   REMOVE PURCHASE ITEM
===================================================== */

$(document).on(
    'click',
    '.remove-purchase-item',
    function (e) {

        e.preventDefault();

        const container =
            document.getElementById(
                'purchaseItemsContainer'
            );

        if (!container) {
            return;
        }

        const rows =
            container.querySelectorAll(
                '.purchase-item-row'
            );


        /*
        |--------------------------------------------------------------------------
        | Keep At Least One Row
        |--------------------------------------------------------------------------
        */

        if (rows.length <= 1) {

            warning(
                'At least one product is required.'
            );

            return;
        }


        const row =
            this.closest(
                '.purchase-item-row'
            );


        if (row) {

            row.remove();

        }


        reindexPurchaseItems();

        updatePurchaseRemoveButtons();

        calculatePurchaseTotals();

    }
);


/* =====================================================
   PRODUCT CHANGE
===================================================== */

$(document).on(
    'change',
    '.purchase-product',
    function () {

        const select = this;

        const row =
            select.closest(
                '.purchase-item-row'
            );

        if (!row) {
            return;
        }


        const selectedOption =
            select.options[
                select.selectedIndex
            ];


        const purchasePriceInput =
            row.querySelector(
                '.purchase-price'
            );


        if (!purchasePriceInput) {
            return;
        }


        if (
            selectedOption &&
            selectedOption.value
        ) {

            const price =
                parseFloat(
                    selectedOption.dataset.price
                ) || 0;


            purchasePriceInput.value =
                price.toFixed(2);

        } else {

            purchasePriceInput.value =
                '0.00';

        }


        calculatePurchaseRow(row);

        calculatePurchaseTotals();

    }
);


/* =====================================================
   QUANTITY / PRICE CHANGE
===================================================== */

$(document).on(
    'input',
    '.purchase-quantity, .purchase-price',
    function () {

        const row =
            this.closest(
                '.purchase-item-row'
            );

        if (!row) {
            return;
        }


        calculatePurchaseRow(row);

        calculatePurchaseTotals();

    }
);


/* =====================================================
   DISCOUNT CHANGE
===================================================== */

$(document).on(
    'input',
    '#purchaseDiscount',
    function () {

        calculatePurchaseTotals();

    }
);


/* =====================================================
   PAID AMOUNT CHANGE
===================================================== */

$(document).on(
    'input',
    '#purchasePaidAmount',
    function () {

        calculatePurchaseTotals();

    }
);


/* =====================================================
   CALCULATE SINGLE PURCHASE ROW
===================================================== */

function calculatePurchaseRow(row) {

    const quantityInput =
        row.querySelector(
            '.purchase-quantity'
        );

    const priceInput =
        row.querySelector(
            '.purchase-price'
        );

    const subtotalInput =
        row.querySelector(
            '.purchase-subtotal'
        );


    if (
        !quantityInput ||
        !priceInput ||
        !subtotalInput
    ) {
        return;
    }


    const quantity =
        parseFloat(
            quantityInput.value
        ) || 0;


    const price =
        parseFloat(
            priceInput.value
        ) || 0;


    const subtotal =
        quantity * price;


    subtotalInput.value =
        subtotal.toFixed(2);

}


/* =====================================================
   CALCULATE PURCHASE TOTALS
===================================================== */

function calculatePurchaseTotals() {

    const container =
        document.getElementById(
            'purchaseItemsContainer'
        );


    if (!container) {
        return;
    }


    const rows =
        container.querySelectorAll(
            '.purchase-item-row'
        );


    let subtotal = 0;


    rows.forEach(function (row) {

        calculatePurchaseRow(row);


        const subtotalInput =
            row.querySelector(
                '.purchase-subtotal'
            );


        if (subtotalInput) {

            subtotal +=
                parseFloat(
                    subtotalInput.value
                ) || 0;

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Discount
    |--------------------------------------------------------------------------
    */

    const discountInput =
        document.getElementById(
            'purchaseDiscount'
        );


    let discount = 0;


    if (discountInput) {

        discount =
            parseFloat(
                discountInput.value
            ) || 0;

    }


    if (discount > subtotal) {

        discount = subtotal;

        if (discountInput) {

            discountInput.value =
                discount.toFixed(2);

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Grand Total
    |--------------------------------------------------------------------------
    */

    const grandTotal =
        subtotal - discount;


    /*
    |--------------------------------------------------------------------------
    | Paid Amount
    |--------------------------------------------------------------------------
    */

    const paidInput =
        document.getElementById(
            'purchasePaidAmount'
        );


    let paidAmount = 0;


    if (paidInput) {

        paidAmount =
            parseFloat(
                paidInput.value
            ) || 0;

    }


    if (paidAmount > grandTotal) {

        paidAmount = grandTotal;

        if (paidInput) {

            paidInput.value =
                paidAmount.toFixed(2);

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Due Amount
    |--------------------------------------------------------------------------
    */

    const dueAmount =
        grandTotal - paidAmount;


    /*
    |--------------------------------------------------------------------------
    | Update Display
    |--------------------------------------------------------------------------
    */

    const subtotalDisplay =
        document.getElementById(
            'purchaseSubtotalDisplay'
        );


    const grandTotalDisplay =
        document.getElementById(
            'purchaseGrandTotalDisplay'
        );


    const dueDisplay =
        document.getElementById(
            'purchaseDueAmountDisplay'
        );


    if (subtotalDisplay) {

        subtotalDisplay.textContent =
            subtotal.toFixed(2);

    }


    if (grandTotalDisplay) {

        grandTotalDisplay.textContent =
            grandTotal.toFixed(2);

    }


    if (dueDisplay) {

        dueDisplay.textContent =
            dueAmount.toFixed(2);

    }

}


/* =====================================================
   REINDEX PURCHASE ITEMS
===================================================== */

function reindexPurchaseItems() {

    const container =
        document.getElementById(
            'purchaseItemsContainer'
        );


    if (!container) {
        return;
    }


    const rows =
        container.querySelectorAll(
            '.purchase-item-row'
        );


    rows.forEach(function (row, index) {

        const product =
            row.querySelector(
                '.purchase-product'
            );

        const quantity =
            row.querySelector(
                '.purchase-quantity'
            );

        const price =
            row.querySelector(
                '.purchase-price'
            );

        const subtotal =
            row.querySelector(
                '.purchase-subtotal'
            );


        if (product) {

            product.name =
                `items[${index}][product_id]`;

        }


        if (quantity) {

            quantity.name =
                `items[${index}][quantity]`;

        }


        if (price) {

            price.name =
                `items[${index}][purchase_price]`;

        }


        if (subtotal) {

            subtotal.name =
                `items[${index}][subtotal]`;

        }

    });

}


/* =====================================================
   UPDATE REMOVE BUTTONS
===================================================== */

function updatePurchaseRemoveButtons() {

    const container =
        document.getElementById(
            'purchaseItemsContainer'
        );


    if (!container) {
        return;
    }


    const buttons =
        container.querySelectorAll(
            '.remove-purchase-item'
        );


    const rows =
        container.querySelectorAll(
            '.purchase-item-row'
        );


    buttons.forEach(function (button) {

        button.disabled =
            rows.length <= 1;

    });

}
    



});


