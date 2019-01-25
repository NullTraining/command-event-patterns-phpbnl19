<?php

declare(strict_types=1);

namespace App\InternetBanking\Notifications\Emails;

use App\InternetBanking\Account\Event\MoneyDeposited;
use App\OurBank\Account\Accounts;
use App\OurBank\Customer\Customers;
use EmailSDK\EmailSender;

class SendDepositNotificationSubscriber
{
    /** @var Accounts */
    private $accounts;

    /** @var Customers */
    private $customers;

    /** @var EmailSender */
    private $emailSender;

    public function __construct()
    {
        $this->accounts    = new Accounts();
        $this->customers   = new Customers();
        $this->emailSender = new EmailSender();
    }

    public function onMoneyDeposited(MoneyDeposited $event)
    {
        $account  = $this->accounts->load($event->getAccountId());
        $customer = $this->customers->load($account->getCustomerId());

        $subject = 'You just received ...';
        $body    = ' Hi, We want to tell you that ...';

        $this->emailSender->send('bank@bank.com', $customer->getEmailAddressAsString(), $subject, $body);

        var_dump('Deposit mail sent');
    }
}
