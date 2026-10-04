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
    



});


