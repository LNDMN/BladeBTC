<?php

use App\Http\Controllers\BotManController;

$botman = resolve('botman');

/**
 * Commands
 */
$botman->hears('.*start.*', BotManController::class . '@start');
$botman->hears('.*Balance.*', BotManController::class . '@balance');
$botman->hears('.*Invest.*', BotManController::class . '@invest');
$botman->hears('.*Withdraw.*', BotManController::class . '@withdraw');
$botman->hears('.*Reinvest.*', BotManController::class . '@reinvest');
$botman->hears('.*Help.*', BotManController::class . '@help');
$botman->hears('.*Team.*', BotManController::class . '@team');

/**
 * Error
 */
$botman->fallback(function ($bot) {
    $bot->reply('Sorry, I did not understand these commands.');
});