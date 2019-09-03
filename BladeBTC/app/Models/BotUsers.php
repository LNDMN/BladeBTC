<?php

namespace App\Models;

use BotMan\BotMan\Interfaces\UserInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BotUsers extends Model
{

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bot_users';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Get user value or create user if not existing
     *
     * @param UserInterface $user
     *
     * @return BotUsers
     */
    public static function getCreate(UserInterface $user)
    {

        /**
         * Check if user is in our database
         */
        $user_exist = self::where('telegram_id', $user->getId())->get();


        /**
         * User not existing
         */
        if (count($user_exist) == 0) {

            /**
             * Insert user in database
             */
            $new_user = new BotUsers();
            $new_user->telegram_id = $user->getId();
            $new_user->telegram_firstname = $user->getFirstName();
            $new_user->telegram_lastname = $user->getLastName();
            $new_user->telegram_username = $user->getUsername();
            $new_user->save();
            $new_user->refresh();

            /**
             * Add value indicating if user is new
             */
            $new_user->isNew = 1;

            return $new_user;
        }
        else {

            $bot_user = self::where('telegram_id', $user->getId())->first();

            /**
             * Add value indicating if user is new
             */
            $bot_user->isNew = 0;
            $bot_user->save();
            $bot_user->refresh();

            return $bot_user;
        }
    }

    /**
     * Reinvest user balance
     */
    public function Reinvest()
    {

        /**
         * Recover balance
         */
        $balance = $this->balance;


        /**
         * Create investment
         */
        $contract_day = BotInvestmentPlans::where('active', 1)->first()->contract_day;
        $investment = new BotInvestments();
        $investment->telegram_id = $this->telegram_id;
        $investment->amount = $balance;
        $investment->contract_start_date = Carbon::now();
        $investment->contract_end_date = Carbon::now()->addDays($contract_day);
        $investment->save();


        /**
         * Put balance to 0
         */
        $this->balance = 0;
        $this->reinvested = $this->reinvested + $balance;
        $this->save();
        $this->refresh();


        /**
         * Log transaction
         */
        $transaction = new BotTransactions();
        $transaction->telegram_id = $this->telegram_id;
        $transaction->amount = $balance;
        $transaction->status = 1;
        $transaction->type = "reinvest";
        $transaction->save();


        /**
         * Give bonus to referent
         */
        $interest_on_reinvest = BotInvestmentPlans::where('active', 1)->first()->interest_on_reinvest;
        if ($interest_on_reinvest == 1) {

            /**
             * Get referent ID
             */
            $referent_id = $this->getReferentId($this->telegram_id);
            if (!is_null($referent_id)) {

                /**
                 * Calculate commission
                 */
                $rate = BotInvestmentPlans::where('active', 1)->first()->commission_rate;
                $commission = $balance * $rate / 100;
                BotUsers::giveCommission($referent_id, $commission);
            }
        }
    }

    /**
     * Referent ID
     *
     * @param $telegram_id
     *
     * @return int|null
     */
    public function getReferentId($telegram_id)
    {

        $telegram_id_referent = BotReferrals::where('telegram_id_referred', $telegram_id)->first();
        if (!is_null($telegram_id_referent)) {
            return $telegram_id_referent->telegram_id_referent;
        }
        else {
            return null;
        }
    }

    /**
     * Give commission to referent
     *
     * @param $telegram_referent_id - Id of the referent
     *
     * @param $commission           - Commission to give
     *
     */
    public static function giveCommission($telegram_referent_id, $commission)
    {

        /**
         * Give commission
         */
        $referent_user = BotUsers::where('telegram_id', $telegram_referent_id)->first();
        $referent_user->commission = $referent_user->commision + $commission;
        $referent_user->balance = $referent_user->balance + $commission;
        $referent_user->save();


        /**
         * Log transaction
         */
        $transaction = new BotTransactions();
        $transaction->telegram_id = $telegram_referent_id;
        $transaction->amount = $commission;
        $transaction->status = 1;
        $transaction->type = "commission";
        $transaction->save();
    }
}
