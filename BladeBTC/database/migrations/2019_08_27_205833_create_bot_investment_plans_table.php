<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotInvestmentPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_investment_plans', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->decimal('minimum_invest', 15, 8)->nullable()->default(null);
            $table->decimal('minimum_reinvest', 15, 8)->nullable()->default(null);
            $table->decimal('minimum_payout', 15, 8)->nullable()->default(null);
            $table->decimal('minimum_invest_usd', 15, 2)->nullable()->default(null);
            $table->decimal('minimum_reinvest_usd', 15, 2)->nullable()->default(null);
            $table->decimal('minimum_payout_usd', 15, 2)->nullable()->default(null);
            $table->decimal('referral_bonus_usd', 15, 2)->nullable()->default(null);
            $table->integer('base_rate')->nullable()->default(null);
            $table->integer('contract_day')->nullable()->default(null);
            $table->integer('commission_rate')->nullable()->default(null);
            $table->integer('timer_time_hour')->nullable()->default(null);
            $table->integer('required_confirmations')->nullable()->default(null);
            $table->integer('interest_on_reinvest')->nullable()->default(null);
            $table->integer('withdraw_fee')->nullable()->default(null);
            $table->boolean('active')->default(false);
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
        Schema::dropIfExists('bot_investment_plans');
    }
}
