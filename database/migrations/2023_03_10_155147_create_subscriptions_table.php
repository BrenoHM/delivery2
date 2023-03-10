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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('subscription_id')->nullable();
            $table->integer('plan_id')->nullable();
            $table->integer('trial_days')->nullable();
            $table->integer('custom_id')->nullable();
            $table->date('first_execution')->nullable();
            $table->float('total')->nullable();
            $table->string('payment')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->comment('Tabela de assinantes na gerencianet');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
};
