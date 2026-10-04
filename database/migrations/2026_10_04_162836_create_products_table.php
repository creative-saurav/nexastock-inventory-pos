<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('brand_id')
                ->nullable()
                ->constrained('brands')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('unit_id')
                ->constrained('units')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Basic Information
            $table->string('name');
            $table->string('slug')->unique();

            $table->string('sku')->unique();
            $table->string('barcode')->nullable()->unique();

            // Product Image
            $table->string('image')->nullable();

            // Pricing
            $table->decimal('purchase_price', 12, 2)->default(0);
            $table->decimal('selling_price', 12, 2)->default(0);

            // Inventory
            $table->decimal('stock', 12, 2)->default(0);
            $table->decimal('alert_quantity', 12, 2)->default(0);

            // Other Information
            $table->text('description')->nullable();

            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
