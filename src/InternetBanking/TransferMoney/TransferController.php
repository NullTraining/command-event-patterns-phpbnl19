<?php

declare(strict_types=1);

namespace App\InternetBanking\TransferMoney;

use App\InternetBanking\Account\Command\DepositMoney;
use App\InternetBanking\Account\Command\WithdrawMoney;
use App\OurBank\Account\AccountId;
use App\OurBank\Customer\CustomerId;
use InterBankSDK\InterBankClient;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TransferController extends AbstractController
{
    /** @var InterBankClient */
    private $interBankClient;

    /** @var CommandBus */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->interBankClient = new InterBankClient();
        $this->commandBus      = $commandBus;
    }

    public function transfer(Request $request): Response
    {
        $query      = $request->query;
        $customerId = new CustomerId($query->get('customerId'));
        $sourceId   = AccountId::fromString('ABC', $query->get('from'));
        $targetId   = AccountId::fromString('ABC', $query->get('to'));
        $amount     = $query->getInt('amount');

        $withdrawMoney = new WithdrawMoney($sourceId, $amount, $customerId);
        $depositMoney  = new DepositMoney($targetId, $amount);

        $this->commandBus->handle($withdrawMoney);
        $this->commandBus->handle($depositMoney);

        return new Response('OK');
    }

    public function outgoingExternalTransfer(Request $request): Response
    {
        $query      = $request->query;
        $customerId = new CustomerId($query->get('customerId'));
        $sourceId   = AccountId::fromString('ABC', $query->get('from'));
        $targetId   = AccountId::fromString('ABC', $query->get('to'));
        $amount     = $query->getInt('amount');

        $withdrawMoney = new WithdrawMoney($sourceId, $amount, $customerId);
        $this->commandBus->handle($withdrawMoney);
        $this->notifyReceivingBank($targetId, $amount);

        return new Response('OK');
    }

    public function incomingExternalTransfer(Request $request): Response
    {
        $query         = $request->query;
        $transactionId = $query->get('transactionId');
        $sourceId      = AccountId::fromString('ABC', $query->get('from'));
        $targetId      = AccountId::fromString('ABC', $query->get('to'));
        $amount        = $query->getInt('amount');

        $depositMoney = new DepositMoney($targetId, $amount);
        $this->commandBus->handle($depositMoney);

        $this->confirmTransaction($transactionId, $sourceId, $amount);

        return new Response('OK');
    }

    private function notifyReceivingBank(AccountId $accountId, int $amount): void
    {
        $this->interBankClient->send('transaction-id', $accountId->getId(), $amount);
    }

    private function confirmTransaction(string $transactionId, AccountId $accountId, int $amount): void
    {
        $this->interBankClient->confirm($transactionId, $accountId->getId(), $amount);
    }
}
