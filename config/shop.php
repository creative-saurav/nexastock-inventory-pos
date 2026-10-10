<?php

/*
|--------------------------------------------------------------------------
| Shop Information (defaults)
|--------------------------------------------------------------------------
|
| These are only fallbacks. The real values are edited from
| Settings in the admin panel and read with setting('name') etc.
|
*/

return [

    'name'    => env('SHOP_NAME', 'NexaStock'),

    'tagline' => env('SHOP_TAGLINE', 'Inventory & POS'),

    'address' => env('SHOP_ADDRESS', 'House 12, Road 5, Dhanmondi, Dhaka 1205'),

    'phone'   => env('SHOP_PHONE', '+880 1700-000000'),

    'email'   => env('SHOP_EMAIL', 'info@nexastock.com'),

    'logo'    => null,

    'invoice_note' => env('SHOP_INVOICE_NOTE', 'Goods once sold are not returnable or exchangeable without the invoice within 7 days.'),

    'invoice_footer' => 'Thank you for shopping with us!',

];
