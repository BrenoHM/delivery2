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
        Schema::create('addition_product', function (Blueprint $table) {
            //$table->id();
            $table->foreignId('addition_id')->constrained();
            $table->foreignId('product_id')->constrained();
            //$table->timestamps();
            $table->comment('Relação de Produtos com acréscimos.');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addition_product');
    }
};
