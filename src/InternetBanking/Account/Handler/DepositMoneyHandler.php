<?php

declare(strict_types=1);

namespace App\InternetBanking\Account\Handler;

use App\InternetBanking\Account\Command\DepositMoney;
use App\InternetBanking\Account\Event\MoneyDeposited;
use App\OurBank\Account\Accounts;
use SimpleBus\SymfonyBridge\Bus\EventBus;

class DepositMoneyHandler
{
    /** @var Accounts */
    private $accounts;

    /** @var EventBus */
    private $eventBus;

    public function __construct(EventBus $eventBus)
    {
        $this->accounts = new Accounts();
        $this->eventBus = $eventBus;
    }

    public function handle(DepositMoney $command)
    {
        $account = $this->accounts->load($command->getAccountId());

        $account->deposit($command->getAmount());

        $this->accounts->save($account);

        $this->eventBus->handle(
            new MoneyDeposited($command->getAccountId(), $command->getAmount())
        );
    }
}
