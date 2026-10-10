<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Store the product's purchase price at the time of sale,
     * so profit reports don't change when purchase prices change later.
     */
    public function up(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->decimal('cost_price', 12, 2)->default(0)->after('price');
        });

        // Backfill existing rows with the product's current purchase price
        DB::table('sale_items')
            ->join('products', 'products.id', '=', 'sale_items.product_id')
            ->update(['sale_items.cost_price' => DB::raw('products.purchase_price')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropColumn('cost_price');
        });
    }
};
