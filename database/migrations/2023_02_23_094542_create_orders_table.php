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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained();
            $table->string('name');
            $table->string('phone');
            $table->enum('payment_method', ['money', 'card']);
            $table->enum('delivery_method', ['local', 'shipping']);
            $table->string('zip_code')->nullable();
            $table->string('street')->nullable();
            $table->integer('number')->nullable();
            $table->string('complement')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('city')->nullable();
            $table->char('state', 2)->nullable();
            $table->text('additional_information')->nullable();
            $table->float('freight_total')->nullable();
            $table->float('total')->comment('Produtos + Frete + Adicionais');
            $table->enum('status', ['opened', 'preparation', 'transport', 'finished', 'canceled'])
                    ->default('opened')
                    ->comment('Aberto, Em preparação, Em transporte, Concluído, Cancelado');
            $table->timestamps();
            $table->comment('Tabela de pedidos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
