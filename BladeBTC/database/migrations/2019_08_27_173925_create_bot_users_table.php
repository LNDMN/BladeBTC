<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->text('telegram_username')->nullable()->default(NULL);
            $table->text('telegram_firstname')->nullable()->default(NULL);
            $table->text('telegram_lastname')->nullable()->default(NULL);
            $table->bigInteger('telegram_id')->nullable()->default(NULL)->unique();
            $table->decimal('balance', 15,8)->default(0.00000000);
            $table->decimal('invested', 15,8)->default(0.00000000);
            $table->decimal('reinvested', 15,8)->default(0.00000000);
            $table->decimal('profit', 15,8)->default(0.00000000);
            $table->decimal('commission', 15,8)->default(0.00000000);
            $table->decimal('payout', 15,8)->default(0.00000000);
            $table->decimal('last_confirmed', 15,8)->default(0.00000000);
            $table->string('investment_address', 250)->nullable()->default(NULL)->unique();
            $table->string('wallet_address',250)->nullable()->default(NULL)->unique();
            $table->string('referral_link',250)->nullable()->default(NULL)->unique();
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
