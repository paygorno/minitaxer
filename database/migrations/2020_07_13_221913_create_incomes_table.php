<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->unsignedDecimal('amount', 19,4);
            $table->unsignedDecimal('pending', 19, 4)->nullable();
            $table->foreignId('userId')
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('exchangeDataId')
                ->constrained('exchange_rates')
                ->onDelete('cascade');
            $table->timestamps();
            $table->unique(['userId', 'exchangeDataId']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incomes');
    }
}
