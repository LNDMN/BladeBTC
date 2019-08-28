<?php

namespace App\Http\Controllers;


use App\Models\BotUsers;
use App\Keyboards\MainKeyboard;
use BotMan\BotMan\BotMan;


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
         * User interacting with the bot
         */
        $user = $bot->getUser();


        /**
         * Check if user is in our database
         */
        $user_exist = BotUsers::where('telegram_id', $user->getId())->get();


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
             * Send welcome
             */
            $bot->reply(
                __('messages.welcome',
                    [
                        'first_name' => $user->getFirstName(),
                        'last_name' => $user->getLastName()
                    ]),
                array_add(MainKeyboard::get($new_user->balance), "parse_mode", "HTML")
            );
        }


        /**
         * User existing
         */
        else {

            /**
             * Send welcome back
             */
            $bot->reply(
                __('messages.welcome_back',
                    [
                        'first_name' => $user->getFirstName(),
                        'last_name' => $user->getLastName()
                    ]),
                array_add(MainKeyboard::get($user_exist[0]->balance), "parse_mode", "HTML")
            );
        }
    }


    /**
     * Balance controller
     *
     * @param BotMan $bot
     */
    public function balance(BotMan $bot)
    {
        $bot->reply(
            "Balance",
            array_add(MainKeyboard::get(), "parse_mode", "HTML")
        );
    }

    /**
     * Invest controller
     *
     * @param BotMan $bot
     */
    public function invest(BotMan $bot)
    {
        $bot->reply(
            "Invest",
            array_add(MainKeyboard::get(), "parse_mode", "HTML")
        );
    }

    /**
     * Withdraw controller
     *
     * @param BotMan $bot
     */
    public function withdraw(BotMan $bot)
    {
        $bot->reply(
            "Withdraw",
            array_add(MainKeyboard::get(), "parse_mode", "HTML")
        );
    }

    /**
     * Reinvest controller
     *
     * @param BotMan $bot
     */
    public function reinvest(BotMan $bot)
    {
        $bot->reply(
            "Reinvest",
            array_add(MainKeyboard::get(), "parse_mode", "HTML")
        );
    }

    /**
     * Help controller
     *
     * @param BotMan $bot
     */
    public function help(BotMan $bot)
    {
        $bot->reply(
            "Help",
            array_add(MainKeyboard::get(), "parse_mode", "HTML")
        );
    }

    /**
     * Team controller
     *
     * @param BotMan $bot
     */
    public function team(BotMan $bot)
    {
        $bot->reply(
            "Team",
            array_add(MainKeyboard::get(), "parse_mode", "HTML")
        );
    }
}
