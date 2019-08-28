<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBotSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_settings', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('app_id',200)->nullable()->default(NULL);
            $table->string('app_name',100)->nullable()->default(NULL);
            $table->string('support_chat_id',100)->nullable()->default(NULL);
            $table->string('wallet_id',200)->nullable()->default(NULL);
            $table->string('wallet_password',200)->nullable()->default(NULL);
            $table->string('wallet_second_password',200)->nullable()->default(NULL);
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
        Schema::dropIfExists('bot_settings');
    }
}
