<?php

namespace App\Http\Controllers;


use App\Conversations\WalletConversation;
use App\Conversations\WithdrawConversation;
use App\Helpers\Btc;
use App\Helpers\Wallet;
use App\Keyboards\MainKeyboard;
use App\Models\BotInvestmentPlans;
use App\Models\BotInvestments;
use App\Models\BotSettings;
use App\Models\BotUsers;
use BotMan\BotMan\BotMan;
use Carbon\Carbon;
use Exception;


class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $botman = app('botman');

        $botman->listen();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinker()
    {
        return view('tinker');
    }

    /**
     * start controller
     *
     * @param BotMan $bot
     */
    public function start(BotMan $bot)
    {

        /**
         * User built from database value
         */
        $user = BotUsers::getCreate($bot->getUser());


        /**
         * Support chat Id
         */
        $support_chat_id = BotSettings::where('id', 1)->first()->support_chat_id;


        /**
         * Send welcome
         */
        $bot->reply(
            __($user->isNew == 1 ? 'messages.welcome' : 'messages.welcome_back',
                [
                    'first_name' => $user->telegram_firstname,
                    'last_name' => $user->telegram_lastname,
                    'support_chat_id' => $support_chat_id
                ]),
            array_add(MainKeyboard::get($user->balance), "parse_mode", "HTML")
        );
    }

    /**
     * Invest controller
     *
     * @param BotMan $bot
     */
    public function invest(BotMan $bot)
    {

        /**
         * User built from database value
         */
        $user = BotUsers::getCreate($bot->getUser());

        /**
         * Generate payment address
         */
        $payment_address = Wallet::generateAddress($user->telegram_id);

        /**
         * Validate payment address and reply
         */
        if (isset($payment_address->address)) {

            try {

                /**
                 * Store investment_address
                 */
                $user->investment_address = $payment_address->address;
                $user->save();
                $user->refresh();

                /**
                 * Get investment Plan Detail
                 */
                $minimum_invest = BotInvestmentPlans::where('active', 1)->first()->minimum_invest;

                /**
                 * Response
                 */
                $bot->reply(
                    __('messages.btc_address'),
                    array_add(MainKeyboard::get($user->balance), "parse_mode", "HTML")
                );

                $bot->reply(
                    "<b>" . $user->investment_address . "</b>",
                    array_add(MainKeyboard::get($user->balance), "parse_mode", "HTML")
                );

                $bot->reply(
                    __("messages.minimum_invest",
                        [
                            "minimum_invest" => $minimum_invest
                        ]),
                    array_add(MainKeyboard::get($user->balance), "parse_mode", "HTML")
                );

            } catch (Exception $e) {

                $bot->reply(
                    __("messages.btc_address_error",
                        [
                            "error" => $e->getMessage()
                        ]),
                    array_add(MainKeyboard::get($user->balance), "parse_mode", "HTML")
                );
            }
        }
        else {

            $bot->reply(
                __("messages.btc_address_error",
                    [
                        "error" => $payment_address->error
                    ]),
                array_add(MainKeyboard::get($user->balance), "parse_mode", "HTML")
            );
        }
    }

    /**
     * Reinvest controller
     *
     * @param BotMan $bot
     */
    public function reinvest(BotMan $bot)
    {

        /**
         * User built from database value
         */
        $user = BotUsers::getCreate($bot->getUser());


        /**
         * Get active investment plan minimum reinvest value
         */
        $minimum_reinvest = BotInvestmentPlans::where('active', 1)->first()->minimum_reinvest;


        /**
         * Check if balance is lower than the minimum reinvest
         */
        if ($user->balance < $minimum_reinvest) {

            $bot->reply(
                __("messages.reinvest_not_enough", [
                    "minimum_reinvest" => $minimum_reinvest
                ]),
                array_add(MainKeyboard::get($user->balance), "parse_mode", "HTML")
            );
        }
        else {

            try {

                /**
                 * Reinvest balance
                 */
                $user->Reinvest();
                $user->Refresh();

                /**
                 * Response
                 */
                $bot->reply(
                    __("messages.reinvest_success"),
                    array_add(MainKeyboard::get($user->balance), "parse_mode", "HTML")
                );

            } catch (Exception $e) {

                $bot->reply(
                    __("messages.reinvest_error", [
                        "error" => $e->getMessage()
                    ]),
                    array_add(MainKeyboard::get($user->balance), "parse_mode", "HTML")
                );
            }
        }
    }

    /**
     * Balance controller
     *
     * @param BotMan $bot
     */
    public function balance(BotMan $bot)
    {

        /**
         * User built from database value
         */
        $user = BotUsers::getCreate($bot->getUser());


        /**
         * Get active investment plan base rate
         */
        $base_rate = BotInvestmentPlans::where('active', 1)->first()->base_rate;


        /**
         * Get active investment plan base rate
         */
        $minimum_invest = BotInvestmentPlans::where('active', 1)->first()->minimum_invest;


        /**
         * Get user investments list
         */
        $investment = BotInvestments::where('telegram_id', $user->telegram_id)->where('contract_end_date', '>', Carbon::now())->get();


        /**
         * Get user investment total
         */
        $investment_total = 0;


        /**
         * Format investment list for the reply
         */
        if (count($investment) > 0) {
            $investment_data = "\n<b>Amount      |   Rate   |   End</b>\n";
            foreach ($investment as $row) {
                $investment_total += $row->amount;
                $investment_data .= $row->amount . " |   " . $base_rate . "%      |  " . $row->contract_end_date . "\n";
            }
        }
        else {
            $investment_data = "No active investment, start now with just " . $minimum_invest . " BTC";
        }


        /**
         * Reply
         */
        $bot->reply(
            __("messages.balance", [
                "balance_btc" => Btc::Format($user->balance),
                "balance_usd" => Btc::FormatUSD($user->balance),
                "invested_btc" => Btc::Format($user->invested),
                "invested_usd" => Btc::FormatUSD($user->invested),
                "reinvested_btc" => Btc::Format($user->reinvested),
                "reinvested_usd" => Btc::FormatUSD($user->reinvested),
                "profit_btc" => Btc::Format($user->profit),
                "profit_usd" => Btc::FormatUSD($user->profit),
                "payout_btc" => Btc::Format($user->payout),
                "payout_usd" => Btc::FormatUSD($user->payout),
                "commission_btc" => Btc::Format($user->commission),
                "commission_usd" => Btc::FormatUSD($user->commission),
                "deposit_btc" => Btc::Format($user->last_confirmed),
                "deposit_usd" => Btc::FormatUSD($user->last_confirmed),
                "deposit_lower_btc" => Btc::Format($user->last_confirmed - $user->invested),
                "deposit_lower_usd" => Btc::FormatUSD($user->last_confirmed - $user->invested),
                "investment_btc" => Btc::Format($investment_total),
                "investment_usd" => Btc::FormatUSD($investment_total),
            ]),
            array_add(MainKeyboard::get($user->balance), "parse_mode", "HTML")
        );


        $bot->reply(
            __("messages.investments", [
                "investments" => $investment_data
            ]),
            array_add(MainKeyboard::get($user->balance), "parse_mode", "HTML")
        );


        $bot->reply(
            __("messages.new_investment"),
            array_add(MainKeyboard::get($user->balance), "parse_mode", "HTML")
        );
    }

    /**
     * Withdraw controller
     *
     * @param BotMan $bot
     */
    public function withdraw(BotMan $bot)
    {
        $bot->startConversation(new WithdrawConversation());
    }

    /**
     * Withdraw controller
     *
     * @param BotMan $bot
     */
    public function wallet(BotMan $bot)
    {
        $bot->startConversation(new WalletConversation());
    }

    /**
     * Team controller
     *
     * @param BotMan $bot
     */
    public function team(BotMan $bot)
    {

        /**
         * User built from database value
         */
        $user = BotUsers::getCreate($bot->getUser());

        $bot->reply(
            "Team",
            array_add(MainKeyboard::get($user->balance), "parse_mode", "HTML")
        );
    }

    /**
     * Team controller
     *
     * @param BotMan $bot
     */
    public function stats(BotMan $bot)
    {

        /**
         * User built from database value
         */
        $user = BotUsers::getCreate($bot->getUser());

        $bot->reply(
            "Stats",
            array_add(MainKeyboard::get($user->balance), "parse_mode", "HTML")
        );
    }

    /**
     * Help controller
     *
     * @param BotMan $bot
     */
    public function help(BotMan $bot)
    {

        /**
         * User built from database value
         */
        $user = BotUsers::getCreate($bot->getUser());

        $bot->reply(
            "Help",
            array_add(MainKeyboard::get($user->balance), "parse_mode", "HTML")
        );
    }

    /**
     * Help controller
     *
     * @param BotMan $bot
     */
    public function language(BotMan $bot)
    {

        /**
         * User built from database value
         */
        $user = BotUsers::getCreate($bot->getUser());

        $bot->reply(
            "Language",
            array_add(MainKeyboard::get($user->balance), "parse_mode", "HTML")
        );
    }
}
