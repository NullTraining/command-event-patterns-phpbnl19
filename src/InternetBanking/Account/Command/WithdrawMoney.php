<?php

declare(strict_types=1);

namespace App\InternetBanking\Account\Command;

use App\OurBank\Account\AccountId;
use App\OurBank\Customer\CustomerId;

class WithdrawMoney
{
    /** @var AccountId */
    private $accountId;

    /** @var int */
    private $amount;

    /** @var CustomerId */
    private $customerId;

    public function __construct(AccountId $accountId, int $amount, CustomerId $customerId)
    {
        $this->accountId  = $accountId;
        $this->amount     = $amount;
        $this->customerId = $customerId;
    }

    public function getAccountId(): AccountId
    {
        return $this->accountId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCustomerId(): CustomerId
    {
        return $this->customerId;
    }
}
