<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Realistic demo data for a Bangladeshi retail shop.
 *
 * Adds products (with generated SVG images), brands, suppliers, customers,
 * purchases (stock comes in), POS sales over the last 30 days (stock goes out)
 * and two months of expenses. Existing data is never changed.
 *
 * Run: php artisan db:seed --class=DemoDataSeeder
 * Safe to run once; it stops if the demo products already exist.
 */
class DemoDataSeeder extends Seeder
{
    /** Category name => [tone colour, soft colour] for the product images */
    private const TONES = [
        'Electronics' => ['#6366f1', '#eef2ff'],
        'Mobile & Accessories' => ['#0ea5e9', '#e0f2fe'],
        'Computer & Accessories' => ['#8b5cf6', '#f3e8ff'],
        'Home Appliances' => ['#10b981', '#d1fae5'],
        'Kitchen & Dining' => ['#f59e0b', '#fef3c7'],
        'Grocery' => ['#84cc16', '#ecfccb'],
        'Beverages' => ['#ef4444', '#fee2e2'],
        'Personal Care' => ['#ec4899', '#fce7f3'],
    ];

    /**
     * [name, category, brand, unit short name, selling price, alert qty, opening purchase qty, emoji, description]
     * Opening qty 0 means it was never bought, so it shows as out of stock.
     */
    private const PRODUCTS = [
        // Electronics
        ['Sony WH-1000XM5 Wireless Noise Cancelling Headphones', 'Electronics', 'Sony', 'Pc', 42500, 2, 6, '🎧', 'Industry-leading noise cancellation, 30-hour battery life and crystal clear hands-free calling. Folds flat for travel.'],
        ['Sony Bravia 43" 4K Ultra HD Smart Google TV', 'Electronics', 'Sony', 'Pc', 68500, 2, 4, '📺', '43 inch 4K HDR display with Google TV, Dolby Audio and built-in Chromecast. Two-year official warranty.'],
        ['Walton 32" HD Ready LED TV', 'Electronics', 'Walton', 'Pc', 18990, 3, 8, '📺', '32 inch HD LED TV with USB movie playback and low power consumption. Made in Bangladesh.'],
        ['Xiaomi Smart Band 8', 'Electronics', 'Xiaomi', 'Pc', 4200, 4, 15, '⌚', '1.62" AMOLED display, 150+ workout modes, heart rate and SpO2 monitoring, up to 16 days of battery.'],
        ['Xiaomi Redmi Buds 5 True Wireless Earbuds', 'Electronics', 'Xiaomi', 'Pc', 2850, 5, 20, '🎧', 'Active noise cancellation up to 46dB, 40-hour total playback and fast charging.'],

        // Mobile & Accessories
        ['Samsung Galaxy A55 5G (8GB/128GB)', 'Mobile & Accessories', 'Samsung', 'Pc', 47999, 2, 6, '📱', '6.6" Super AMOLED 120Hz display, 50MP OIS camera, 5000mAh battery and IP67 water resistance.'],
        ['Xiaomi Redmi Note 13 (8GB/256GB)', 'Mobile & Accessories', 'Xiaomi', 'Pc', 26999, 3, 10, '📱', '6.67" AMOLED display, 108MP main camera and 33W fast charging. Official Bangladesh variant.'],
        ['Apple iPhone 15 (128GB)', 'Mobile & Accessories', 'Apple', 'Pc', 124999, 1, 3, '📱', 'Dynamic Island, 48MP main camera, USB-C and the A16 Bionic chip.'],
        ['Anker 20W USB-C Fast Charger', 'Mobile & Accessories', 'Anker', 'Pc', 1450, 5, 30, '🔌', 'Compact PowerPort III charger. Charges an iPhone to 50% in about 30 minutes.'],
        ['Xiaomi 20000mAh 22.5W Power Bank', 'Mobile & Accessories', 'Xiaomi', 'Pc', 2650, 5, 18, '🔋', 'Charge three devices at once with 22.5W fast charging and dual USB-A plus USB-C.'],

        // Computer & Accessories
        ['Dell Inspiron 15 3520 (Core i5, 8GB, 512GB SSD)', 'Computer & Accessories', 'Dell', 'Pc', 72500, 1, 4, '💻', '15.6" Full HD display, 12th Gen Intel Core i5, 512GB NVMe SSD and Windows 11.'],
        ['Lenovo IdeaPad Slim 3 (Ryzen 5, 16GB, 512GB SSD)', 'Computer & Accessories', 'Lenovo', 'Pc', 68900, 1, 4, '💻', 'Thin and light 15.6" laptop with AMD Ryzen 5, 16GB RAM and all-day battery life.'],
        ['Logitech M331 Silent Plus Wireless Mouse', 'Computer & Accessories', 'Logitech', 'Pc', 1350, 5, 25, '🖱️', '90% less click noise, 24-month battery life and a comfortable contoured shape.'],
        ['Logitech K380 Multi-Device Bluetooth Keyboard', 'Computer & Accessories', 'Logitech', 'Pc', 3950, 3, 10, '⌨️', 'Pair up to three devices and switch between them at the touch of a button.'],
        ['SanDisk Ultra 64GB USB 3.0 Pen Drive', 'Computer & Accessories', 'SanDisk', 'Pc', 750, 10, 40, '💾', 'Transfer speeds up to 130MB/s with password protection software included.'],

        // Home Appliances
        ['Walton 1.5 Ton Inverter Split AC', 'Home Appliances', 'Walton', 'Pc', 62500, 1, 0, '❄️', 'Energy saving inverter compressor, fast cooling and anti-bacterial filter. Free installation in Dhaka.'],
        ['Walton 252L Frost Free Refrigerator', 'Home Appliances', 'Walton', 'Pc', 46990, 1, 4, '🧊', 'Frost free with inverter technology, multi air flow and a 12-year compressor warranty.'],
        ['LG 7kg Front Load Washing Machine', 'Home Appliances', 'LG', 'Pc', 62500, 1, 3, '🧺', 'Direct drive motor with 6 Motion technology and steam wash for allergy care.'],
        ['Miyako 2.8L Electric Rice Cooker', 'Home Appliances', 'Miyako', 'Pc', 3250, 3, 12, '🍚', 'Cooks rice for 6-8 people with automatic keep-warm function and a non-stick inner pot.'],
        ['Philips HD1172 Dry Iron', 'Home Appliances', 'Philips', 'Pc', 2150, 3, 10, '👔', '1000W with non-stick American heritage soleplate and adjustable temperature control.'],

        // Kitchen & Dining
        ['Miyako 1.8L Electric Kettle', 'Kitchen & Dining', 'Miyako', 'Pc', 1450, 4, 15, '☕', 'Stainless steel body with auto shut-off and boil-dry protection.'],
        ['Kiam Non-Stick Frying Pan 26cm', 'Kitchen & Dining', 'Kiam', 'Pc', 1650, 4, 12, '🍳', 'Three-layer non-stick coating, induction compatible base and heat-resistant handle.'],
        ['RFL Opal Glass Dinner Set (32 pcs)', 'Kitchen & Dining', 'RFL', 'Box', 4800, 2, 6, '🍽️', 'Elegant tempered opal glass dinner set for six people. Microwave and dishwasher safe.'],
        ['RFL Stainless Steel Water Bottle 1L', 'Kitchen & Dining', 'RFL', 'Pc', 650, 8, 30, '🍶', 'Leak-proof, food-grade stainless steel bottle for school, office and travel.'],

        // Grocery
        ['ACI Pure Miniket Rice 5kg', 'Grocery', 'ACI', 'Pkt', 420, 15, 80, '🌾', 'Premium quality polished miniket rice, cleaned and sorted.'],
        ['Fresh Soybean Oil 5L', 'Grocery', 'Fresh', 'Pc', 850, 10, 50, '🌻', 'Vitamin A fortified refined soybean oil. Cholesterol free.'],
        ['Pran Mustard Oil 1L', 'Grocery', 'Pran', 'Pc', 280, 10, 40, '🌼', 'Pure cold-pressed mustard oil with a rich aroma, perfect for bhorta and pickles.'],
        ['Teer Atta 2kg', 'Grocery', 'Teer', 'Pkt', 140, 15, 60, '🍞', 'Whole wheat atta for soft, fluffy rutis every time.'],
        ['Fresh Refined Sugar 1kg', 'Grocery', 'Fresh', 'kg', 135, 20, 80, '🍬', 'Fine grain, hygienically packed refined sugar.'],
        ['Ispahani Mirzapore Best Leaf Tea 400g', 'Grocery', 'Ispahani', 'Pkt', 245, 15, 50, '🍵', 'Strong, rich and full of flavour. Bangladesh\'s favourite cup of tea.'],

        // Beverages
        ['Coca-Cola 1.25L', 'Beverages', 'Coca-Cola', 'Pc', 90, 24, 120, '🥤', 'The original refreshing taste. Best served chilled.'],
        ['Pran Mango Fruit Drink 1L', 'Beverages', 'Pran', 'Pc', 120, 20, 80, '🧃', 'Made from real mangoes. A family favourite.'],
        ['Nescafé Classic Instant Coffee 200g', 'Beverages', 'Nestlé', 'Pc', 650, 6, 25, '☕', 'Rich, full-bodied instant coffee made from carefully selected beans.'],
        ['Mum Drinking Water 2L', 'Beverages', 'Mum', 'Pc', 40, 30, 150, '💧', 'Safe, purified and mineral-balanced drinking water.'],

        // Personal Care
        ['Dove Beauty Bar 135g', 'Personal Care', 'Unilever', 'Pc', 140, 15, 60, '🧼', 'With 1/4 moisturising cream for soft, smooth and glowing skin.'],
        ['Sunsilk Thick & Long Shampoo 375ml', 'Personal Care', 'Unilever', 'Pc', 520, 8, 30, '🧴', 'Biotin and keratin formula for thicker, longer looking hair.'],
        ['Colgate MaxFresh Toothpaste 150g', 'Personal Care', 'Colgate', 'Pc', 195, 15, 50, '🦷', 'Cooling crystals for a long-lasting fresh breath feeling.'],
        ['Nivea Men Oil Control Face Wash 100ml', 'Personal Care', 'Nivea', 'Pc', 420, 6, 2, '🧴', 'Removes excess oil and fights dullness for a fresh, clean look.'],
        ['Dettol Original Liquid Handwash 200ml', 'Personal Care', 'Dettol', 'Pc', 125, 15, 0, '🧴', 'Protects from 99.9% of germs. Trusted protection for the whole family.'],
    ];

