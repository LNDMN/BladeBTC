<?php

namespace App\Conversations;

use App\Helpers\Btc;
use App\Helpers\Wallet;
use App\Models\BotInvestmentPlans;
use App\Models\BotUsers;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Exception;

class WithdrawConversation extends Conversation
{

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->askQuestionWithdraw();
    }


    /**
     * First question
     */
    public function askQuestionWithdraw()
    {

        /**
         * User built from database value
         */
        $user = BotUsers::getCreate($this->bot->getUser());


        /**
         * Get current wallet address
         */
        $wallet_address = $user->wallet_address;


        /**
         * Check if user address is NULL
         */
        if (is_null($wallet_address)) {
            $this->say(__("messages.withdraw_msg_1"));
        }


        /**
         * Withdrawal address id valid - validate with user if this is the address he want the payment.
         */
        else {


            /**
             * Question 1
             */
            $question = Question::create(__("messages.withdraw_question_1", [ "wallet_address" => $wallet_address ]))
                ->callbackId('check_wallet_address')
                ->addButtons([
                    Button::create(__("messages.withdraw_option_yes"))->value('yes'),
                    Button::create(__("messages.withdraw_option_no"))->value('no')
                ]);


            $this->ask($question, function (Answer $answer) {

                /**
                 * Detect if button was clicked:
                 */
                if ($answer->isInteractiveMessageReply()) {

                    /**
                     * will be either 'yes' or 'no'
                     */
                    $selectedValue = $answer->getValue();

                    /**
                     * Start withdraw transaction
                     */
                    if ($selectedValue == "yes") {
                        $this->askWithdrawAmount();
                    }

                    /**
                     * cancel withdraw transaction
                     */
                    if ($selectedValue == "no") {
                        $this->say(__("messages.withdraw_msg_2"), [ "parse_mode" => "HTML" ]);
                    }
                }

                /**
                 * Invalid action repeat question with a warning
                 */
                else {
                    $this->repeat();
                    $this->say(__("messages.wallet_msg_3"));
                }
            }, [ "parse_mode" => "HTML" ]);

        }

    }


    /**
     * Second question
     */
    public function askWithdrawAmount()
    {

        $question = Question::create(__("messages.withdraw_msg_3"))
            ->callbackId('get_withdraw_amount');

        $this->ask($question, function (Answer $answer) {


            /**
             * Get user input
             */
            $withdraw_amount = $answer->getText();

            /**
             * Cancel transaction
             */
            if (strtolower($withdraw_amount) == "cancel") {
                $this->say(__("messages.withdraw_msg_5"));
                exit;
            }

            /**
             * Validate amount format
             */
            if (!is_numeric($withdraw_amount)) {

                $this->say(__("messages.withdraw_msg_4"), [ "parse_mode" => "HTML" ]);
                $this->repeat();
            }

            else {

                /**
                 * Get investment plan - minimum withdraw amount
                 */
                $minimum_withdraw = BotInvestmentPlans::where('active', 1)->first()->minimum_payout;


                /**
                 * User requested by user is lower than the minimum withdraw amount.
                 */
                if ($withdraw_amount < $minimum_withdraw) {
                    $this->say(__("messages.withdraw_msg_6", [ "minimum_withdraw" => $minimum_withdraw ]), [ "parse_mode" => "HTML" ]);
                    $this->repeat();
                }

                else {


                    /**
                     * Get current user balance
                     */
                    $user = BotUsers::getCreate($this->bot->getUser());
                    $user_balance = $user->balance;
                    $user_wallet_address = $user->wallet_address;


                    /**
                     * User balance lower than the request amount
                     */
                    if ($user_balance < $withdraw_amount) {
                        $this->say(__("messages.withdraw_msg_7"), [ "parse_mode" => "HTML" ]);
                        exit;
                    }


                    /**
                     * Withdraw
                     */
                    else {


                        try {

                            /**
                             * Do transaction
                             */
                            $transaction = Wallet::makeOutgoingPayment($user_wallet_address, Btc::BtcToSatoshi($withdraw_amount));


                            /**
                             * Check transaction results
                             */
                            if (!empty($transaction) && empty($transaction->error)) {

                                /**
                                 * Update user balance
                                 */
                                $user->updateBalance($out_amount, $transaction);

                                $msg = "Message :\n<b>" . $transaction->message . "</b>\n" . "Transaction ID:\n<b>" . $transaction->txid . "</b>\n" . "Transaction Hash:\n<b>" . $transaction->tx_hash . "</b>";

                            }
                            else {

                                $msg = "An error occurred while withdrawing your BTC.\n<b>[Error] " . $transaction->error . "</b>. \xF0\x9F\x98\x96";

                            }


                        } catch (Exception $e) {

                            $this->say(__("messages.withdraw_msg_8", [ "error_msg" => $e->getMessage() ]), [ "parse_mode" => "HTML" ]);
                        }
                    }
                }
            }

        }, [ "parse_mode" => "HTML" ]);
    }
}
