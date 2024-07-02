<?php

use App\Enums\AddressType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('address_type', [
                AddressType::BILLING->value,
                AddressType::BILLING_AND_DELIVERY->value,
                AddressType::DELIVERY->value,
                AddressType::ONE_TIME->value,
            ]);
            $table->foreignId('country_id')->constrained();
            $table->string('zip_code', 10);
            $table->string('city', 30);
            $table->string('line1', 30);
            $table->string('line2', 30);
            $table->string('building_number', 10);
            $table->string('additional_information', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
