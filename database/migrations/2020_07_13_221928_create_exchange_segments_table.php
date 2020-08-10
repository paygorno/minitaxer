<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangeSegmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange_segments', function (Blueprint $table) {
            $table->id();
            $table->unsignedDecimal('amount_exchanged', 19, 4);
            $table->foreignId('incomeId')
                ->constrained('incomes')
                ->onDelete('cascade');
            $table->foreignId('exchangeId')
                ->constrained('exchanges')
                ->onDelete('cascade');
            $table->timestamps();

            $table->unique(['incomeId', 'exchangeId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exchange_segments');
    }
}
