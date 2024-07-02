<?php

use App\Enums\DeliveryType;
use App\Enums\OrderStatus;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('delivery_type', [
                DeliveryType::HOME->value,
                DeliveryType::PICK_UP->value,
            ]);
            $table->enum('payment_type', [
                PaymentType::CASH->value,
                PaymentType::STRIPE->value]);
            $table->decimal('payment_total', 10, 2);
            $table->date('delivery_date');
            $table->enum('order_status', [
                OrderStatus::PENDING->value,
                OrderStatus::CONFIRMED->value,
                OrderStatus::REJECTED->value,
                OrderStatus::DELETED->value,
                OrderStatus::DELIVERED->value,
            ])->default(OrderStatus::PENDING->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
