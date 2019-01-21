<?php

declare(strict_types=1);

namespace App\OurBank\Customer;

use DumbJson\JsonRepository;

class Customers extends JsonRepository
{
    public function load(CustomerId $customerId): ?Customer
    {
        return $this->find($customerId->getId());
    }

    public function save(Customer $entity): void
    {
        $this->add($entity);
    }

    ///
    /// Internal methods for dumbJson
    ///

    protected function getTableName(): string
    {
        return 'customers';
    }
}
