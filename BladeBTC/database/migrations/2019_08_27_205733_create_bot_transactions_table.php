<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_transactions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->bigInteger('telegram_id')->nullable()->default(null);
            $table->decimal('amount', 15, 8)->nullable()->default(null);
            $table->string('withdraw_address', 200)->nullable()->default(null);
            $table->text('message')->nullable()->default(null);
            $table->text('tx_hash')->nullable()->default(null);
            $table->text('tx_id')->nullable()->default(null);
            $table->smallInteger('status')->nullable()->default(null);
            $table->string('type', 50)->nullable()->default(null);
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
        Schema::dropIfExists('bot_transactions');
    }
}
