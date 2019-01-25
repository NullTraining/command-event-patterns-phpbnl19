<?php

declare(strict_types=1);

namespace App\InternetBanking\Notifications\Emails;

use App\InternetBanking\Account\Event\MoneyWithdrawn;
use App\OurBank\Account\Accounts;
use App\OurBank\Customer\Customers;
use EmailSDK\EmailSender;

class SendWithdrawalNotificationSubscriber
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

    public function onMoneyWithdrawn(MoneyWithdrawn $event)
    {
        $account  = $this->accounts->load($event->getAccountId());
        $customer = $this->customers->load($account->getCustomerId());

        $subject = 'New withdrawal ...';
        $body    = ' Hi, We want to tell you that ...';

        $this->emailSender->send('bank@bank.com', $customer->getEmailAddressAsString(), $subject, $body);

        var_dump('Email sent');

        throw new \Exception('HAHAHAHA');
    }
}
