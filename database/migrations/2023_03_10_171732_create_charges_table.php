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
        Schema::create('charges', function (Blueprint $table) {
            $table->id();
            $table->integer('charge_id')->nullable();
            $table->integer('custom_id')->nullable();
            $table->integer('subscription_id')->nullable();
            $table->tinyInteger('parcel')->nullable();
            $table->string('status')->nullable();
            $table->float('total')->nullable();
            $table->timestamps();
            $table->comment('Tabela de cobranças da gerencianet');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('charges');
    }
};
