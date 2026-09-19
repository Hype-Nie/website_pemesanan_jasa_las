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
        Schema::table('custom_orders', function (Blueprint $table) {
            $table->decimal('dp_amount', 12, 2)->nullable()->after('total_price');
            $table->unsignedTinyInteger('progress_percentage')->default(0)->after('status');
            $table->text('progress_notes')->nullable()->after('progress_percentage');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_type', 30)->default('down_payment')->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('payment_type');
        });

        Schema::table('custom_orders', function (Blueprint $table) {
            $table->dropColumn(['dp_amount', 'progress_percentage', 'progress_notes']);
        });
    }
};