    private const NEW_BRANDS = [
        'Samsung', 'Anker', 'Logitech', 'SanDisk', 'Miyako', 'Philips', 'Kiam', 'RFL', 'ACI', 'Fresh',
        'Pran', 'Teer', 'Ispahani', 'Coca-Cola', 'Nestlé', 'Mum', 'Unilever', 'Colgate', 'Nivea', 'Dettol',
    ];

    private const NEW_SUPPLIERS = [
        ['Dhaka Electronics Distribution Ltd.', '01711-234567', 'sales@dhakaelectronics.example', 'Elephant Road, Dhaka 1205'],
        ['Bengal Consumer Goods Supply', '01819-345678', 'orders@bengalconsumer.example', 'Kawran Bazar, Dhaka 1215'],
        ['Smart Gadget Importers', '01912-456789', 'info@smartgadget.example', 'Bashundhara City, Panthapath, Dhaka'],
    ];

    private const CUSTOMERS = [
        ['Rahim Uddin', '01711-100201'],
        ['Karim Hossain', '01812-200302'],
        ['Nusrat Jahan', '01913-300403'],
        ['Farhana Akter', '01714-400504'],
        ['Tanvir Ahmed', '01815-500605'],
        ['Sadia Islam', '01916-600706'],
        ['Mahmudul Hasan', '01717-700807'],
        ['Shirin Sultana', '01818-800908'],
        ['Arif Chowdhury', '01919-900109'],
        ['Jannatul Ferdous', '01720-010110'],
    ];

