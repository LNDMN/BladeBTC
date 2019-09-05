<?php

namespace App\Conversations;

use App\Helpers\AddressValidator;
use App\Models\BotUsers;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class WalletConversation extends Conversation
{

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->askQuestionWallet();
    }


    /**
     * First question
     */
    public function askQuestionWallet()
    {

        /**
         * User built from database value
         */
        $user = BotUsers::getCreate($this->bot->getUser());


        /**
         * Get current wallet address
         */
        $wallet_address = is_null($user->wallet_address) ? __("messages.wallet_not_set") : $user->wallet_address;


        /**
         * Question 1
         */
        $question = Question::create(__("messages.wallet_question_1", [ "wallet_address" => $wallet_address ]))
            ->callbackId('update_wallet_address')
            ->addButtons([
                Button::create(__("messages.wallet_option_1"))->value('set'),
                Button::create(__("messages.wallet_option_2"))->value('delete'),
                Button::create(__("messages.wallet_option_3"))->value('nothing'),
            ]);


        $this->ask($question, function (Answer $answer) {

            /**
             * Detect if button was clicked:
             */
            if ($answer->isInteractiveMessageReply()) {

                /**
                 * will be either 'set' or 'delete' or 'nothing'
                 */
                $selectedValue = $answer->getValue();

                if ($selectedValue == "set") {
                    $this->askWalletAddress();
                }

                /**
                 * Delete wallet address
                 */
                if ($selectedValue == "delete") {
                    $user = BotUsers::getCreate($this->bot->getUser());
                    $user->wallet_address = null;
                    $user->save();
                    $this->say(__("messages.wallet_msg_1"));
                }

                /**
                 * Exit conversation
                 */
                if ($selectedValue == "nothing") {
                    $this->say(__("messages.wallet_msg_2"));
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


    /**
     * Second question
     */
    public function askWalletAddress()
    {
        $question = Question::create(__("messages.wallet_msg_4"))
            ->callbackId('get_wallet_address');

        $this->ask($question, function (Answer $answer) {

            /**
             * Get user input
             */
            $new_wallet_address = $answer->getText();

            /**
             * Validate and update wallet address
             */
            if (AddressValidator::isValid($new_wallet_address)) {
                $user = BotUsers::getCreate($this->bot->getUser());
                $user->wallet_address = $new_wallet_address;
                $user->save();
                $this->say(__("messages.wallet_msg_5"));
                $this->say(__("messages.wallet_msg_6", [ "wallet_address" => $new_wallet_address ]), [ "parse_mode" => "HTML" ]);
            }

            /**
             * Invalid wallet address - warning
             */
            else {
                $this->say(__("messages.wallet_msg_7"));
            }
        }, [ "parse_mode" => "HTML" ]);
    }
}
