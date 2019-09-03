<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->text('telegram_username')->nullable()->default(null);
            $table->text('telegram_firstname')->nullable()->default(null);
            $table->text('telegram_lastname')->nullable()->default(null);
            $table->bigInteger('telegram_id')->nullable()->default(null)->unique();
            $table->decimal('balance', 15, 8)->default(0.00000000);
            $table->decimal('invested', 15, 8)->default(0.00000000);
            $table->decimal('reinvested', 15, 8)->default(0.00000000);
            $table->decimal('profit', 15, 8)->default(0.00000000);
            $table->decimal('commission', 15, 8)->default(0.00000000);
            $table->decimal('payout', 15, 8)->default(0.00000000);
            $table->decimal('last_confirmed', 15, 8)->default(0.00000000);
            $table->string('investment_address')->nullable()->default(null)->unique();
            $table->decimal('current_minimum_btc', 15, 8)->nullable()->default(null);
            $table->string('wallet_address')->nullable()->default(null)->unique();
            $table->string('referral_link')->nullable()->default(null)->unique();
            $table->boolean('isNew')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bot_users');
    }
}
