<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice', 100)->unique();
            $table->foreignId('cashier_id')->constrained("users", "id");
            $table->foreignId('customer_id')->constrained("customers", "id");
            $table->decimal('customer_pay_money', 20);
            $table->decimal('customer_change_money', 20);
            $table->decimal('discount', 20)->default(0);
            $table->decimal('sum_tax', 20)->default(0);
            $table->decimal('sum_profit', 20);
            $table->decimal('sum_gross_price', 20);
            $table->decimal('sum_net_price', 20);
            $table->timestampTz("created_at");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
};
