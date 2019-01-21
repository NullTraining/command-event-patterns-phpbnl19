<?php

declare(strict_types=1);

namespace App\OurBank\Account;

use App\OurBank\Customer\CustomerId;
use DumbJson\JsonRepository;

class Accounts extends JsonRepository
{
    public function load(AccountId $customerId): ?Account
    {
        return $this->find($customerId->getId());
    }

    public function save(Account $entity): void
    {
        $this->add($entity);
    }

    public function findByCustomer(CustomerId $customerId): array
    {
        $results = [];

        foreach ($this->getResults() as $account) {
            if ($account->getCustomerId() == $customerId) {
                $results[] = $account;
            }
        }

        return $results;
    }

    ///
    /// Internal methods for dumbJson
    ///

    protected function getTableName(): string
    {
        return 'accounts';
    }
}
