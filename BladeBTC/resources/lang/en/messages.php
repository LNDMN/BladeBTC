<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Messages Language Lines
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
    "reinvest_success" => "Congratulation your balance has been properly reinvested!",
    "wallet_not_set" => "Not set",
    "wallet_question_1" => "\xF0\x9F\x94\x91 <b>Wallet Overview</b> \xF0\x9F\x94\x91\n\nCurrently, your withdrawal address is:\n\n<b>:wallet_address</b>\n\nWhat do you like to do now?",
    "wallet_option_1" => "Update Withdrawal Address",
    "wallet_option_2" => "Delete My Withdrawal Address",
    "wallet_option_3" => "Return to Bot",
    "wallet_msg_1" => "Your account was successfully updated.",
    "wallet_msg_2" => "No changes have been made to your account.",
    "wallet_msg_3" => "Please select one of the available actions in the chat box.",
    "wallet_msg_4" => "Please enter your withdrawal address in the chat box.",
    "wallet_msg_5" => "Your account was successfully updated.",
    "wallet_msg_6" => "Your address is now: <b>:wallet_address</b>",
    "wallet_msg_7" => "The BTC address you provided is invalid.\nNo changes have been made to your account.\nTry again!",
    "withdraw_option_yes" => "Yes",
    "withdraw_option_no" => "No",
    "withdraw_question_1" => "Your withdrawal address is:\n\n<b>:wallet_address</b>\n\nPlease confirm that is correct?",
    "withdraw_msg_1" => "Your withdrawal address is currently not set. Please use the `Wallet` button to setup your withdrawal address into your account.",
    "withdraw_msg_2" => "OK! While you said the withdrawal address is invalid, you need to got to <b>`Wallet`</b> option and update your account with your new address. Start the withdraw process again after that.",
    "withdraw_msg_3" => "Please enter the amount to withdraw?",
    "withdraw_msg_4" => "The amount you submitted is not valid. It must be in numerical format. To cancel this transaction type <b>cancel</b>",
    "withdraw_msg_5" => "The transaction was cancel at your request!",
    "withdraw_msg_6" => "The minimum withdraw amount is currently set to <b>:minimum_withdraw</b>. Please enter an amount higher than this minimum.",
    "withdraw_msg_7" => "I'm sorry to tell you this but your account have not enough balance.",
    "withdraw_msg_8" => "An error occurred during withdraw process.\n:error_msg \xF0\x9F\x98\x96",

];

