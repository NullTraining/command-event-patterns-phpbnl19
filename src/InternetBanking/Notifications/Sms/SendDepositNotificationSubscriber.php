<?php

declare(strict_types=1);

namespace App\InternetBanking\Notifications\Sms;

use App\InternetBanking\Account\Event\MoneyDeposited;
use App\OurBank\Account\Accounts;
use App\OurBank\Customer\Customers;
use ShortMessageServiceSDK\SmsSender;

class SendDepositNotificationSubscriber
{
    /** @var Accounts */
    private $accounts;

    /** @var Customers */
    private $customers;

    /** @var SmsSender */
    private $smsSender;

    public function __construct()
    {
        $this->accounts  = new Accounts();
        $this->customers = new Customers();
        $this->smsSender = new SmsSender();
    }

    public function onMoneyDeposited(MoneyDeposited $event)
    {
        $account  = $this->accounts->load($event->getAccountId());
        $customer = $this->customers->load($account->getCustomerId());

        $message = 'You just got money...';
        $this->smsSender->send($customer->getPhoneNumberAsString(), $message);
        var_dump('Deposit sms sent');
    }
}
