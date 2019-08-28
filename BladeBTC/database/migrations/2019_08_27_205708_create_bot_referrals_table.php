<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBotReferralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_referrals', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->bigInteger('telegram_id_referent');
            $table->bigInteger('telegram_id_referred')->unique();
            $table->timestamps();
            $table->unique(['telegram_id_referent', 'telegram_id_referred']);
            $table->foreign('telegram_id_referent')->references('telegram_id')->on('bot_users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('telegram_id_referred')->references('telegram_id')->on('bot_users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bot_referrals');
    }
}
