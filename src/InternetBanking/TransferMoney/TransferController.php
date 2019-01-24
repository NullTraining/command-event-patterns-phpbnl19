<?php

declare(strict_types=1);

namespace App\InternetBanking\TransferMoney;

use App\OurBank\Account\AccountId;
use App\OurBank\Account\Accounts;
use App\OurBank\Customer\CustomerId;
use App\OurBank\Customer\Customers;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TransferController extends AbstractController
{
    /** @var Accounts */
    private $accounts;

    /** @var Customers */
    private $customers;

    public function __construct()
    {
        $this->accounts  = new Accounts();
        $this->customers = new Customers();
    }

    public function index(Request $request): Response
    {
        $query         = $request->query;
        $customerId    = new CustomerId($query->get('customerId'));
        $fromAccountId = AccountId::fromString('ABC', $query->get('from'));
        $toAccountId   = AccountId::fromString('ABC', $query->get('to'));
        $amount        = $query->getInt('amount');

        if ($fromAccountId->getId() === $toAccountId->getId()) {
            return new Response('To account is same as from account', 400);
        }

        $fromAccount = $this->accounts->load($fromAccountId);
        $toAccount   = $this->accounts->load($toAccountId);

        if (null === $fromAccount) {
            return new Response('Unknown from account', 404);
        }

        if (null === $toAccount) {
            return new Response('Unknown to account', 404);
        }

        if ($customerId->getId() !== $fromAccount->getCustomerId()->getId()) {
            return new Response('Customer doesnt own the from account', 401);
        }

        if (false === $fromAccount->canWithdraw($amount)) {
            return new Response('Not enough money', 400);
        }

        // Withdraw money
        $fromAccount->withdraw($amount);
        // Deposit money
        $toAccount->deposit($amount);

        $this->accounts->save($fromAccount);
        $this->accounts->save($toAccount);

        return new Response('OK');
    }
}
