<?php

namespace App\Keyboards;

use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;

/**
 * Class MainKeyboard
 *
 * @package App\Keyboards
 */
class MainKeyboard
{
    /**
     * Return Main Application Keyboard
     *
     * @param $user_balance - Current user balance
     *
     * @return array
     */
    public static function get($user_balance)
    {
        return Keyboard::create()
            ->type(Keyboard::TYPE_KEYBOARD)
            ->oneTimeKeyboard(false)
            ->addRow(
                KeyboardButton::create(__('keyboard.balance', [ 'user_balance' => $user_balance ]))
            )
            ->addRow(
                KeyboardButton::create(__('keyboard.invest')),
                KeyboardButton::create(__('keyboard.reinvest')),
                KeyboardButton::create(__('keyboard.withdraw'))

            )
            ->addRow(
                KeyboardButton::create(__('keyboard.wallet')),
                KeyboardButton::create(__('keyboard.team')),
                KeyboardButton::create(__('keyboard.stats'))
            )
            ->addRow(
                KeyboardButton::create(__('keyboard.help')),
                KeyboardButton::create(__('keyboard.language'))
            )
            ->toArray();
    }
}