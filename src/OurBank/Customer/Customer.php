<?php

declare(strict_types=1);

namespace App\OurBank\Customer;

use App\Generic\EmailAddress;
use App\Generic\PhoneNumber;

class Customer
{
    /** @var CustomerId */
    private $customerId;

    /** @var string */
    private $firstName;

    /** @var string */
    private $lastName;

    /** @var EmailAddress */
    private $emailAddress;

    /** @var PhoneNumber */
    private $phoneNumber;

    public function __construct(
        CustomerId $customerId,
        string $firstName,
        string $lastName,
        EmailAddress $emailAddress,
        PhoneNumber $phoneNumber
    ) {
        $this->customerId   = $customerId;
        $this->firstName    = $firstName;
        $this->lastName     = $lastName;
        $this->emailAddress = $emailAddress;
        $this->phoneNumber  = $phoneNumber;
    }

    public function getCustomerId(): CustomerId
    {
        return $this->customerId;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmailAddress(): EmailAddress
    {
        return $this->emailAddress;
    }

    public function getPhoneNumber(): PhoneNumber
    {
        return $this->phoneNumber;
    }

    ///
    /// Internal methods for dumbJson
    ///

    public function serialize(): array
    {
        return [
            '__className'  => get_class($this),
            'customerId'   => $this->customerId->getId(),
            'firstName'    => $this->firstName,
            'lastName'     => $this->lastName,
            'emailAddress' => $this->emailAddress->getValue(),
            'phoneNumber'  => $this->phoneNumber->getValue(),
        ];
    }

    public static function deserialize(array $data): self
    {
        return new self(
            new CustomerId($data['customerId']),
            $data['firstName'],
            $data['lastName'],
            new EmailAddress($data['emailAddress']),
            new PhoneNumber($data['phoneNumber'])
        );
    }

    public function getEntityId(): string
    {
        return $this->customerId->getId();
    }
}
