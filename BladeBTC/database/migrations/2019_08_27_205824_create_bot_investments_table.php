<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBotInvestmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_investments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->bigInteger('telegram_id');
            $table->decimal('amount', 15, 8);
            $table->dateTime('contract_start_date')->useCurrent();
            $table->dateTime('contract_end_date');
            $table->timestamps();
            $table->foreign('telegram_id')->references('telegram_id')->on('bot_users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bot_investments');
    }
}
