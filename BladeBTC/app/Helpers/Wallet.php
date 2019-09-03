<?php


namespace App\Helpers;

use App\Models\BotInvestmentPlans;
use App\Models\BotSettings;
use App\Models\BotUsers;
use stdClass;

class Wallet
{

    /**
     * Generate payment address
     *
     * @param $telegram_user_id - ID of the current user requesting address
     *
     * @return object - Payment address
     */
    public static function generateAddress($telegram_user_id)
    {

        /**
         * Select address from users database if exist
         */
        $wallet_address = BotUsers::where('telegram_id', $telegram_user_id)->first()->investment_address;
        if (!is_null($wallet_address) || !empty($wallet_address)) {
            $data = new stdClass();
            $data->address = $wallet_address;
        }
        else {

            /**
             * Param
             */
            $wallet = BotSettings::where('id', 1)->first()->wallet_id;
            $main_password = BotSettings::where('id', 1)->first()->wallet_password;
            $second_password = BotSettings::where('id', 1)->first()->wallet_second_password;
            $label = $telegram_user_id;

            /**
             * Request URL
             */
            $url = "http://127.0.0.1:3000/merchant/$wallet/new_address?password=$main_password&second_password=$second_password&label=$label";

            /**
             * Request
             */
            $data = Curl::get($url);
        }
        return $data;
    }



    /**
     * Get wallet balance
     *
     * @return mixed
     */
    public static function getWalletBalance()
    {

        /**
         * Param
         */
        $wallet = BotSettings::where('id', 1)->first()->wallet_id;
        $main_password = BotSettings::where('id', 1)->first()->wallet_password;
        $second_password = BotSettings::where('id', 1)->first()->wallet_second_password;

        /**
         * Request URL
         */
        $url = "http://127.0.0.1:3000/merchant/$wallet/balance?password=$main_password&second_password=$second_password";

        /**
         * Request
         */
        $data = Curl::get($url);
        return $data->balance;
    }


    /**
     * Send bitcoin to a specific address
     *
     * @param $to_wallet_address - Wallet address
     * @param $satoshi_amount    - Satoshi amount
     *
     * @return object - Message
     */
    public static function makeOutgoingPayment($to_wallet_address, $satoshi_amount)
    {

        /**
         * Param
         */
        $wallet = BotSettings::where('id', 1)->first()->wallet_id;
        $main_password = BotSettings::where('id', 1)->first()->wallet_password;
        $second_password = BotSettings::where('id', 1)->first()->wallet_second_password;
        $fee = BotInvestmentPlans::where('active', 1)->first()->withdraw_fee;

        /**
         * Removing transaction fee
         */
        $send_amount_without_fee = $satoshi_amount - $fee;

        /**
         * Request URL
         */
        $url = "http://127.0.0.1:3000/merchant/$wallet/payment?password=$main_password&second_password=$second_password&to=$to_wallet_address&amount=$send_amount_without_fee&fee=$fee";
        $data = Curl::get($url);
        return $data;
    }


    /**
     * List address
     *
     * @return mixed
     * @see    https://blockchain.info/q/getblockcount
     */
    public static function listAddress()
    {

        /**
         * Param
         */
        $wallet = BotSettings::where('id', 1)->first()->wallet_id;
        $main_password = BotSettings::where('id', 1)->first()->wallet_password;
        $second_password = BotSettings::where('id', 1)->first()->wallet_second_password;

        /**
         * Request URL
         */
        $url = "http://127.0.0.1:3000/merchant/$wallet/list?password=$main_password&second_password=$second_password";

        /**
         * Request
         */
        $data = Curl::get($url, true);
        return $data;
    }


    /**
     * Get the amount received and confirmed for an address
     *
     * @param $address
     *
     * @return bool|string
     */
    public static function getConfirmedReceivedByAddress($address)
    {
        $url = "https://blockchain.info/q/getreceivedbyaddress/$address?confirmations=" . BotInvestmentPlans::where('active', 1)->first()->required_confirmations;
        $data = Curl::getRaw($url);
        return $data;
    }
}