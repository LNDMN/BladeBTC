<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Keyboards Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used for
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    "welcome" => "Welcome <b>:first_name :last_name</b> \xF0\x9F\x98\x84 \nTo explore me use controls below.\nTo get support please go to :support_chat_id",
    "welcome_back" => "Nice to see you again <b>:first_name :last_name</b> \xF0\x9F\x98\x84 \nTo explore me use controls below.\nTo get support please go to :support_chat_id",
    "btc_address" => "Here is your personal BTC address for your investments:",
    "btc_address_error" => "An error occurred while generating your payment address:\n\n<b>:error.</b> \xF0\x9F\x98\x96",
    "minimum_invest" => "You may invest at anytime and as much as you want. The minimum investment amount is <b>:minimum_invest BTC</b>.\n\nAfter correct transfer, your funds will be added to your account during an hour.\n\nHave fun and enjoy your daily profit!",
    "balance" => "\xF0\x9F\x91\x80 <b>Overview</b> \xF0\x9F\x91\x80\n
<b>Balance</b>
:balance_btc BTC ( $ :balance_usd USD )\n
<b>Invested</b>
:invested_btc BTC ( $ :invested_usd USD )\n
<b>Reinvested</b>
:reinvested_btc BTC ( $ :reinvested_usd USD )\n
<b>Profit</b>
:profit_btc BTC ( $ :profit_usd USD )\n
<b>Payout</b>
:payout_btc BTC ( $ :payout_usd USD )\n
<b>Commission</b>
:commission_btc BTC ( $ :commission_usd USD )\n
<b>Deposit (Confirmed)</b>
:deposit_btc BTC ( $ :deposit_usd USD )\n
<b>Deposit (Lower than minimum invest)</b>
:deposit_lower_btc BTC ( $ :deposit_lower_usd USD )\n
<b>Active investment</b>
:investment_btc BTC ( $ :investment_usd USD )",
    "investments" => "\xF0\x9F\x95\xA5 <b>Your investment</b> \xF0\x9F\x95\xA5\n:investments",
    "new_investment" => "You may start another investment by pressing the <b>Invest</b> button. Your balance will grow according to the base rate.",
    "reinvest_not_enough" => "Sorry to tell you that, but your balance isn't high enough for that!\n<b>Minimum: :minimum_reinvest BTC</b>",
    "reinvest_error" => "An error occurred while reinvesting your balance:\n\n<b>:error</b> \xF0\x9F\x98\x96",
    "reinvest_success" => "Congratulation your balance has been properly reinvested!"
];
