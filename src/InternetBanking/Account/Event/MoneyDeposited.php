<?php

declare(strict_types=1);

namespace App\InternetBanking\Account\Event;

use App\OurBank\Account\AccountId;

class MoneyDeposited
{
    /** @var AccountId */
    private $accountId;

    /** @var int */
    private $amount;

    public function __construct(AccountId $accountId, int $amount)
    {
        $this->accountId = $accountId;
        $this->amount    = $amount;
    }

    public function getAccountId(): AccountId
    {
        return $this->accountId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
