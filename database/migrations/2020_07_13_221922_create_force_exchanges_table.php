<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForceExchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('force_exchanges', function (Blueprint $table) {
            $table->id();
            $table->unsignedDecimal('amount', 19, 4);
            $table->unsignedDecimal('rate', 19, 4);
            $table->foreignId('incomeId')
                ->constrained('incomes')
                ->onDelete('cascade')
                ->unique();
            $table->timestamps();
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
        Schema::dropIfExists('force_exchanges');
    }
}
