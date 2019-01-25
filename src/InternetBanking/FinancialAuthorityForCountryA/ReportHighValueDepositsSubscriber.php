<?php

declare(strict_types=1);

namespace App\InternetBanking\FinancialAuthorityForCountryA;

use App\InternetBanking\Account\Event\MoneyDeposited;
use FinancialAuthoritySDK\TransactionSender;

class ReportHighValueDepositsSubscriber
{
    private $sender;

    public function __construct()
    {
        $this->sender = new TransactionSender();
    }

    public function onMoneyDeposited(MoneyDeposited $event)
    {
        if ($event->getAmount() <= 10) {
            return;
        }

        $this->sender->send($event->fromAccountId(), $event->toAccountId(), $event->getAmount());
    }
}