    public function run(): void
    {
        if (Product::where('sku', 'NX-1001')->exists()) {
            $this->command?->warn('Demo data already exists (NX-1001 found). Nothing was added.');

            return;
        }

        mt_srand(2026);

        DB::transaction(function () {

            $brands = $this->seedBrands();
            $suppliers = $this->seedSuppliers();
            $products = $this->seedProducts($brands);
            $this->seedPurchases($products, $suppliers);
            $customers = $this->seedCustomers();
            $this->seedSales($products, $customers);
            $this->seedExpenses();
        });

        $this->command?->info('Demo data added.');
    }


    private function seedBrands(): array
    {
        foreach (self::NEW_BRANDS as $name) {
            Brand::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'status' => true]
            );
        }

        return Brand::pluck('id', 'name')->all();
    }


    private function seedSuppliers(): array
    {
        foreach (self::NEW_SUPPLIERS as [$name, $phone, $email, $address]) {
            Supplier::firstOrCreate(
                ['name' => $name],
                ['phone' => $phone, 'email' => $email, 'address' => $address, 'status' => true]
            );
        }

        return Supplier::whereIn('name', array_column(self::NEW_SUPPLIERS, 0))->pluck('id')->all();
    }


    private function seedProducts(array $brands): array
    {
        $categories = Category::pluck('id', 'name')->all();
        $units = Unit::pluck('id', 'short_name')->all();

        $imageDir = public_path('uploads/products/demo');

        if (! is_dir($imageDir)) {
            mkdir($imageDir, 0755, true);
        }

        $products = [];

        foreach (self::PRODUCTS as $index => [$name, $category, $brand, $unit, $price, $alert, $openingQty, $emoji, $description]) {

            $slug = Str::slug($name);
            $sku = 'NX-' . (1001 + $index);

            file_put_contents("$imageDir/$slug.svg", $this->productSvg($name, $brand, $category, $emoji));

            // Shops usually buy at roughly 78-88% of the selling price
            $purchasePrice = round($price * (mt_rand(78, 88) / 100), -1 * ($price >= 1000 ? 1 : 0));

            $product = Product::create([
                'category_id' => $categories[$category],
                'brand_id' => $brands[$brand] ?? null,
                'unit_id' => $units[$unit],
                'name' => $name,
                'slug' => $slug,
                'sku' => $sku,
                'barcode' => '8941' . str_pad((string) (100000000 + $index * 7919), 9, '0', STR_PAD_LEFT),
                'image' => "uploads/products/demo/$slug.svg",
                'purchase_price' => $purchasePrice,
                'selling_price' => $price,
                'stock' => 0,
                'alert_quantity' => $alert,
                'description' => $description,
                'status' => true,
            ]);

            // Older products were added a while ago; the last few are "new arrivals"
            $addedAt = now()->subDays($index >= count(self::PRODUCTS) - 8 ? mt_rand(1, 10) : mt_rand(36, 60));
            $this->stamp($product, $addedAt);

            $products[] = ['model' => $product, 'opening' => $openingQty];
        }

        return $products;
    }


    /**
     * Opening stock arrives in a few received purchases dated before the first sale,
     * plus one pending order that hasn't arrived yet (no stock change).
     */
    private function seedPurchases(array $products, array $suppliers): void
    {
        $toBuy = array_values(array_filter($products, fn ($row) => $row['opening'] > 0));
        $batches = array_chunk($toBuy, (int) ceil(count($toBuy) / 4));

        foreach ($batches as $i => $batch) {

            $date = now()->subDays(34 - $i * 2);
            $subtotal = 0;
            $items = [];

            foreach ($batch as $row) {
                $line = $row['opening'] * $row['model']->purchase_price;
                $subtotal += $line;
                $items[] = [$row['model'], $row['opening'], $line];
            }

            $discount = $i === 1 ? round($subtotal * 0.02, -1) : 0;
            $grandTotal = $subtotal - $discount;

            // Mix of fully paid, partly paid and unpaid supplier bills
            $paid = match ($i) {
                0, 2 => $grandTotal,
                1 => round($grandTotal * 0.6, -2),
                default => 0,
            };

            $purchase = Purchase::create([
                'purchase_no' => $this->documentNo(Purchase::class, 'purchase_no', 'PUR', $date),
                'supplier_id' => $suppliers[$i % count($suppliers)],
                'purchase_date' => $date->toDateString(),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'grand_total' => $grandTotal,
                'paid_amount' => $paid,
                'due_amount' => $grandTotal - $paid,
                'payment_status' => $paid >= $grandTotal ? 'paid' : ($paid > 0 ? 'partial' : 'due'),
                'status' => 'received',
                'note' => 'Opening stock',
            ]);

            foreach ($items as [$product, $qty, $line]) {
                $purchase->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'purchase_price' => $product->purchase_price,
                    'subtotal' => $line,
                ]);

                $product->increment('stock', $qty);
            }

            $this->stamp($purchase, $date->copy()->setTime(11, 0));
        }

        // A recent order still on its way: the out-of-stock items
        $pendingItems = array_values(array_filter($products, fn ($row) => $row['opening'] === 0));

        if ($pendingItems) {

            $subtotal = 0;

            foreach ($pendingItems as $row) {
                $subtotal += 3 * $row['model']->purchase_price;
            }

            $purchase = Purchase::create([
                'purchase_no' => $this->documentNo(Purchase::class, 'purchase_no', 'PUR', now()->subDays(1)),
                'supplier_id' => $suppliers[0],
                'purchase_date' => now()->subDays(1)->toDateString(),
                'subtotal' => $subtotal,
                'discount' => 0,
                'grand_total' => $subtotal,
                'paid_amount' => 0,
                'due_amount' => $subtotal,
                'payment_status' => 'due',
                'status' => 'pending',
                'note' => 'Ordered, waiting for delivery',
            ]);

            foreach ($pendingItems as $row) {
                $purchase->items()->create([
                    'product_id' => $row['model']->id,
                    'quantity' => 3,
                    'purchase_price' => $row['model']->purchase_price,
                    'subtotal' => 3 * $row['model']->purchase_price,
                ]);
            }
        }
    }


    private function seedCustomers(): array
    {
        $ids = [];

        foreach (self::CUSTOMERS as $index => [$name, $phone]) {

            // One customer can log in, to try the customer portal
            $canLogin = $index === 2;

            $customer = User::firstOrCreate(
                ['phone' => $phone, 'role' => 'customer'],
                [
                    'name' => $name,
                    'email' => $canLogin ? 'nusrat@example.com' : null,
                    'password' => $canLogin ? Hash::make('password') : null,
                    'status' => 1,
                ]
            );

            $this->stamp($customer, now()->subDays(mt_rand(31, 90)));

            $ids[] = $customer->id;
        }

        return $ids;
    }


    /**
     * About 60 POS sales over the last 30 days, never selling more than is in stock.
     */
    private function seedSales(array $products, array $customers): void
    {
        $sellers = User::whereIn('role', ['admin', 'manager', 'cashier'])->pluck('id', 'role');
        $sellerPool = array_values(array_filter([
            $sellers['cashier'] ?? null, $sellers['cashier'] ?? null, $sellers['cashier'] ?? null,
            $sellers['manager'] ?? null, $sellers['admin'] ?? null,
        ]));

        // Cheap everyday items sell much more often than laptops and TVs
        $catalog = [];

        foreach ($products as $row) {
            $price = (float) $row['model']->selling_price;
            $weight = $price < 1000 ? 8 : ($price < 5000 ? 4 : ($price < 30000 ? 2 : 1));
            $catalog = array_merge($catalog, array_fill(0, $weight, $row['model']));
        }

        $stock = [];

        foreach ($products as $row) {
            $stock[$row['model']->id] = (float) $row['model']->stock;
        }

        for ($i = 0; $i < 62; $i++) {

            // More sales on recent days
            $daysAgo = (int) floor(pow(mt_rand(0, 1000) / 1000, 1.6) * 29);
            $soldAt = now()->subDays($daysAgo)->setTime(mt_rand(10, 20), mt_rand(0, 59), mt_rand(0, 59));

            if ($soldAt->isFuture()) {
                $soldAt = now()->subMinutes(mt_rand(5, 120));
            }

            $lines = [];
            $lineCount = mt_rand(1, 4);

            for ($l = 0; $l < $lineCount; $l++) {

                $product = $catalog[array_rand($catalog)];

                if (isset($lines[$product->id])) {
                    continue;
                }

                $price = (float) $product->selling_price;
                $qty = $price < 500 ? mt_rand(1, 5) : ($price < 5000 ? mt_rand(1, 2) : 1);
                $qty = min($qty, (int) $stock[$product->id]);

                if ($qty < 1) {
                    continue;
                }

                $stock[$product->id] -= $qty;
                $lines[$product->id] = [$product, $qty];
            }

            if (! $lines) {
                continue;
            }

            $subtotal = array_sum(array_map(fn ($line) => $line[0]->selling_price * $line[1], $lines));

            // Occasional round-off discount on bigger bills
            $discount = $subtotal >= 5000 && mt_rand(1, 3) === 1 ? min(round($subtotal * 0.03, -1), 2000) : ($subtotal > 300 && mt_rand(1, 4) === 1 ? fmod($subtotal, 10) : 0);
            $grandTotal = $subtotal - $discount;

            $method = $grandTotal > 20000 ? ['card', 'mobile_banking', 'cash'][mt_rand(0, 2)] : (mt_rand(1, 10) <= 7 ? 'cash' : (mt_rand(0, 1) ? 'mobile_banking' : 'card'));
            $paid = $method === 'cash' ? ceil($grandTotal / 100) * 100 : $grandTotal;

            $sale = Sale::create([
                'invoice_no' => 'INV-' . $soldAt->format('Ymd') . '-' . strtoupper(Str::random(6)),
                'customer_id' => mt_rand(1, 10) <= 6 ? $customers[array_rand($customers)] : null,
                'user_id' => $sellerPool[array_rand($sellerPool)],
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => 0,
                'grand_total' => $grandTotal,
                'paid_amount' => $paid,
                'change_amount' => $paid - $grandTotal,
                'payment_method' => $method,
                'payment_status' => 'paid',
            ]);

            foreach ($lines as [$product, $qty]) {
                $sale->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'price' => $product->selling_price,
                    'cost_price' => $product->purchase_price,
                    'total' => $product->selling_price * $qty,
                ]);
            }

            $this->stamp($sale, $soldAt);
            DB::table('sale_items')->where('sale_id', $sale->id)->update(['created_at' => $soldAt, 'updated_at' => $soldAt]);
        }

        foreach ($stock as $productId => $remaining) {
            Product::whereKey($productId)->update(['stock' => $remaining]);
        }
    }


    private function seedExpenses(): void
    {
        $categories = [];

        foreach (['Shop Rent', 'Staff Salary', 'Electricity Bill', 'Internet & Phone', 'Transport', 'Shop Maintenance'] as $name) {
            $categories[$name] = ExpenseCategory::firstOrCreate(['name' => $name], ['status' => true])->id;
        }

        $admin = User::where('role', 'admin')->value('id');
        $entries = [];

        foreach ([now()->subMonthNoOverflow()->startOfMonth(), now()->startOfMonth()] as $month) {
            $entries[] = ['Shop Rent', 35000, $month->copy()->addDays(2), 'bank_transfer', 'Monthly shop rent'];
            $entries[] = ['Staff Salary', 48000, $month->copy()->addDays(4), 'bank_transfer', 'Salary for 3 staff'];
            $entries[] = ['Electricity Bill', mt_rand(6500, 8900), $month->copy()->addDays(8), 'mobile_banking', 'DESCO prepaid recharge'];
            $entries[] = ['Internet & Phone', 1500, $month->copy()->addDays(9), 'mobile_banking', 'Broadband monthly bill'];
            $entries[] = ['Transport', mt_rand(1800, 3500), $month->copy()->addDays(6), 'cash', 'Goods delivery from supplier'];
        }

        $entries[] = ['Shop Maintenance', 4200, now()->subDays(12), 'cash', 'AC servicing and light replacement'];
        $entries[] = ['Transport', 950, now()->subDays(3), 'cash', 'Rickshaw van for customer delivery'];

        foreach ($entries as [$category, $amount, $date, $method, $note]) {

            if ($date->isFuture()) {
                continue;
            }

            $expense = Expense::create([
                'expense_no' => $this->documentNo(Expense::class, 'expense_no', 'EXP', $date),
                'expense_category_id' => $categories[$category],
                'user_id' => $admin,
                'amount' => $amount,
                'expense_date' => $date->toDateString(),
                'payment_method' => $method,
                'note' => $note,
            ]);

            $this->stamp($expense, $date->copy()->setTime(12, 0));
        }
    }


    /**
     * Document number for a past date (PUR-20260906-0001), continuing any numbers already used that day.
     * The normal generator always uses today's date, so past-date numbers never collide with new ones.
     */
    private function documentNo(string $model, string $column, string $prefix, Carbon $date): string
    {
        $key = $prefix . '-' . $date->format('Ymd') . '-';

        $last = $model::where($column, 'like', $key . '%')->max($column);
        $next = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $key . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }


    /**
     * Back-date a record's created_at/updated_at without touching anything else.
     */
    private function stamp($model, Carbon $at): void
    {
        $model->newQuery()->whereKey($model->getKey())->update([
            'created_at' => $at,
            'updated_at' => $at,
        ]);
    }


    /**
     * Clean square product illustration: tinted background, emoji, name and brand.
     */
    private function productSvg(string $name, string $brand, string $category, string $emoji): string
    {
        [$tone, $soft] = self::TONES[$category] ?? ['#6366f1', '#eef2ff'];

        $title = e(Str::limit(preg_replace('/\s*\(.*?\)/', '', $name), 34, '…'));
        $brand = e(mb_strtoupper($brand));

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="800" height="800" viewBox="0 0 800 800">
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="#ffffff"/>
      <stop offset="1" stop-color="{$soft}"/>
    </linearGradient>
    <radialGradient id="glow" cx="0.5" cy="0.42" r="0.45">
      <stop offset="0" stop-color="{$tone}" stop-opacity="0.22"/>
      <stop offset="1" stop-color="{$tone}" stop-opacity="0"/>
    </radialGradient>
  </defs>
  <rect width="800" height="800" fill="url(#bg)"/>
  <circle cx="400" cy="335" r="300" fill="url(#glow)"/>
  <circle cx="400" cy="335" r="175" fill="#ffffff" stroke="{$tone}" stroke-opacity="0.15" stroke-width="2"/>
  <text x="400" y="395" font-size="170" text-anchor="middle" font-family="Segoe UI Emoji, Apple Color Emoji, Noto Color Emoji, sans-serif">{$emoji}</text>
  <text x="400" y="610" font-size="22" font-weight="800" letter-spacing="6" text-anchor="middle" fill="{$tone}" font-family="Inter, Segoe UI, Arial, sans-serif">{$brand}</text>
  <text x="400" y="660" font-size="34" font-weight="700" text-anchor="middle" fill="#0f172a" font-family="Inter, Segoe UI, Arial, sans-serif">{$title}</text>
</svg>
SVG;
    }
}
