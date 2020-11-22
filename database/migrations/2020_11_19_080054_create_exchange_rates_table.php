<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangeRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->integer('source_currency_id')->nullable(false);
            $table->integer('target_currency_id')->nullable(false);
            $table->float('exchange_rate')->nullable(false)->default(0);
            $table->timestamps();

            $table->foreign('source_currency_id', )->references('id')->on('currencies');
            $table->foreign('target_currency_id', )->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exchange_rates');
    }
}
