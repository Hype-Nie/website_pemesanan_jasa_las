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
        Schema::create('custom_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('catalog_product_id')->nullable()->constrained('catalog_products')->nullOnDelete();
            $table->string('product_name');
            $table->text('description');
            $table->string('dimensions'); // e.g. '3m x 1.5m'
            $table->string('material_preference')->nullable();
            $table->integer('quantity')->default(1);
            $table->string('reference_design_path')->nullable();
            $table->decimal('total_price', 12, 2)->nullable(); // set by admin
            $table->string('status')->default('pending'); // pending, confirmed, in_production, completed, cancelled
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_orders');
    }
};
