<?php

declare(strict_types=1);

namespace App\InternetBanking\Account\Handler;

use App\InternetBanking\Account\Command\WithdrawMoney;
use App\InternetBanking\Account\Event\MoneyWithdrawn;
use App\OurBank\Account\Account;
use App\OurBank\Account\Accounts;
use App\OurBank\Customer\CustomerId;
use SimpleBus\SymfonyBridge\Bus\EventBus;

class WithdrawMoneyHandler
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

    public function handle(WithdrawMoney $command)
    {
        $account = $this->accounts->load($command->getAccountId());

        $this->guardCurrentUserOwnsAccount($command->getCustomerId(), $account);
        $this->guardEnoughMoneyOnSourceAccount($account, $command->getAmount());

        $account->withdraw($command->getAmount());
        $this->accounts->save($account);

        $moneyWithdrawn = new MoneyWithdrawn($command->getAccountId(), $command->getAmount());

        $this->eventBus->handle($moneyWithdrawn);
        var_dump('I work!!!');
    }

    private function guardCurrentUserOwnsAccount(CustomerId $customerId, Account $account): void
    {
        if ($customerId->getId() !== $account->getCustomerId()->getId()) {
            throw new \Exception('Customer doesnt own the from account');
        }
    }

    private function guardEnoughMoneyOnSourceAccount(Account $account, int $amount): void
    {
        if (false === $account->canWithdraw($amount)) {
            throw new \Exception('Not enough money');
        }
    }
}
