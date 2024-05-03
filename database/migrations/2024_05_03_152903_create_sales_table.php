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
            $table->enum("type", ["self-ransom", "sale"])->default("sale");

            $table->unsignedBigInteger("wb_sale_id")->unique();

            $table->decimal("wb_price");
            $table->decimal("warehouse_tax");
            $table->decimal("logistic_tax")->nullable();
            $table->json("product");

            $table->string("note")->nullable();
            $table->date("sell_date");
            $table->timestamps();
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
