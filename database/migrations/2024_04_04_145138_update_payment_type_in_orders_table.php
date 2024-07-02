<?php

use App\Enums\PaymentType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_type', [
                PaymentType::CASH->value,
                PaymentType::STRIPE->value,
                PaymentType::CARD->value])->change();
        });
    }

    /**
     * Reverse the migrations.delivery_addresses
     */
    public function down(): void
    {
        if (Schema::hasColumn('orders', 'payment_type'))
            Schema::table('orders', function (Blueprint $table) {
                $table->enum('payment_type', [
                    PaymentType::CASH->value,
                    PaymentType::STRIPE->value])->change();
            });
    }
};
