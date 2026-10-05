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
        Schema::create('purchases', function (Blueprint $table) {

            $table->id();

            // Supplier
            $table->foreignId('supplier_id')
                ->constrained('suppliers')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Purchase Information
            $table->string('purchase_no')->unique();

            $table->date('purchase_date');

            // Amounts
            $table->decimal('subtotal', 12, 2)->default(0);

            $table->decimal('discount', 12, 2)->default(0);

            $table->decimal('grand_total', 12, 2)->default(0);

            $table->decimal('paid_amount', 12, 2)->default(0);

            $table->decimal('due_amount', 12, 2)->default(0);

            // Payment Status
            $table->enum('payment_status', [
                'paid',
                'partial',
                'due'
            ])->default('due');

            // Purchase Status
            $table->enum('status', [
                'received',
                'pending',
                'cancelled'
            ])->default('received');

            // Additional Information
            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};